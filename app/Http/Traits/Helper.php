<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\ProductField;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


trait Helper

{



    public function applyCoupon(string $code, float $orderAmount): array
    {
        $coupon = Coupon::where('code', $code)->first();
        $rate = session('rate', 1);
        if (!$coupon) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => __('site.Coupon not found.')
            ];
        }

        $now = Carbon::now();

        if ($coupon->valid_from && $coupon->valid_from > $now) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => __('site.Coupon is not active yet.')
            ];
        }

        if ($coupon->valid_until && $coupon->valid_until < $now) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => __('site.Coupon has expired.')
            ];
        }

        if ($coupon->min_purchase_amount && $orderAmount < $coupon->min_purchase_amount) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => __('site.Order amount is less than minimum required.')
            ];
        }

        if ($coupon->max_uses !== null) {
            $usageCount = Order::where('coupon_id', $coupon->id)
                ->where('status_id', 3)
                ->where('payment_status', 'paid')
                ->count();
            if ($usageCount >= $coupon->max_uses) {
                return [
                    'valid' => false,
                    'discount' => 0,
                    'message' => __('site.This coupon has reached its maximum usage limit')
                ];
            }
        }
        $uses = Order::where('user_id', auth()->id())
            ->where('coupon_id', $coupon->id)
            ->where('payment_status', 'paid')
            ->count();

        // Check if the coupon has a per-user usage limit and if it's been exceeded
        if ($coupon->max_uses_per_user !== null && $uses >= $coupon->max_uses_per_user) {
            return [
                'valid' => false,
                'discount' => 0,
                'message' => __('site.You_have_reached_the_usage_limit_for_this_coupon'),
            ];
        }
        $discount = 0;

        if ($coupon->type === 'percentage') {
            $discount = ($coupon->value / 100) * $orderAmount;
        } elseif ($coupon->type === 'fixed') {
            $discount = $coupon->value * $rate;
        }


        $discount = min($discount, $orderAmount);

        return [
            'valid' => true,
            'discount' => $discount,
            'message' => null
        ];
    }



    public function nerachat(string $receiver = '966500000000', string $message = 'Welcome to Flix :)')
    {

        if (substr($receiver, 0, 1) === '0') {
            $phoneNumberFormatted = substr($receiver, 1);
        }
        $payload = [
            'appkey'  => env('NERACHAT_APP_KEY'),
            'authkey' => env('NERACHAT_AUTH_KEY'),
            'to'      => $receiver,
            'message' => $message,
            'sandbox' => 'false',
        ];

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->post('https://www.nerachat.com/api/create-message', $payload);

            if ($response->successful()) {
                // You can also return as a JSON response if used in a controller
                return [
                    'success' => true,
                    'data'    => $response->json(),
                ];
            }

            // Handle non-2xx responses
            Log::warning('Nerachat API responded with error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'API error: ' . $response->status(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            // Handle connection timeouts or exceptions
            Log::error('Nerachat API exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Request failed: ' . $e->getMessage(),
            ];
        }
    }


    public function calculateExtraOptionsTotal($productId, array $options): float
    {
        $productFields = ProductField::where('product_id', $productId)->get()->keyBy('input_type');

        $optionLookups = [];
        foreach ($productFields as $type => $field) {
            $optionLookups[$type] = collect($field->options)->keyBy('key');
        }

        $total = 0;
        foreach ($options as $type => $values) {
            if (!isset($optionLookups[$type])) continue;

            if ($type === 'select') {
                foreach ($values as $key) {
                    $total += floatval($optionLookups[$type][$key]['value'] ?? 0);
                }
            }
        }

        return $total;
    }
}
