<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Ichtrojan\Otp\Otp;
use App\Http\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use libphonenumber\PhoneNumberUtil;
use Illuminate\Support\Facades\Auth;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use Illuminate\Support\Facades\Validator;

class OtpController extends Controller
{

    use Helper;
    public function send(Request $request)
    {
        $data = $request->all();
        $countryCode = $data['country_code'];
        $phoneNumber = $data['phone_number'];
        $fullPhone = $request->phone;

        // Initialize formatted phone number with original value
        $phoneNumberFormatted = $phoneNumber;

        // Remove leading zero if present
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumberFormatted = substr($phoneNumber, 1);
        }

        $fullPhoneNumber = $countryCode . $phoneNumberFormatted;

        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $phoneObject = $phoneUtil->parse($countryCode . $phoneNumberFormatted, null);

            if ($phoneUtil->isValidNumber($phoneObject)) {
                $otpInstance = new Otp();
                $otpData = $otpInstance->generate($fullPhoneNumber, 'numeric', 4, 5);
                $otp = $otpData->token;

                $message = "اهلا نورت/ي متجر فليكس💙\nرمز التحقق الخاص بك: $otp";
                $nerachatResponse = $this->nerachat($fullPhoneNumber, $message);

                Log::info('Nerachat response:', $nerachatResponse);

                if ($nerachatResponse['success'] ?? false) {
                    if ($request->wantsJson()) {
                        return response()->json([
                            'status'  => true,
                            'message' => 'OTP sent successfully.',
                        ]);
                    }
                }

                $errorMessage = $nerachatResponse['message'] ?? 'Failed to send OTP';

                if ($request->wantsJson()) {
                    return response()->json([
                        'status'  => false,
                        'message' => $errorMessage,
                    ]);
                }
            } else {
                \Log::info('Invalid phone number:', ['phone' => $fullPhoneNumber]);
                $errorMessage = 'Invalid phone number.';

                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => false,
                        'message' => $errorMessage,
                    ]);
                }
            }
        } catch (NumberParseException $e) {
            $errorMessage = 'Error parsing phone number: ' . $e->getMessage();

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $errorMessage,
                ], 422);
            }
        }
    }
    public function verifyOtp(Request $request)
    {

        $data = $request->all();
        $countryCode = $data['country_code'];
        $phoneNumber = $data['phone_number'];
        $fullPhone = $request->phone;

        // Initialize formatted phone number with original value
        $phoneNumberFormatted = $phoneNumber;

        // Remove leading zero if present
        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumberFormatted = substr($phoneNumber, 1);
        }

        $fullPhoneNumber = $countryCode . $phoneNumberFormatted;

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string',
            'otp'   => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }
        $otp = new Otp;
        $reversedOtp = strrev($request->otp);

        $status = $otp->validate($fullPhoneNumber, $reversedOtp);

        // \Log::info('OTP validation status:', ['status' => $status]);
        if ($status->status) {
            $user = \App\Models\User::where('mobile', $fullPhoneNumber)->first();
            if ($user) {
                auth()->login($user);
                $this->mergeGuestCartToUserCart($user->id);

                return response()->json(['status' => true, 'message' => 'OTP is valid.', 'user' => isset($user) ? true : false],);
            }
            return response()->json(['status' => true, 'message' => 'OTP is valid.', 'user' => isset($user) ? true : false]);
        }

        return response()->json(['status' => false, 'message' => $status->message], 400);
    }



    public function resendOtp(Request $request)
    {

        $data = $request->all();
        $countryCode = $data['country_code'];
        $phoneNumber = $data['phone_number'];

        // Initialize formatted phone number with original value
        $phoneNumberFormatted = $phoneNumber;

        // Remove leading zero if present

        if (substr($phoneNumber, 0, 1) === '0') {
            $phoneNumberFormatted = substr($phoneNumber, 1);
        }

        $fullPhoneNumber = $countryCode . $phoneNumberFormatted;
        $otp = new Otp;
        $otpData = $otp->generate($fullPhoneNumber, 'numeric', 4, 15);
        $otp = $otpData->token;

        $message = "اهلا نورت/ي متجر فليكس💙\nرمز التحقق الخاص بك: $otp";

        $result = $this->nerachat($fullPhoneNumber, $message);

        if ($result['success'] ?? false) {
            return response()->json(['status' => true]);
        }

        return response()->json([
            'status' => false,
            'message' => $result['message'] ?? 'حدث خطأ أثناء إرسال الرمز'
        ]);
    }


    public function mergeGuestCartToUserCart()
    {
        if (!Auth::check()) {
            // No logged-in user, no merge needed
            return;
        }

        $sessionToken = session()->get('cart_session_token');
        if (!$sessionToken) {
            // No guest cart session token, nothing to merge
            return;
        }

        $guestCart = Cart::where('session_token', $sessionToken)
            ->where('status', 'open')
            ->first();

        if (!$guestCart) {
            // No guest cart to merge
            return;
        }

        $userCart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'open'],
            ['status' => 'open']
        );

        foreach ($guestCart->items as $guestItem) {
            // Try to find matching item in user's cart
            $userCartItem = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('options', $guestItem->options) // adjust if serialized JSON, compare accordingly
                ->where('type', $guestItem->type)
                ->first();

            if ($userCartItem) {
                // Increase quantity if exists
                $userCartItem->quantity += $guestItem->quantity;
                $userCartItem->save();
            } else {
                // Move guest item to user cart
                $guestItem->cart_id = $userCart->id;
                $guestItem->save();
            }
        }

        // Delete guest cart and clear session token
        $guestCart->delete();
        session()->forget('cart_session_token');
    }
}
