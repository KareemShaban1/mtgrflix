<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Country;
use App\Models\Currency;
use App\Http\Traits\Helper;
use App\Models\ProductCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Torann\GeoIP\Facades\GeoIP;
use App\Http\Services\SendMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Services\MyFatoorahPaymentService;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use CleaniqueCoders\Shrinkr\Facades\Shrinkr;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class TestContoller extends Controller
{
    use Helper;
    // Declare country codes properly outside any method
    protected $countryCodes = [
        '+1'   => 1,  // USA/Canada
        '+20'  => 2,  // Egypt
        '+966' => 3,  // Saudi Arabia
        '+971' => 3,  // UAE
        '+962' => 3,  // Jordan
        '+973' => 3,  // Bahrain
        '+974' => 3,  // Qatar
        '+965' => 3,  // Kuwait
        '+968' => 3,  // Oman
        '+212' => 3,  // Morocco
        '+213' => 3,  // Algeria
        '+216' => 3,  // Tunisia
        '+90'  => 2,  // Turkey
        '+33'  => 2,  // France
        '+44'  => 2,  // UK
        '+49'  => 2,  // Germany
        '+39'  => 2,  // Italy
        '+34'  => 2,  // Spain
        '+81'  => 2,  // Japan
        '+86'  => 2,  // China
        '+91'  => 2,  // India
    ];

    public function test()
    {

        return;
    }
    
    public function handle($code)
{
    // rerurn 'tes';
    $url = \CleaniqueCoders\Shrinkr\Models\Url::where('shortened_url', $code)->firstOrFail();
    return redirect($url->original_url);
}
    
   public function test_code_msg(SendMessage $msg)
    {
        
          $user = Auth::user();
           $slug = Shrinkr::shorten(
            LaravelLocalization::localizeUrl(route('cart')),
            $user
        );
        
        // $slug = Shrinkr::shorten(
        //     LaravelLocalization::localizeUrl(route('cart')),
        //     $user
        // );
        
        
  $fullUrl = 'https://' . config('shrinkr.domain') . '/' . $slug;


        $message = $msg->getTemplate('review_request', [
            '{{name}}' => 'flix',
            '{{review_url}}' =>  $fullUrl,
        ]);
        return   $this->nerachat('201032891025', $message);
        
        $url = route('mtgr');
                return   $this->nerachat('201032891025', $url);

        $username = 'flix';
        // $url = Shrinkr::shorten('https://mtgrflix.com/test', auth()->user());
// return $url;
        // $user = Auth::user();
        // $shortUrl = Shrinkr::shorten($url, $user);
        // // dd( $shortUrl); 
        $slug = Shrinkr::shorten($url, auth()->user());

        // // Construct the full URL
        $fullUrl = url($slug);
        
        return $fullUrl;
        //   $hiddenUrl = chunk_split($url, 3, "\u{200B}"); // Break every 3 chars with zero-width space
        //     $shortUrl = Shrinkr::shorten(
        //         LaravelLocalization::localizeUrl(route('cart')),
        //         $user
        //     );
        // return $fullUrl;
        // Outputs: https://yourdomain.com/s/abc123
        // Outputs: https://yourdomain.com/abc123
                // $url = route('order.details', $order->number);
                
                // // Android/iOS compatible:
                // $hiddenUrl = "https://" . 
                //              implode("\u{200B}", str_split('mtgrflix.com')) . 
                //              "/" . 
                //              ltrim(parse_url($url, PHP_URL_PATH), '/');
                
                // // WhatsApp message template:
                // $message = "Rate your order:\n" . $hiddenUrl . "\n\n[Click above]";

// return $message;

        // $url = LaravelLocalization::localizeUrl(route('order.details', 102));
          
                $localizedUrl = LaravelLocalization::localizeUrl(route('order.details', 1452));
                   
                    
                    // return $hiddenUrl;
                        // 2. Create short URL with Shrinkr
                                    $slug = Shrinkr::shorten($localizedUrl,auth()->user());
                    $shortUrl = "https://" . "go.mtgrflix.com" . "/{$slug}";
            return $shortUrl;
                // 3. Obfuscate to prevent previews (3 methods)
                // $hiddenUrl = str_replace('.', ".\u{200B}", $shortUrl); 
                
        
                $message = $msg->getTemplate('review_request', [
                    '{{name}}' => $username,
                    '{{review_url}}' =>  $localizedUrl,
                ]);
                return   $this->nerachat('201032891025', $message);
    }






    public function test_code_msg22(SendMessage $msg)
    {
      
$url = route('order.details',222);

// Create preview-blocked URL
$hiddenUrl = "https://" . 
             implode("\u{200B}", str_split('mtgrflix.com')) . 
             "/" . 
             ltrim(parse_url($url, PHP_URL_PATH), '/');

// Arabic message template with hidden URL
$message = <<<MSG
مرحبا عميلنا العزيز flix
💠[معك متجر فليكس]💠
•
نتمنى تقييم الطلب على موقعنا 🌐
يعنيلنا الكثير 🙏💙
عبر الرابط ⬇️

{$hiddenUrl}
•
🛑اذا قيمت الطلب اليوم رح يوصلك هدية كود خصم خاص فيك عشان تستخدمه بطلبك القادم وتشرفنا بخدمتك💙
MSG;

 
        
                // $message = $msg->getTemplate('review_request', [
                //     '{{name}}' => 'flix',
                //     '{{review_url}}' =>  $url,
                // ]);
                return   $this->nerachat('966548821517', $message);
    }
}
