<?php

namespace App\Http\Middleware;

use App\Models\Country;
use App\Models\Currency;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyMiddleware
{

    private array $currencyToCountryMap = [
        'SAR' => 'SAU',
        'EGP' => 'EGY',
        'AED' => 'ARE',
        'QAR' => 'QAT',
        'USD' => 'USA',
        'GBP' => 'GBR',
        'KWD' => 'KWT',
        'JOD' => 'JOR',
        'SYP' => 'SYR',
        'LBP' => 'LBN',
        'TND' => 'TUN',
        'MAD' => 'MAR',
        'DZD' => 'DZA',
        'SDG' => 'SDN',
        'BHD' => 'BHR',
        'IQD' => 'IRQ',
        'YER' => 'YEM',
        // Add more mappings if needed
    ];


    public function handle(Request $request, Closure $next)
    {
        if ($request->has('currency')) {
            Log::info('Currency manually selected', ['currency' => $request->currency]);
            session()->forget('currency');
            $this->setCurrencySession($request->currency, $request->code);
        }

        if (!session()->has('currency')) {
            Log::info('Attempting currency auto-detection from IP');
            $this->detectCurrencyByIP();
        }

        if (!session()->has('country')) {
            $this->setCurrencySession(session('currency'));
        }  
        

        view()->share('currentCurrency', session('currency'));
        return $next($request);
    }

    private function setCurrencySession(string $currencyCode, string $callingCode = '966', string $flag = '🇸🇦')
    {
        $currency = Currency::where('code', $currencyCode)->first();
        $country = $this->currencyToCountryMap[$currencyCode] ?? 'SAU';


        if ($currency) {
            session([
                'currency' => $currency->code,
                'rate'     => $currency->exchange_rate,
                'symbol'   => $currency->symbol,
                'country'  => $country,
                'flag'     => $flag,
            ]);
        } else {
            Log::warning("Currency '{$currencyCode}' not found. Using fallback SAR.");
            session([
                'currency' => 'SAR',
                'rate'     => 1,
                'symbol'   => 'SAR',
                'country'  => 'SAU',
                'flag'     => $flag,
            ]);
        }
    }

    private function detectCurrencyByIP()
    {
        $ip = app()->environment('local') ? env('FAKE_IP', '197.121.246.12') : request()->ip();
        Log::info('Client IP detected', ['ip' => $ip]);
    
        if (
            $this->tryIpWhois($ip) ||
            $this->tryIpWho($ip) ||
            $this->tryIpApiCo($ip) ||
            $this->tryIpInfo($ip)
        ) {
            return;
        }
    
        // All failed, fallback
        Log::info('All IP services failed. Falling back to SAR.');
        $this->setCurrencySession('SAR', '966', '🇸🇦');
    }
    
    private function tryIpWhois($ip): bool
    {
        try {
            $apiKey = config('services.ipwhois.key');
            $response = Http::timeout(10)->get("https://ipwhois.pro/{$ip}?key={$apiKey}");
            $data = $response->json();
            Log::info('ipwhois.app response', $data);
    
            if (!empty($data['success']) && $data['success'] === false) {
                throw new \Exception($data['message'] ?? 'ipwhois.app failed');
            }
    
            return $this->handleCurrencyDetection($data['calling_code'] ?? null, $data['flag']['emoji'] ?? '🇸🇦');
        } catch (\Exception $e) {
            Log::warning('ipwhois.app failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    private function tryIpWho($ip): bool
    {
        try {
            $response = Http::timeout(10)->get("https://ipwho.is/{$ip}");
            $data = $response->json();
            Log::info('ipwho.is response', $data);
    
            if (!empty($data['success'])) {
                return $this->handleCurrencyDetection($data['calling_code'] ?? null, $data['flag']['emoji'] ?? '🇸🇦');
            }
            return false;
        } catch (\Exception $e) {
            Log::warning('ipwho.is failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    private function tryIpApiCo($ip): bool
    {
        try {
            $response = Http::timeout(10)->get("https://ipapi.co/{$ip}/json/");
            $data = $response->json();
            Log::info('ipapi.co response', $data);
    
            if (!isset($data['error'])) {
                return $this->handleCurrencyDetection($data['country_calling_code'] ?? null, '🇸🇦');
            }
            return false;
        } catch (\Exception $e) {
            Log::warning('ipapi.co failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    private function tryIpInfo($ip): bool
    {
        try {
            $response = Http::timeout(10)->get("https://ipinfo.io/{$ip}/json");
            $data = $response->json();
            Log::info('ipinfo.io response', $data);
    
            $countryCode = $data['country'] ?? null;
            if ($countryCode) {
                $country = Country::where('alpha2', $countryCode)->orWhere('alpha3', $countryCode)->first();
                if ($country && $country->currency) {
                    $this->setCurrencySession($country->currency, $country->code, $country->flag ?? '🇸🇦');
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            Log::warning('ipinfo.io failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    private function handleCurrencyDetection(?string $callingCode, string $flag): bool
    {
        if ($callingCode) {
            $normalizedCode = preg_replace('/[^0-9]/', '', $callingCode);
            $country = Country::where('code', $normalizedCode)->first();
            if ($country && $country->currency) {
                $this->setCurrencySession($country->currency, $normalizedCode, $country->flag ?? $flag);
                return true;
            }
        }
    
        Log::warning('Calling code not found or no matching country.');
        return false;
    }
    
}
