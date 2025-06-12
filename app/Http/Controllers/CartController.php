<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Cart;
use App\Models\Order;
use Nette\Utils\Json;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Currency;
use App\Models\OrderItem;
use App\Http\Traits\Helper;
use App\Models\Testimonial;
use Illuminate\Support\Str;
use App\Models\ProductField;
use Illuminate\Http\Request;
use App\Models\OrderItemOption;
use App\Http\Services\SendMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Services\MyFatoorahPaymentService;

class CartController extends Controller
{

    use Helper;



    public function cart()
    {
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->where('status', 'open')->with(['items.product', 'coupon'])->first();
        } else {
            $sessionToken = session('cart_session_token');
            if ($sessionToken) {
                $cart = Cart::where('session_token', $sessionToken)->where('status', 'open')->with(['items.product', 'coupon'])->first();
            } else {
                $cart = null;
            }
        }

        // return $cart;
        return view('website.pages.cart', compact('cart'));
    }



    public function remove($itemId)
    {
        if ($itemId) {
            if (auth()->check()) {
                // Logged-in user
                $cart = \App\Models\Cart::where('user_id', auth()->id())->where('status', 'open')->first();
            } else {
                // Guest user by session token
                $sessionToken = session('cart_session_token');
                $cart = \App\Models\Cart::where('session_token', $sessionToken)->where('status', 'open')->first();
            }

            if ($cart) {
                $item = $cart->items()->where('id', $itemId)->first();

                if ($item) {
                    $item->delete();
                    session()->flash('success', __('site.product_removed_successfully'));
                } else {
                    session()->flash('error', __('site.item_not_found'));
                }
            } else {
                session()->flash('error', __('site.cart_not_found'));
            }
            \Session::forget('cart_data');
            return redirect()->back();
        }
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // Find the cart based on user authentication status
        if (auth()->check()) {
            // Logged-in user
            $cart = \App\Models\Cart::where('user_id', auth()->id())
                ->where('status', 'open')
                ->first();
        } else {
            // Guest user by session token
            $sessionToken = session('cart_session_token');
            $cart = \App\Models\Cart::where('session_token', $sessionToken)
                ->where('status', 'open')
                ->first();
        }

        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => __('site.cart_not_found')
            ], 404);
        }

        // Find the item within this cart
        $item = $cart->items()->where('id', $request->item_id)->first();

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => __('site.item_not_found')
            ], 404);
        }

        // Update the quantity
        $item->update(['quantity' => $request->quantity]);

        // Recalculate totals
        $cartTotal = $cart->getTotal();
        $itemTotal = $item->price * $item->quantity;

        return response()->json([
            'success' => true,
            'new_quantity' => $item->quantity,
            'item_total' => number_format($itemTotal, 2),
            'cart_total' => number_format($cartTotal, 2),
            'message' => __('site.quantity_updated_successfully')
        ]);
    }

    public function applyCouponAjax(Request $request)
    {
        $code = $request->get('code');
        $amount = floatval($request->get('amount'));

        return response()->json($this->applyCoupon($code, $amount));
    }



    public function addToCart($productId, $options = null)
    {
        $product = Product::findOrFail($productId);
        $extraOptionsTotal = 0;
        // ✅ Step 1: Calculate extra option prices
        if ($options && is_array($options)) {
            $formattedOptions = $options[0] ?? []; // flatten structure
            $extraOptionsTotal = $this->calculateExtraOptionsTotal($productId, $formattedOptions);
        }

        // ✅ Step 2: Get/create cart
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'status' => 'open'],
                ['status' => 'open']
            );
        } else {
            $sessionToken = session()->get('cart_session_token');
            if (!$sessionToken) {
                $sessionToken = (string) Str::uuid();
                session()->put('cart_session_token', $sessionToken);
            }

            $cart = Cart::firstOrCreate(
                ['session_token' => $sessionToken, 'status' => 'open'],
                ['status' => 'open']
            );
        }

        $finalPrice = $product->getEffectiveBasePrice() + $extraOptionsTotal;

        // ✅ Step 3: Add to cart with final price
        if ($product->type === 'service') {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'unique_key' => $productId . '_' . uniqid(),
                'quantity' => 1,
                'price' => $finalPrice,
                'options' => $formattedOptions ?? null,
                'type' => 'service',
            ]);
        } else {
            if (!$product->productCodes()->whereNull('used_at')->exists()) {
                return redirect()->back()->with('error', __('site.no_available_codes'));
            }

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $productId)
                ->where('type', 'product')
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $finalPrice,
                    'options' => $options,
                    'type' => 'product',
                ]);
            }
        }

        return redirect()->back()->with('success', __('site.added_to_cart'));
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back()->with('success', 'تم مسح السلة بنجاح');
    }


    public function submitReview($Id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }
        $validated = $request->all();

        $product = Product::findOrFail($validated['product']);
        $review = Review::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'order_id' => $Id,
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'],
            ]
        );
        return response()->json(['success' => true]);
    }


    public function storeTestimonial(Request $request, SendMessage $sendMessage)
    {
        $request->validate([
            'rate' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);


        $user = auth()->user();

        // Store the testimonial
        $existing = Testimonial::where([
            'user_id' => $user->id,
            'comment' => $request->comment,
            'rate' => $request->rate
        ])->exists();

        if (!$existing) {
            $testimonial = Testimonial::create([
                'rate' => $request->rate,
                'comment' => $request->comment,
                'user_id' => $user->id,
            ]);
        } else {
            $testimonial = $existing; // Assign existing testimonial for later use
        }

        $message = '';
        $coupon = null;
        $discount = null;

        $validCoupons = Coupon::where('valid_from', '<=', Carbon::now()->subDay())
            ->where('valid_until', '>=', Carbon::now())->where('code','thx')
            ->first();


        if ($validCoupons && $testimonial) {
            // Set the coupon and discount
            $coupon = $validCoupons;
        }
        if (!$validCoupons && $testimonial) {
            $coupon = $this->attachCoupon($testimonial);
        }


        if ($coupon) {
            $discount = $coupon->value;

            if ($request->rate == 5) {
                $message = $sendMessage->getTemplate('5_star_review', ['{{code}}' => $coupon->code]);
            } elseif ($request->rate == 4) {
                $message = $sendMessage->getTemplate('4_star_review', ['{{code}}' => $coupon->code]);
            }
        } elseif ($request->rate == 1) {
            $message = $sendMessage->getTemplate('1_star_review');
        }

        // Send message if prepared
        if ($message) {
            $this->nerachat(auth()->user()->mobile, $message);
        }

        return response()->json([
            'success' => true,
            'coupon' => $coupon?->code,
            'discount' => $discount,
        ]);
    }

    protected function attachCoupon(Testimonial $testimonial)
    {
        $randomCode = strtoupper(Str::random(6));

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

        $testimonial->coupon_id = $coupon->id;
        $testimonial->save();

        return $coupon;
    }
}
