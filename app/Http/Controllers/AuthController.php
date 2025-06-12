<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // \Log::info($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string',
                'last_name'  => 'required|string',
                'email'      => 'required|email',
                'phone'      => 'required|string|unique:users',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
    
            $phoneNumberFormatted = $request->phone;
    
            // Remove leading zero if present
            if (substr($request->phone, 0, 1) === '0') {
                $phoneNumberFormatted = substr($request->phone, 1);
            }
    
            $fullPhoneNumber = $request->country_code . $phoneNumberFormatted;
    
            $user = User::create([
                'first_name'   => $request->first_name,
                'last_name'    => $request->last_name,
                'email'        => $request->email,
                'phone'        => $phoneNumberFormatted,
                'mobile'       => $fullPhoneNumber,
                'name'         => $request->first_name . ' ' . $request->last_name,
                'password'     => \Hash::make($request->password ?? '12345678'),
                'country_code' => $request->country_code,
            ]);
    
            auth()->login($user); // login user
    
            $hasOrder = false;
    
            if ($user) {
                $sessionToken = session()->get('cart_session_token');
                if ($sessionToken) {
                    $cart = \App\Models\Cart::where('session_token', $sessionToken)
                        ->where('status', 'open')
                        ->first();
    
                    if ($cart) {
                        $cart->update(['user_id' => $user->id]);
                        $hasOrder = session()->get('has_saved_cart');
                        session()->forget('has_saved_cart');
                    }
                }
            }
    
            return response()->json([
                'status' => true,
                'message' => 'تم التسجيل بنجاح',
                'order' => $hasOrder ? true : false
            ]);
    
        } catch (\Throwable $e) {
            \Log::error('Register error: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء التسجيل: ' . $e->getMessage(),
            ], 500);
        }
    }
    


    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        // return $request->all();
        // Attempt to authenticate the user
        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {

            return redirect()->intended('dashboard')

                ->withSuccess('You have Successfully loggedin');
        } else {
            // Authentication failed, redirect back with error message
            return redirect()->back()->with('error', __('Invalid credentials, please try again.'));
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('home')->with('success', __('You have logged out successfully.'));
    }
}
