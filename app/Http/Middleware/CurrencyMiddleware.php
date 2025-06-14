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
    public function handle(Request $request, Closure $next)
    {

        session()->forget('currency');

        // Step 1: Manually selected currency
        if ($request->has('currency')) {
            Log::info('Currency manually selected', ['currency' => $request->currency]);
            // Clear previous session currency if needed
            // session()->forget('currency');
            $this->setCurrencySession($request->currency);
        }

        // Step 2: Auto-detect from IP if not already set
        if (!session()->has('currency')) {
            Log::info('Attempting currency auto-detection from IP');
            $this->detectCurrencyByIP();
        }

        // Step 3: Share current currency with views
        view()->share('currentCurrency', session('currency'));

        return $next($request);
    }

    private function setCurrencySession($currencyCode, $countryCode = '966', $flag = '🇸🇦')
    {
        $currency = Currency::where('code', $currencyCode)->first();

        if ($currency) {
            session([
                'currency' => $currency->code,
                'rate'     => $currency->exchange_rate,
                'symbol'   => $currency->symbol,
                'country'  => $countryCode,
                'flag'     => $flag,
            ]);
        } else {
            Log::warning("Currency code '{$currencyCode}' not found in database. Falling back to SAR.");
            // fallback session values
            session([
                'currency' => 'SAR',
                'rate'     => 1,
                'symbol'   => 'SAR',
                'country'  => $countryCode,
                'flag'     => $flag,
            ]);
        }
    }

    private function detectCurrencyByIP()
    {
        try {
            $ip = app()->environment('local') ? env('FAKE_IP', '197.121.246.12') : request()->ip();
            $response = Http::timeout(10)->get("https://ipwho.is/{$ip}");

            if ($response->ok()) {
                $data = $response->json();
                $callingCode = $data['calling_code'] ?? null;


                if ($callingCode) {
                    $country = Country::where('code', $callingCode)->first();

                    if ($country && $country->currency) {
                        $this->setCurrencySession($country->currency, $callingCode, $country->flag ?? '🇸🇦');
                        return;
                    }
                }
            }

            // If detection failed or no valid currency found
            Log::info('Currency detection failed or no valid country data found. Falling back to SAR.');
            $this->setCurrencySession('SAR', '966', '🇸🇦');
        } catch (\Exception $e) {
            Log::error('Currency detection failed', ['error' => $e->getMessage()]);
            $this->setCurrencySession('SAR', '966', '🇸🇦');
        }
    }
}
