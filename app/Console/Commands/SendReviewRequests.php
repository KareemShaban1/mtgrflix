<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Review;
use App\Http\Traits\Helper;
use Illuminate\Console\Command;
use App\Http\Services\SendMessage;
use Illuminate\Support\Facades\Mail;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use CleaniqueCoders\Shrinkr\Facades\Shrinkr;

class SendReviewRequests extends Command
{
    use Helper;
    protected $signature = 'orders:send-review-requests';
    protected $description = 'Send review request emails 1 hour after order is placed';

    public function handle()
    {
        $orders = Order::where('created_at', '<=', now()->now()->subDay())->where('payment_status', 'paid')->where('status_id', 3)->whereNull('review_requested_at')

            ->get();

        $msg = new SendMessage();
        foreach ($orders as $order) {
            // Skip if review already exists
            if (Review::where('order_id', $order->id)->exists()) {
                continue;
            }

              $localizedUrl = LaravelLocalization::localizeUrl(route('order.details', $order->number));

                $slug = Shrinkr::shorten($localizedUrl, $order->user);
                // $shortUrl = url("{$slug}");
                $fullUrl = 'https://' . config('shrinkr.domain') . '/' . $slug;


            
            
            $message = $msg->getTemplate('review_request', [
                '{{name}}' => $order->user?->name,
                '{{review_url}}' => $fullUrl,
            ]);
            
            
            $this->nerachat($order->user?->mobile, $message);

            $order->update([
                'review_requested_at' => now(),
            ]);
        }
    }
}
