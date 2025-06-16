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
        // Middle East & Africa
        'AED' => 'ARE',
        'BHD' => 'BHR',
        'DZD' => 'DZA',
        'EGP' => 'EGY',
        'IQD' => 'IRQ',
        'JOD' => 'JOR',
        'KWD' => 'KWT',
        'LYD' => 'LBY',
        'MAD' => 'MAR',
        'OMR' => 'OMN',
        'QAR' => 'QAT',
        'SAR' => 'SAU',
        'SDG' => 'SDN',
        'TND' => 'TUN',
        'YER' => 'YEM',
        'ZAR' => 'ZAF',
    
        // Asia
        'CNY' => 'CHN',
        'INR' => 'IND',
        'JPY' => 'JPN',
        'MYR' => 'MYS',
        'PKR' => 'PAK',
        'THB' => 'THA',
        'TRY' => 'TUR',
        'IDR' => 'IDN',
        'BDT' => 'BGD',
        'KHR' => 'KHM',
        'MMK' => 'MMR',
        'NPR' => 'NPL',
        'PHP' => 'PHL',
        'LKR' => 'LKA',
        'VND' => 'VNM',
    
        // Europe
        'EUR' => 'EU',     // Generalized for Eurozone
        'GBP' => 'GBR',
        'CHF' => 'CHE',
        'SEK' => 'SWE',
        'NOK' => 'NOR',
        'DKK' => 'DNK',
        'RUB' => 'RUS',
        'HUF' => 'HUN',
        'PLN' => 'POL',
        'RON' => 'ROU',
        'UAH' => 'UKR',
    
        // Americas
        'USD' => 'USA',
        'CAD' => 'CAN',
        'BRL' => 'BRA',
        'MXN' => 'MEX',
        'ARS' => 'ARG',
        'CLP' => 'CHL',
        'COP' => 'COL',
        'PEN' => 'PER',
        'GTQ' => 'GTM',
    
        // Oceania
        'AUD' => 'AUS',
        'NZD' => 'NZL',
        'FJD' => 'FJI',
        'PGK' => 'PNG',
    
        // Africa (Extra)
        'ETB' => 'ETH',
        'GHS' => 'GHA',
        'KES' => 'KEN',
        'NGN' => 'NGA',
        'RWF' => 'RWA',
    
        // Special / Multi-country
        'XOF' => 'XOF', // West African CFA Franc — used in several countries
        'XPF' => 'XPF', // CFP Franc — used in French territories
    ];
    

    public function handle(Request $request, Closure $next)
    {
        dd(session()->all());
        if ($request->has('currency')) {
            Log::info('Currency manually selected', ['currency' => $request->currency]);
            session()->forget('currency');
            $this->setCurrencySession($request->currency, $request->code);
        }

        if (!session()->has('currency')) {
            Log::info('Attempting currency auto-detection from IP');
            $this->detectCurrencyByIP();
        }

        if (!session()->has('country') || !session()->has('calling_code')) {
            $this->setCurrencySession(session('currency'));
        }  
        

        view()->share('currentCurrency', session('currency'));
        return $next($request);
    }

    private function setCurrencySession(string $currencyCode, string $callingCode = '966', string $flag = '🇸🇦')
    {
        $currency = Currency::where('code', $currencyCode)->first();
        $countryCodeMapiing = $this->currencyToCountryMap[$currencyCode] ?? 'SAU';
        $country = Country::where('currency',$currencyCode)->first();


        // dd($callingCode);
        if ($currency) {
            session([
                'currency' => $currency->code,
                'rate'     => $currency->exchange_rate,
                'symbol'   => $currency->symbol,
                'country'  => $countryCodeMapiing,
                'flag'     => $country->flag,
                'calling_code' => $country->code,
            ]);
        } else {
            Log::warning("Currency '{$currencyCode}' not found. Using fallback SAR.");
            session([
                'currency' => 'SAR',
                'rate'     => 1,
                'symbol'   => 'SAR',
                'country'  => 'SAU',
                'flag'     => $country->flag,
                'calling_code' => $country->code,
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
