<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\User;
use App\Models\Coupon;
use App\Http\Traits\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Http\Services\SendMessage;
use Illuminate\Support\Facades\Mail;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use CleaniqueCoders\Shrinkr\Facades\Shrinkr;

class SendAbandonedCartReminders extends Command
{
    use Helper;
    protected $signature = 'carts:send-abandoned-reminders';
    protected $description = 'Send emails to users with abandoned carts';

    public function handle()
    {
        $threshold = now()->subHours(2);
        $msg = new SendMessage();
        $abandonedCarts = Cart::with('user')
            ->where('status', 'open')
            ->whereNotNull('user_id')
            ->where('created_at', '<', $threshold)
            ->whereNull('reminder_sent_at')
            ->get();

        foreach ($abandonedCarts as $cart) {
            if (!$cart->user || !$cart->user->mobile) {
                continue;
            }

            // Create and attach coupon to user's cart
            $this->attachCouponToCart($cart);

            $url = LaravelLocalization::localizeUrl(route('cart'));
            $slug = Shrinkr::shorten( $url, $cart->user);
            // $shortUrl = url("{$slug}");
            $fullUrl = 'https://' . config('shrinkr.domain') . '/' . $slug;
            // $hiddenPreviewUrl = str_replace('.', ".\u{200B}", $url);
            $message = $msg->getTemplate('abandoned_cart', [
                '{{name}}' => $cart->user?->name,
                '{{checkout_url}}' => $fullUrl,
            ]);

            $this->nerachat($cart->user?->mobile, $message);

            $cart->reminder_sent_at = now();
            $cart->save();
        }
    }

    protected function attachCouponToCart(Cart $cart): void
    {
        // Create or get existing coupon for abandoned carts
        $randomCode = strtoupper(Str::random(6));

        // Create a new one-time-use coupon with this code
        $coupon = Coupon::create([
            'code' => $randomCode,
            'type' => 'percentage',
            'value' => 8,
            'valid_from' => now(),
            'valid_until' => now()->addHours(12),
            'max_uses' => 1,
            'max_uses_per_user' => 1,
            'min_purchase_amount' => null,
            'is_active' => true,
        ]);

        // Attach coupon to the cart
        $cart->coupon_id = $coupon->id;
        $cart->save();
    }
}
