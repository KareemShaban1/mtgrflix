<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Http\Traits\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\BasePaymentService;
use Illuminate\Support\Facades\Http;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Storage;
use App\Interfaces\PaymentGatewayInterface;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MyFatoorahPaymentService extends BasePaymentService implements PaymentGatewayInterface
{
    use Helper;
    /**
     * Create a new class instance.
     */
    protected $api_key;
    public function __construct()
    {
        $this->base_url = env("MYFATOORAH_BASE_URL");
        $this->api_key = env("MYFATOORAH_API_KEY");
        $this->header = [
            'accept' => 'application/json',
            "Content-Type" => "application/json",
            "Authorization" => "Bearer " . $this->api_key,
        ];
    }

    public function sendPayment(Request $request): array
    {
        $data = $request->all();
        $data['NotificationOption'] = "LNK";
        $data['Language'] = "en";
        $data['CallBackUrl'] = $request->getSchemeAndHttpHost() . '/api/payment/callback';
        $response = $this->buildRequest('POST', '/v2/SendPayment', $data);
        //handel payment response data and return it
        if ($response->getData(true)['success']) {
            return ['success' => true, 'url' => $response->getData(true)['data']['Data']['InvoiceURL']];
        }
        return ['success' => false, 'url' => route('payment.failed')];
    }




    public function make_payment($orderId, $sessionId)
    {
        $validGulfCurrencies = ['KWD', 'SAR', 'BHD', 'AED', 'QAR', 'OMR', 'JOD', 'EGP'];

        $order = Order::with(['user', 'currency'])->find($orderId);

        if (!$order) {
            \Log::info('Order not found');
            return ['success' => false, 'message' => 'Order not found'];
        }

        $user = $order->user;
        $currency = strtoupper($order->currency?->code);
        $exchangeRate = $order->exchange_rate;
        $originalTotal = $order->grand_total;
        $newTotal = in_array($currency, $validGulfCurrencies) ? $originalTotal * $exchangeRate : $originalTotal;
        $displayCurrency = in_array($currency, $validGulfCurrencies) ? $currency : 'SAR';
        \Log::info('Currency Conversion Info', [
            'user_id' => $user->id ?? null,
            'currency' => $currency,
            'exchange_rate' => $exchangeRate,
            'original_total' => $originalTotal,
            'new_total' => $newTotal,
            'display_currency' => $displayCurrency,
        ]);
        $url = env('MYFATOORAH_BASE_URL') . 'ExecutePayment';
        $token = 'Bearer ' . env('MYFATOORAH_API_KEY');

        try {
            $response = Http::withHeaders(['Authorization' => $token])->post($url, [
                "SessionId" => $sessionId,
                "InvoiceValue" => $newTotal,
                "DisplayCurrencyIso" => $displayCurrency,
                "ProcessingDetails" => ["Bypass3DS" => false],
                "CustomerName" => $user->name ?? 'Guest',
                "CustomerMobile" => $user->phone ?? '96857425888',
                "CustomerEmail" => $user->email ?? 'user@flix.com',
                "CallBackUrl" => route('payment.callback'),
                "ErrorUrl" => route('payment.failed'),
                "WebhookUrl" => route('payment.webhook'),
            ])->json();

            if (!isset($response['IsSuccess']) || !$response['IsSuccess']) {
                \Log::info('MyFatoorah payment failed', ['response' => $response]);
                return ['success' => false, 'message' => $response['ValidationErrors'][0]['Error'] ?? 'Unknown error'];
            }

            $paymentURL = $response['Data']['PaymentURL'];
            \Log::info('MyFatoorah payment response', ['response' => $response['Data']]);
            try {
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'total' => $originalTotal,
                    'sub_total' => $order->sub_total,
                    'currency_id' => $order->currency_id,
                    'discount' => $order->discount,
                    'paymentMethod' => 'my-fatoorah',
                    'invoice_id' => $response['Data']['InvoiceId'],
                    'transaction_ref' => null,
                    'status' => 'pending',
                ]);
            } catch (\Exception $e) {
                \Log::error('Payment creation failed: ' . $e->getMessage());
                return ['success' => false, 'message' => 'DB Error: ' . $e->getMessage()];
            }

            \Log::info('MyFatoorah payment success', ['payment' => $payment->toArray()]);
            return ['success' => true, 'url' => $paymentURL];
        } catch (\Exception $e) {
            \Log::error('MyFatoorah API exception: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function callback($request)
    {
        \Log::info('MyFatoorah callback', ['request' => $request->all()]);
        // TODO: replace test url and test token with live url and token
        $url = env('MYFATOORAH_BASE_URL') . 'GetPaymentStatus';
        $token = 'Bearer ' . env('MYFATOORAH_API_KEY');
        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post($url, [
                'Key' => $request['paymentId'],
                'KeyType' => 'PaymentId'
            ]);
            $response = $response->json();
            \Log::info('MyFatoorah callback response', ['response' => $response]);
            
            if ($response['IsSuccess']) {
                if ($response['Data']['InvoiceStatus'] === 'Paid') {

                    $payment = Payment::where('invoice_id', $response['Data']['InvoiceId'])->first();
                    $paymentMethod = $response['Data']['InvoiceTransactions'][0]['PaymentGateway'] ?? 'unknown';
                    $TotalServiceCharge = $response['Data']['InvoiceTransactions'][0]['TotalServiceCharge'] ?? 0;

                    if ($payment) {
                        $payment->update([
                            'transaction_ref' => $response['Data']['InvoiceReference'],
                            'status' => 'success',
                            'response_message' => 'Payment done successfully',
                            'data' => ($response['Data']),
                            'paymentMethod' => $paymentMethod,
                            'tax'=>$TotalServiceCharge
                        ]);

                        $this->handleCallback($payment, $paymentMethod);

                        if ($payment && $payment->order) {
                            return redirect()->route('digital.content', $payment->order->id);
                        }
                    }
                } else {
                    $payment = Payment::where('invoice_id', $response['Data']['InvoiceId'])->first();

                    if ($payment) {
                        $payment->update([
                            'transaction_ref' => $response['Data']['InvoiceReference'],
                            'status' => 'failed',
                            'response_message' => $response['ValidationErrors'][0]['Error'] ?? 'Payment failed',
                            'data' => json_encode($response),
                        ]);

                        $payment->order?->update([
                            'status_id' => 5,
                            'payment_status' => 'failed',
                        ]);
                    }
                    Log::info('Payment failed');
                    return view('payments.fail');
                }
                Log::info('Payment done successfully');
                return response()->json(['success' => true, 'payment' => $payment]);
            } else {

                Log::info('Payment failed');
                return view('payments.fail');
            }
        } catch (\Exception $e) {
            Log::error('Callback error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);
            return redirect()->route('payment.failed');
        }
    }


    public function handleCallback($payment, $paymentMethod)
    {
        try {
            DB::beginTransaction();
            $order = $payment->order;
            $user = $order->user;
            $qr = $this->qr_code($order, $payment);
            $order->update([
                'payment_status' => 'paid',
                'status_id' => 3,
                'qr_code' => $qr,
                'payment_method' => $paymentMethod,
            ]);

            foreach ($order->items as $item) {
                $product = $item->product;

                // Check if the product is digital
                if (!empty($product) && $product->type == 'digital') {

                    $order->update([
                        'status_id' => 3,
                    ]);

                    // Handle multiple quantities for the same product
                    $quantity = $item->quantity; // Get the quantity of the product
                    $codes = $product->codes()->whereNull('used_at')->take($quantity)->get();

                    // Check if we have enough available codes for the quantity
                    if ($codes->count() == $quantity) {
                        foreach ($codes as $index => $code) {
                            // Assign a code to the item and mark it as used
                            $item->update([
                                'product_code_id' => $code->id,
                            ]);

                            $code->update([
                                'used_at' => now(),
                                'user_id' => $order->user_id,
                            ]);

                            \Log::info('Payment code sent successfully for code ID: ' . $code->id);

                            $this->sendPaymentCodeMessage($user, $order, $product, $code);
                        }

                        // Notify the user
                        $user->notify(new UserNotification($order->id, 'تم تنفيذ طلبك بنجاح'));
                    } else {
                        \Log::error('Not enough available codes for product', ['order_id' => $order->id, 'product_id' => $product->id]);
                        DB::rollBack();
                        return response()->json(['error' => 'Not enough available codes for the digital product.'], 400);
                    }
                }
            }

            DB::commit();

            return response()->json(['message' => 'Payment processed successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment callback failed: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['error' => 'Payment processing failed.'], 500);
        }
    }

    public function qr_code($order)
    {
        $url = route('invoice.download', $order->id);


        $qrImage = QrCode::format('png')->size(300)->generate($url);

        // Save to storage
        $filename = 'qrcodes/order_' . $order->id . '_' . time() . '.png';
        Storage::disk('public')->put($filename, $qrImage);

        // Update the order with QR path
        return $filename;

        // \Log::info('QR Code generated and saved', ['path' => $filename]);
    }


    public function handle_error($request)
    {
        $url = env('MYFATOORAH_BASE_URL') . 'GetPaymentStatus';
        $token = 'Bearer ' . env('MYFATOORAH_API_KEY');

        try {
            $response = Http::withHeaders([
                'Authorization' => $token
            ])->post($url, [
                'Key' => $request['paymentId'],
                'KeyType' => 'PaymentId'
            ])->json();

            \Log::info('MyFatoorah payment status response', ['response' => $response]);

            if (!isset($response['IsSuccess']) || !$response['IsSuccess']) {
                \Log::warning('MyFatoorah response unsuccessful.');
                return view('payments.fail');
            }

            $invoiceId = $response['Data']['InvoiceId'] ?? null;

            if (!$invoiceId) {
                \Log::error('Missing InvoiceId in MyFatoorah response.');
                return view('payments.fail');
            }

            $payment = Payment::where('invoice_id', $invoiceId)->first();

            if ($payment) {
                $payment->update([
                    'transaction_ref' => $response['Data']['InvoiceReference'] ?? null,
                    'status' => 'failed',
                    'response_message' => $response['ValidationErrors'][0]['Error'] ?? 'Payment failed',
                    'data' => json_encode($response)['Data'] ?? null,
                ]);

                $payment->order?->update([
                    'status_id' => 5,
                    'payment_status' => 'failed',
                ]);

                \Log::info("Payment marked as failed for invoice: $invoiceId");
            } else {
                \Log::warning("Payment not found for invoice: $invoiceId");
            }

            return view('payments.fail');
        } catch (\Exception $e) {
            \Log::error('handle_error() Exception', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return view('payments.fail');
        }
    }

    public function sendPaymentCodeMessage($user, $order, $product, $code)
    {
        // Convert HTML to plain text
        $html = $code->code;
        $plainText = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $html));
        $plainText = html_entity_decode($plainText);

        $productName = $product->name ?? '';
        $msg = "عميلنا اللزيز {$order->user->name}💠\n"
            . "جايين نسلمك طلبك رقم {$order->number} 🤩\n"
            . " {$productName} 📦\n\n"
            . $plainText;

        \Log::info('Payment code sent successfully', ['mobile' => $user->mobile, 'message' => $msg]);

        // Call your existing nerachat method to send the message
        $this->nerachat($user->mobile, $msg);
    }
}
