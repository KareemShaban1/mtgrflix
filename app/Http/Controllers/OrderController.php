<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Currency;
use App\Models\OrderItem;
use App\Http\Traits\Helper;
use App\Models\ProductField;
use Illuminate\Http\Request;
use App\Models\OrderItemOption;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\MyFatoorahPaymentService;
use Illuminate\Database\Eloquent\Casts\Json;

class OrderController extends Controller
{

    use Helper;
    protected MyFatoorahPaymentService $paymentGateway;

    public function __construct(MyFatoorahPaymentService $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function handlePayment(Request $request)
    {
        // \Log::info($request->all());
        if ($request->product_options) {
            foreach ($request->product_options as $productId => $options) {
                // dd($options);
                if (empty($options['select']) || !is_array($options['select'])) {
                    return back()->with('error', __(' Please select at least one option for product '));
                }
                if (empty($options['text'])) {
                    return back()->with('error', __(' Please fill all inputs '));
                }
            }
        }
        $cart = $request->input('cart');

        $productId = $request->input('product_id');
        $action = $request->input('action');
        $options = $request->input("product_options", []);
        $optionIds = array_values($options);

        // dd($optionIds, $options);

        $finalPrice = 0; // Default value in case product_id is missing

        if ($productId) {

            $product = Product::find($productId);
            if ($product->type === 'digital') {
                if (!$product->productCodes()->whereNull('used_at')->exists()) {
                    return redirect()->back()->with('error', __('site.no_available_codes'));
                }
            }

            $extraOptionsTotal = 0;

            if ($options && is_array($options)) {
                $formattedOptions = $optionIds[0] ?? [];
                $extraOptionsTotal = $this->calculateExtraOptionsTotal($productId, $formattedOptions);
            }

            $basePrice = $product->getEffectiveBasePrice();
            $finalPrice = $basePrice + $extraOptionsTotal;
        }

        session([
            'checkout_data' => [
                'product_id' => $request->input('product_id'),
                'product_options' => $request->input('product_options', []),
                'total' => $finalPrice,
            ]
        ]);

        if ($action === 'add_to_cart') {

            $cart = new CartController();
            return $cart->addToCart($productId, $optionIds);
        } 
        else {

            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'You must be logged in to continue.');
            }

            $payment = new  NewPaymentController();
            $paymentSession = $payment->initiatePaymentSession();

            return view('website.pages.pay', [
                'sessionId' => $paymentSession['sessionId'],
                // 'countryCode' => $paymentSession['CountryCode'],
                'countryCode' => session('country'),
                'total' => session('checkout_data.total'),
                'productId' => $productId,
                'options' => $options,
                'cart' => $cart,
                'currencyCode' => session('currency')


            ]);
        }
    }





    public function submitCart(Request $request)
    {

        // dd($request->all());
        $product_options = $request->input('product_options', []);

        foreach ($product_options as $cartItemId => $products) {
            $cartItem = CartItem::with('product')->find($cartItemId);
            if (!$cartItem) continue;

            foreach ($products as $productId => $options) {
                // Save options
                $cartItem->options = $options;

                // Calculate extra price
                $extraOptionsTotal = $this->calculateExtraOptionsTotal($productId, $options);

                // Update price = base + extras
                $basePrice = $cartItem->product->getEffectiveBasePrice() ?? 0;
                $cartItem->price = $basePrice + $extraOptionsTotal;

                $cartItem->save();
            }
        }

        $cart = Cart::where('user_id', auth()->id())->where('status', 'open')->with('items.product')->first();
        $cartTotal = $cart ? $cart->items->sum(fn($item) => $item->price * $item->quantity) : 0;



        $payment = new NewPaymentController();
        $paymentSession = $payment->initiatePaymentSession();

        return view('website.pages.pay', [
            'sessionId' => $paymentSession['sessionId'],
            'countryCode' => $paymentSession['CountryCode'],
            // 'countryCode' => session('country'),
            'cart' => $request->input('cart'),
            'total' => $cartTotal,
            'currencyCode' => session('currency'),
            'code' => $request->input('coupon_code'),
        ]);
    }


    public function submit(Request $request)
    {

        // dd(Session::all());
        DB::beginTransaction();

        try {
            $subtotal = 0;
            $discountAmount = 0;

            // Pull from session
            $productId = session('checkout_data.product_id');
            $options = session("checkout_data.product_options.{$productId}", []);
            $couponCode = $request->code;
            $sessionId = $request->session_id;

            if (!$productId) {
                return response()->json(['success' => false, 'message' => 'Invalid session data.'], 400);
            }

            $product = Product::findOrFail($productId);
            $price = $product->getEffectiveBasePrice();
            $quantity = 1;

            $currency = Currency::where('code', session('currency'))->first();
            $couponId = Coupon::where('code', $couponCode)->first()?->id;

            // Create Order
            $order = Order::create([
                'user_id' => auth()->id() ?? 1,
                'grand_total' => 0,
                'sub_total' => 0,
                'discount' => 0,
                'payment_method' => 'myfatoorah',
                'status_id' => 1,
                'currency_id' => $currency->id ?? 18,
                'currency_code' => $currency->code,
                'exchange_rate' => $currency->exchange_rate,
                'notes' => '',
                'coupon_id' => $couponId,
            ]);

            // Create Order Item
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_amount' => $price,
                'total_amount' => 0, // to be updated after option calculation
                'options' => json_encode($options),
            ]);

            $extraOptionsTotal = 0;

            // Process Options
            if (!empty($options)) {
                foreach ($options as $type => $fields) {
                    $productField = ProductField::where('product_id', $productId)
                        ->where('input_type', $type)
                        ->first();

                    if (!$productField) {
                        continue;
                    }

                    $productFieldOptions = $productField->options;

                    if ($type === 'text') {
                        OrderItemOption::create([
                            'order_item_id' => $orderItem->id,
                            'type' => 'text',
                            'field_name' => $fields,
                            'key' => null,
                            'value' => $fields,
                            'product_field_id' => $productField->id
                        ]);
                    } elseif ($type === 'select') {
                        foreach ($fields as $optionKey) {
                            $matchedOption = collect($productFieldOptions)->firstWhere('key', $optionKey);
                            $value = floatval($matchedOption['value'] ?? 0);
                            $extraOptionsTotal += $value;

                            OrderItemOption::create([
                                'order_item_id' => $orderItem->id,
                                'type' => 'select',
                                'key' => $optionKey,
                                'value' => $value,
                                'product_field_id' => $productField->id
                            ]);
                        }
                    }
                }
            }

            // Calculate totals
            $itemTotal = ($price + $extraOptionsTotal) * $quantity;

            $orderItem->update([
                'total_amount' => $itemTotal,
            ]);

            $subtotal += $itemTotal;
            //
            $grandTotal = $subtotal;

            if ($request->filled('code')) {
                $discount = $this->applyCoupon($request->code, $subtotal);
                // \Log::info($discount);
                if ($discount['valid'] === true) {
                    $discountAmount = $discount['discount'];
                    $grandTotal -= $discountAmount;
                }
            }

            $orderNumber = 190 + $order->id;

            $order->update([
                'sub_total' => $subtotal,
                'discount' => $discountAmount,
                'grand_total' => $grandTotal,
                'number' => $orderNumber,
            ]);

            //


            // Payment Gateway
            $paymentGateway = new MyFatoorahPaymentService();
            $result = $paymentGateway->make_payment($order->id, $sessionId);

            if (!isset($result['url'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error during checkout',
                ]);
            }

            // Forget checkout session on success
            session()->forget('checkout_data');

            // Track referral
            if (Session::has('ref_id')) {
                $refId = Session::get('ref_id');
                $referral = Referral::where('ref_id', $refId)->first();

                if ($referral) {
                    $referral->increment('purchases_count');
                    $referral->increment('total_sales', $order->grand_total); // Adjust to match your order field
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'url' => $result['url']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error during final checkout: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء إتمام الطلب: ' . $e->getMessage());
        }
    }


    public function final_checkout(Request $request)
    {
        if ($request->has('cart')) {
            return $this->submit_all_cart($request);
        }
        return $this->submit($request);
    }

    public function submit_all_cart(Request $request)
    {
        // \Log::info($request->all());
        // dd($request->all());
        $cart = null;
        if (auth()->check()) {
            $cart = Cart::where('user_id', auth()->id())->where('status', 'open')->with('items')->first();
        } else {
            $sessionToken = session('cart_session_token');
            $cart = $sessionToken
                ? Cart::where('session_token', $sessionToken)->where('status', 'open')->with('items')->first()
                : null;
        }

        if (empty($cart) || $cart->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',

            ]);
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;
            $discountAmount = 0;

            $currency = Currency::where('code', session('currency'))->first() ?? 1;
            // return $currencyId;
            $couponId = Coupon::where('code', $request->code)->first()?->id;

            $order = Order::create([
                'user_id' => auth()->id() ?? 1,
                'grand_total' => 0,
                'sub_total' => 0,
                'discount' => 0,
                'payment_method' => $request->payment_type,
                'status_id' => 1,
                'currency_id' => $currency->id,
                'currency_code' => $currency->code,
                'exchange_rate' => $currency->exchange_rate,
                'coupon_id' => $couponId ?? null
            ]);

            foreach ($cart->items  as $item) {
                // \Log::info($item);

                $cartItemId = $item->id;
                $productId = $item->product_id;
                $quantity = $item->quantity;
                $product = Product::find($productId);
                $price = $product->getEffectiveBasePrice();
                $options = $item->options;
                $extraOptionsTotal = 0;
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_amount' => $price,
                    'total_amount' => 0,
                    'options' => Json::encode($options),
                ]);
                if ($options) {
                    foreach ($options as $type => $fields) {
                        $productField = ProductField::where('product_id', $productId)
                            ->where('input_type', $type)
                            ->first();

                        if (!$productField) continue;

                        $productFieldOptions = $productField->options; // array of ['key' => ..., 'value' => ...]

                        if ($type === 'text') {
                            OrderItemOption::create([
                                'order_item_id' => $orderItem->id,
                                'type' => 'text',
                                'field_name' => $fields,
                                'key' => null,
                                'value' => $fields,
                                'product_field_id' => $productField->id
                            ]);
                        } elseif ($type === 'select') {
                            foreach ($fields as $optionKey) {
                                $matchedOption = collect($productFieldOptions)->firstWhere('key', $optionKey);
                                $value = floatval($matchedOption['value'] ?? 0);
                                $extraOptionsTotal += $value;

                                OrderItemOption::create([
                                    'order_item_id' => $orderItem->id,
                                    'type' => 'select',
                                    'key' => $optionKey,
                                    'value' => $value,
                                    'product_field_id' => $productField->id
                                ]);
                            }
                        }
                    }
                }
                $itemTotal = ($price + $extraOptionsTotal) * $quantity;

                $orderItem->update(['total_amount' => $itemTotal]);
                $subtotal += $itemTotal;
            }

            $grandTotal = $subtotal;

            if ($request->filled('code')) {
                $discount = $this->applyCoupon($request->code, $subtotal);
                // \Log::info($discount);
                if ($discount['valid'] === true) {
                    $discountAmount = $discount['discount'];
                    $grandTotal -= $discountAmount;
                }
            }

            $orderNumber = 190 + $order->id;

            $order->update([
                'sub_total' => $subtotal,
                'discount' => $discountAmount,
                'grand_total' => $grandTotal,
                'number' => $orderNumber,
            ]);
            $paymentGateway = new MyFatoorahPaymentService();
            $result = $paymentGateway->make_payment($order->id, $request->session_id);

            if (!isset($result['url'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error during checkout',
                ]);
            }
            $cart->update(['status' => 'completed']);

            DB::commit();

            // \Log::info($result);
            return response()->json([
                'success' => true,
                'url' => $result['url']
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error during checkout', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),

            ]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
    public function add_order()
    {
        return view('website.pages.success');
    }

    public function add_order_items($request, $cart)
    {
        $subtotal = 0;
        $discountAmount = 0;
        $currency = Currency::where('code', session('currency'))->first() ?? 1;
        // return $currencyId;
        $couponId = Coupon::where('code', $request->code)->first()?->id;

        $order = Order::create([
            'user_id' => auth()->id() ?? 1,
            'grand_total' => 0,
            'sub_total' => 0,
            'discount' => 0,
            'payment_method' => $request->payment_type,
            'status_id' => 1,
            'currency_id' => $currency->id,
            'currency_code' => $currency->code,
            'exchange_rate' => $currency->exchange_rate,
            'coupon_id' => $couponId ?? null,
        ]);

        foreach ($cart->items  as $item) {
            // \Log::info($item);

            $cartItemId = $item->id;
            $productId = $item->product_id;
            $quantity = $item->quantity;
            $product = Product::find($productId);
            $price = $product->getEffectiveBasePrice();
            $options = $item->options;
            $extraOptionsTotal = 0;
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_amount' => $price,
                'total_amount' => 0,
                'options' => Json::encode($options),
            ]);
            if ($options) {
                foreach ($options as $type => $fields) {
                    $productField = ProductField::where('product_id', $productId)
                        ->where('input_type', $type)
                        ->first();

                    if (!$productField) continue;

                    $productFieldOptions = $productField->options; // array of ['key' => ..., 'value' => ...]

                    if ($type === 'text') {
                        OrderItemOption::create([
                            'order_item_id' => $orderItem->id,
                            'type' => 'text',
                            'field_name' => $fields,
                            'key' => null,
                            'value' => $fields,
                            'product_field_id' => $productField->id
                        ]);
                    } elseif ($type === 'select') {
                        foreach ($fields as $optionKey) {
                            $matchedOption = collect($productFieldOptions)->firstWhere('key', $optionKey);
                            $value = floatval($matchedOption['value'] ?? 0);
                            $extraOptionsTotal += $value;

                            OrderItemOption::create([
                                'order_item_id' => $orderItem->id,
                                'type' => 'select',
                                'key' => $optionKey,
                                'value' => $value,
                                'product_field_id' => $productField->id
                            ]);
                        }
                    }
                }
            }
            $itemTotal = ($price + $extraOptionsTotal) * $quantity;

            $orderItem->update(['total_amount' => $itemTotal]);
            $subtotal += $itemTotal;
        }

        $grandTotal = $subtotal;

        if ($request->filled('code')) {
            $discount = $this->applyCoupon($request->code, $subtotal);
            // \Log::info($discount);
            if ($discount['valid'] === true) {
                $discountAmount = $discount['discount'];
                $grandTotal -= $discountAmount;
            }
        }

        $orderNumber = 190 + $order->id;

        $order->update([
            'sub_total' => $subtotal,
            'discount' => $discountAmount,
            'grand_total' => $grandTotal,
            'number' => $orderNumber,
        ]);
    }
}
