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
    // Mapping of calling codes to ISO 3166-1 alpha-3 codes
     // Mapping of ISO 3166-1 alpha-3 country codes to currency codes
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
        // Manually selected
        if ($request->has('currency')) {
            Log::info('Currency manually selected', ['currency' => $request->currency]);
            session()->forget('currency');
            $this->setCurrencySession($request->currency, $request->code);
        }

        // Auto-detect
        if (!session()->has('currency')) {
            Log::info('Attempting currency auto-detection from IP');
            $this->detectCurrencyByIP();
        }

        // Share with views
        view()->share('currentCurrency', session('currency'));

        return $next($request);
    }

    private function setCurrencySession(string $currencyCode, string $callingCode = '966', string $flag = '🇸🇦')
    {
        $currency = Currency::where('code', $currencyCode)->first();
        $countryAlpha3 = $this->currencyToCountryMap[$currencyCode] ?? 'SAU';

        // dd($currencyCode, $callingCode ,$countryAlpha3);
        if ($currency) {
            session([
                'currency' => $currency->code,
                'rate'     => $currency->exchange_rate,
                'symbol'   => $currency->symbol,
                'country'  => $countryAlpha3,
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
        try {
            $ip = app()->environment('local') ? env('FAKE_IP', '197.121.246.12') : request()->ip();
            $response = Http::timeout(10)->get("https://ipwho.is/{$ip}?apiKey=kQW5BwkJcHhrG9mS");

            if ($response->ok()) {
                $data = $response->json();
                $callingCode = $data['calling_code'] ?? null;
                $flag = $data['flag']['emoji'] ?? '🇸🇦';

                Log::info('IP detection result:', ['calling_code' => $callingCode]);
                Log::info('ip data', [$data]);

                if ($callingCode) {
                    $country = Country::where('code', $callingCode)->first();
                    if ($country && $country->currency) {
                        $this->setCurrencySession($country->currency, $callingCode, $country->flag ?? $flag);
                        return;
                    }
                }
            }

            Log::info('Currency detection failed. Falling back to SAR.');
            $this->setCurrencySession('SAR', '966', '🇸🇦');
        } catch (\Exception $e) {
            Log::error('Currency detection exception', ['error' => $e->getMessage()]);
            $this->setCurrencySession('SAR', '966', '🇸🇦');
        }
    }
}
