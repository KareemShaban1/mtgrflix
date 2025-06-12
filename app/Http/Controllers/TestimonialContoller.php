<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Services\SendMessage;
use Illuminate\Support\Facades\Validator;

class TestimonialContoller extends Controller
{
    public function storeTestimonial(Request $request, SendMessage $sendMessage)
    {

        $request->validate([
            'rate' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        // Store the testimonial
        Testimonial::create([
            'rate' => $request->rate,
            'comment' => $request->comment,
            'user_id' => auth()->user()->id,
        ]);

        // Send a message about the testimonial

        // Determine the coupon and message based on the rating
        $message = '';
        $coupon = null;
        $discount = null;
        $validCoupons = Coupon::where('valid_from', '<=', Carbon::now()->subDay())
            ->where('valid_until', '>=', Carbon::now())
            ->first();


        if ($validCoupons) {
            // Set the coupon and discount
            $coupon = $validCoupons->code;
            $discount = $validCoupons->value;
        }
        if ($request->rate == 5 && $validCoupons) {
            $message = $sendMessage->getTemplate('5_star_review', ['{{code}}' => $validCoupons->code]);
            $this->nerachat(auth()->user()->mobile, $message);
        } elseif ($request->rate == 4 && $validCoupons) {
            $message = $sendMessage->getTemplate('4_star_review', ['{{code}}' => $validCoupons->code]);
            $this->nerachat(auth()->user()->mobile, $message);
        } elseif ($request->rate == 1) {
            $message = $sendMessage->getTemplate('1_star_review', []);
            $this->nerachat(auth()->user()->mobile, $message);
        }

        return response()->json([
            'success' => true,
            'coupon' => $coupon,
            'discount' => $discount,
        ]);
    }
}
