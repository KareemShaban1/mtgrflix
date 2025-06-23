<?php

use App\Models\Cart;
use App\Models\Category;
use App\Livewire\CartPage;
use App\Livewire\HomePage;
use App\Livewire\CancelPage;
use App\Livewire\SuccesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductsPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\CategoriesPage;
use App\Livewire\DetailOrderPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\ProductDetailPage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\TestContoller;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Livewire\Auth\ResetPasswordPage;
use App\Http\Controllers\OrderController;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Payment2Controller;
use App\Http\Controllers\NewPaymentController;
use App\Http\Controllers\GoogleServiceController;
use App\Http\Controllers\DigitalContentController;
use Google\Service\Calendar\Setting;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use PHPUnit\Event\Code\Test;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(config('shrinkr.middleware'))
    ->get('/{shortenedUrl}', config('shrinkr.controller'))
    ->where('shortenedUrl', '^(?!ar$|en$)[a-zA-Z0-9]{6,}')
    ->name(config('shrinkr.route-name'))
    ->domain(config('shrinkr.domain'));

Route::get('phpinfo', fn() => phpinfo());
Route::get('/send/test-whatsapp', [TestContoller::class, 'test_code_msg']);
Route::get('/mtgr', [TestContoller::class, 'mtgr'])->name('mtgr');
Route::get('/namwbr', [CategoryController::class, 'flixMonth']);
Route::get('/qpvXbe', [CategoryController::class, 'flix2Month']);




Route::group(['prefix' => LaravelLocalization::setLocale()], function () {

    // Route::prefix('development')->group(function () {
    Route::get('/orders/invoice/{id?}', [DigitalContentController::class, 'downloadInvoice'])->name('invoice.download');

    Route::get('/', [WebsiteController::class, 'home'])->name('home');

    Route::get('/{categorySlug?}/{categoryId?}', [CategoryController::class, 'show'])
        ->where('categoryId', 'c[0-9]+')
        ->name('category');

    Route::get('/{productSlug?}/{productId?}', [CategoryController::class, 'showProduct'])
        ->where('productId', 'p[0-9]+')
        ->name('product');

    Route::get('/categories', [WebsiteController::class, 'categories']);
    Route::get('/products', [WebsiteController::class, 'products']);
    Route::get('/customers/testimonials', [ProfileController::class, 'testimonial'])->name('testimonial');

    //cart
    Route::get('/cart/manage', [CartController::class, 'cart'])->name('cart');
    Route::get('/add_to_cart/{product_id?}/{options?}', [CartController::class, 'addToCart'])->name('add_to_cart');
    Route::get('remove-from-cart/{id?}', [CartController::class, 'remove'])->name('remove.from.cart');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update-quantity');

    Route::get('/pages/{page}', [WebsiteController::class, 'page'])->name('page');

    Route::get('/products/{tag}/{id?}', [CategoryController::class, 'getByTag'])->name('category.byTag');

    Route::get('products/search', [WebsiteController::class, 'index'])->name('development.search');
    Route::get('search/products', [WebsiteController::class, 'searchProduct'])->name('search.product');
    Route::any('/order/checkout', [OrderController::class, 'handlePayment'])->name('checkout');

    Route::middleware('auth')->group(function () {
        Route::get('web/logout', [AuthController::class, 'logout'])->name('logout');

        // Route::get('/user/test/checkout', [OrderController::class, 'handlePayment'])->name('handle.payment');

        Route::get('/user/orders', [ProfileController::class, 'orders'])->name('my-orders');
        Route::get('/user/notification', [ProfileController::class, 'notification'])->name('my-notification');
        Route::get('/user/profile', [ProfileController::class, 'profile'])->name('my-profile');
        Route::get('/my-orders/{order_number}', [ProfileController::class, 'orderDetails'])->name('order.details');
        Route::get('/orders/digital-content/{id?}', [DigitalContentController::class, 'downloadDigitalContent'])->name('digital.content');
        Route::get('/orders/load-more', [ProfileController::class, 'loadMoreOrders'])->name('orders.loadMore');
        Route::put('/user/profile/update', [ProfileController::class, 'update'])->name('user.update');

        //order
        Route::post('/user/final-checkout', [OrderController::class, 'final_checkout'])->name('final-checkout');
        Route::get('/order/{orderId}/items', [ProfileController::class, 'getOrderItems'])->name('order.items');
        Route::any('/cart/checkout', [OrderController::class, 'submitCart'])->name('submit.cart');

        //payment
        Route::get('/payment/payment-failed', [PaymentController::class, 'failed'])->name('payment.failed');
        Route::get('/payment/payment-success', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/paymant/payment-callback', [PaymentController::class, 'callBack'])->name('payment.callback');

        Route::get('/payment/webhook', [NewPaymentController::class, 'webhook'])->name('payment.webhook');
    });

    Route::middleware('guest')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

        Route::get('/flix/login', function () {
            return view('website.auth.login');
        })->name('login');
        Route::get('/website-test/test', [TestContoller::class, 'test'])->name('test');
    });
    // });
});


// web.php

Route::prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/set-currency', [SettingController::class, 'setCurrency'])->name('set.currency');
    Route::get('/apply-coupon', [CartController::class, 'applyCouponAjax'])->name('apply.coupon');
    Route::post('/send-otp', [OtpController::class, 'send'])->name('send.otp');
    Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/resend-otp', [OtpController::class, 'resendOtp'])->name('resend.otp');
    Route::post('/submit-review/{orderId}', [CartController::class, 'submitReview']);
    Route::post('/submit-testimonial/{id?}', [CartController::class, 'storeTestimonial']);
    Route::get('/product/review/{id?}', [ProfileController::class, 'product_review'])->name('product.review');
});


Route::get('/product_reviews/{id}', [ProfileController::class, 'product_review'])
->name('productReviews');
//test
Route::get('/my-orders/payment', [NewPaymentController::class, 'initiatePaymentSession'])->name('order.review');
Route::get('/website-test/test', [TestContoller::class, 'test'])->name('test');
Route::get('/website-test/test-google', [GoogleServiceController::class, 'index'])->name('test');
Route::post('/save-previous-cart', function () {
    session()->put('has_saved_cart', true);
    return response()->json(['status' => 'saved']);
})->name('save.previous.cart');




Route::get('/clear-session', function () {
    session()->forget(['currency', 'rate', 'symbol']);

    session()->flush(); // clears all session data
    $response = Http::get('https://ipwho.is/');
    $data = $response->json();
    return $data;
    return session()->all();
    // return redirect()->back()->with('success', 'Session cleared successfully.');
})->name('session.clear');




// Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');

Route::post('/payment/checkout', [PaymentController::class, 'paymentProcess'])->name('payment.process');

// Route::get('language/{locale}', function ($locale) {
//     app()->setLocale($locale);
//     session()->put('locale', $locale);
//     return redirect()->back();
// })->name('language.switch');

Route::get('/salla/callback', [SettingController::class, 'callback'])->name('salla.callback');
Route::get('/salla/create-token', [SettingController::class, 'createToken'])->name('salla.callback');
