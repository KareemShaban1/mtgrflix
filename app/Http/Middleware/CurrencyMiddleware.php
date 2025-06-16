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
        if ($request->has('currency')) {
            Log::info('Currency manually selected', ['currency' => $request->currency]);
            session()->forget('currency');
            $this->setCurrencySession($request->currency, $request->code);
        }

        if (!session()->has('currency')) {
            Log::info('Attempting currency auto-detection from IP');
            $this->detectCurrencyByIP();
        }

        view()->share('currentCurrency', session('currency'));
        return $next($request);
    }

    private function setCurrencySession(string $currencyCode, string $callingCode = '966', string $flag = '🇸🇦')
    {
        $currency = Currency::where('code', $currencyCode)->first();

        if ($currency) {
            session([
                'currency' => $currency->code,
                'rate'     => $currency->exchange_rate,
                'symbol'   => $currency->symbol,
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

        // Step 1: Try ipwhois.app
        try {
            $apiKey = config('services.ipwhois.key');
            $response = Http::timeout(10)->get("https://ipwhois.app/json/{$ip}?apikey={$apiKey}");
            $data = $response->json();
            Log::info('ipwhois.app response', $data);

            if (!empty($data['success']) && $data['success'] === false) {
                throw new \Exception($data['message'] ?? 'ipwhois.app failed');
            }

            $this->handleCurrencyDetection($data['calling_code'] ?? null, $data['flag']['emoji'] ?? '🇸🇦');
            return;

        } catch (\Exception $e) {
            Log::warning('ipwhois.app failed', ['error' => $e->getMessage()]);
        }

        // Step 2: Try ipwho.is
        try {
            $response = Http::timeout(10)->get("https://ipwho.is/{$ip}");
            $data = $response->json();
            Log::info('ipwho.is response', $data);

            if (!empty($data['success'])) {
                $this->handleCurrencyDetection($data['calling_code'] ?? null, $data['flag']['emoji'] ?? '🇸🇦');
                return;
            }

        } catch (\Exception $e) {
            Log::warning('ipwho.is failed', ['error' => $e->getMessage()]);
        }

        // Step 3: Try ipinfo.io
        try {
            $response = Http::timeout(10)->get("https://ipinfo.io/{$ip}/json");
            $data = $response->json();
            Log::info('ipinfo.io response', $data);

            $countryCode = $data['country'] ?? null;
            if ($countryCode) {
                $country = Country::where('alpha2', $countryCode)->orWhere('alpha3', $countryCode)->first();
                if ($country && $country->currency) {
                    $this->setCurrencySession($country->currency, $country->code, $country->flag ?? '🇸🇦');
                    return;
                }
            }

        } catch (\Exception $e) {
            Log::warning('ipinfo.io failed', ['error' => $e->getMessage()]);
        }

        // Fallback
        Log::info('Currency detection failed. Falling back to SAR.');
        $this->setCurrencySession('SAR', '966', '🇸🇦');
    }

    private function handleCurrencyDetection(?string $callingCode, string $flag)
    {
        if ($callingCode) {
            $country = Country::where('code', $callingCode)->first();
            if ($country && $country->currency) {
                $this->setCurrencySession($country->currency, $callingCode, $country->flag ?? $flag);
                return;
            }
        }

        Log::warning('Calling code not found or no matching country. Falling back to SAR.');
        $this->setCurrencySession('SAR', '966', $flag);
    }
}
