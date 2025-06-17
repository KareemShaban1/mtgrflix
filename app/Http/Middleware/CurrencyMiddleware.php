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
        // Step 1: Check if user manually selected a currency
        if ($request->has('currency')) {
            $this->setCurrencySession($request->currency);
        }

        // Step 2: If currency not set, auto-detect from IP or fallback to default
        if (!session()->has('currency')) {
            $this->detectCurrencyByIP();
        }

        // Step 3: Make currency available to all views
        view()->share('currentCurrency', session('currency'));

        return $next($request);
    }

    private function setCurrencySession($currencyCode, $countryCode = null, $flag = null)
    {
        $currency = Currency::where('code', $currencyCode)->first();

        // Log::info('currency' , [$currencyCode , $currency]);

        if ($currency) {
            session([
                'currency' => $currency->code ?? 'SAR',
                'rate'     => $currency->exchange_rate ?? 1,
                'symbol'   => $currency->symbol ?? 'SAR',
                'country'  => $countryCode ?? '966',
                'flag'     => $flag ?? '🇸🇦',
            ]);
        } else {
            session([
                'currency' => $currency->code ?? 'SAR',
                'rate'     => $currency->exchange_rate ?? 1,
                'symbol'   => $currency->symbol ?? 'SAR',
                'country'  => $countryCode ?? '966',
                'flag'     => $flag ?? '🇸🇦'
            ]);
            Log::warning("Currency code '{$currencyCode}' not found in database.");
        }
        // Log::info('session' , session()->all());
    }

    private function detectCurrencyByIP()
    {

        try {
            $ip = request()->ip(); // Use '127.0.0.1' for local testing
            // $response = Http::timeout(3)->get("https://ipwho.is/{$ip}");
            $apiKey = config('services.ipwhois.key');
            $response = Http::timeout(10)->get("https://ipwhois.pro/{$ip}?key={$apiKey}");
            Log::info('response' , [$response->json()]);
            if ($response->ok()) {
                $data = $response->json();

                if (!empty($data['calling_code'])) {
                    $callingCode = $data['calling_code'];

                    $country = Country::where('code', $callingCode)->first();

                    if ($country && $country->currency) {
                        $this->setCurrencySession($country->currency, $callingCode, $country->flag);
                        return;
                    } else {
                        $this->setCurrencySession('SAR', '966', '🇸🇦');
                        return;
                    }
                }
            }

            // Fallback to SAR if detection fails
            $this->setCurrencySession('SAR', '966');
        } catch (\Exception $e) {
            Log::error('Currency detection failed: ' . $e->getMessage());
            $this->setCurrencySession('SAR', '966', '🇸🇦');
        }
    }
}
