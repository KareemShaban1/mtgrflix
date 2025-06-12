<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\MyFatoorahPaymentService;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
    
        try {
            $request->validate([
                'first_name'    => 'required|string|max:255',
                'last_name'     => 'required|string|max:255',
                'email'         => 'required|email',
                'phone'         => 'required',
                'country_code'  => 'required|string|max:5',
                'gender'        => 'nullable|in:male,female',
                'birthday'      => 'nullable|date',
            ]);
    
            

            
    
            // Check if phone exists for another user
            if (\App\Models\User::where('phone', $request->phone)->where('id', '!=', $user->id)->exists()) {
                return back()->withErrors(['phone' => 'This phone number is already in use.'])->withInput();
            }
    
            $data = $request->only([
                'first_name', 'last_name', 'email', 'phone', 'country_code', 'gender', 'birthday'
            ]);
    
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image && \Storage::exists($user->image)) {
                    \Storage::delete($user->image);
                }
    
                $data['image'] = $request->file('image')->store('users', 'public');
            }
    
            $user->update($data);
    
            return back()->with('success', __('Profile updated successfully.'));
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
            return back()->with('error', __('Something went wrong. Please try again later.'));
        }
    }
    

    public function notification()
    {

    
        $notifications = auth()->user()->notifications()->latest()->paginate(5);
        // return $notifications;
        if (request()->ajax()) {
            $view = view('website.partials.notification', compact('notifications'))->render();
            return response()->json(['html' => $view]);
        }
    
       
        return view('website.pages.notification',compact('notifications'));
    }

    public function orders(Request $request)
    {
        $orders = auth()->user()->orders()->latest()->paginate(5);
        
        if ($request->ajax()) {
            $response = [];
            
            // Include HTML based on request type
            if ($request->get('type') === 'mobile') {
                $response['mobile_html'] = view('website.partials.mobile-orders', compact('orders'))->render();
            } else {
                $response['html'] = view('website.partials.orders', compact('orders'))->render();
            }
            
            return response()->json($response);
        }
        
        return view('website.pages.orders', compact('orders'));
    }
    public function orderDetails($num)
    {
        $order = Order::with(['items.product'])
        // ->where('user_id', auth()->id())
        ->where('number', $num)->firstOrFail();
        return view('website.pages.order_details', compact('order'));
    }


    public function profile()
    {
        return view('website.pages.user_details');
    }


    public function loadMoreOrders(Request $request)
    {
        $orders = auth()->user()->orders()->where('status_id', '!=', '1')->latest()->paginate(5); 
        if ($request->ajax()) {
            $view = view('website.partials.mobile-orders', compact('orders'))->render();
            return response()->json(['html' => $view]);
        }
        return view('website.pages.orders', compact('orders'));
    }


    public function testPayment(MyFatoorahPaymentService $paymentGateway)
    {

        $order = Order::find(1);
        
        return  $paymentGateway->make_payment(1);

        return 'test';
    }


    public function testimonial(Request $request)
    {
        $sort = $request->get('sort', 'newest');
    
        $reviews = \App\Models\Review::with('user')->where('approved', 1);
    
        // Apply sorting based on query param
        switch ($sort) {
            case 'oldest':
                $reviews->orderBy('created_at', 'asc');
                break;
            case 'highest_rating':
                $reviews->orderBy('rating', 'desc');
                break;
            case 'lowest_rating':
                $reviews->orderBy('rating', 'asc');
                break;
            case 'newest':
            default:
                $reviews->orderBy('created_at', 'desc');
                break;
        }
    
        $reviews = $reviews->paginate(5);
    
        if ($request->ajax()) {
            $view = view('website.partials.all_reviews', compact('reviews'))->render();
            return response()->json(['html' => $view]);
        }
    
        return view('website.pages.testimonials', compact('reviews', 'sort'));
    }
    public function product_review(Request $request, $id)
    {
    
        $reviews = \App\Models\Review::with('user')->where('product_id', $id)->where('approved', 1)->latest();
    
      
    
        $reviews = $reviews->paginate(5);
    
        if ($request->ajax()) {
            $view = view('website.partials.product_reviews', compact('reviews'))->render();
            return response()->json(['html' => $view]);
        }
    
        return view('website.pages.testimonials', compact('reviews'));
    }

    public function getOrderItems($orderId)
{
    $order = Order::findOrFail($orderId);

    $items = $order->items->map(function ($item) {
        return [
            'id' => $item->id,
            'name' => $item->product->name,
            'image' => asset('storage/' . $item->product->images),
        ];
    });

    return response()->json(['items' => $items]);
}

    
}
