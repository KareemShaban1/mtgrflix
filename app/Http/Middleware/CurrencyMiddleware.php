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

        // Try primary IPWhois API
        try {
            $apiKey = config('services.ipwhois.key');
            $response = Http::timeout(10)->get("https://ipwhois.app/json/{$ip}?apikey={$apiKey}");

            $data = $response->json();
            Log::info('IPWhois API response', $data);

            if (!empty($data['success']) && $data['success'] === false) {
                throw new \Exception($data['message'] ?? 'IPWhois API failed');
            }

            $callingCode = $data['calling_code'] ?? null;
            $flag = $data['flag']['emoji'] ?? '🇸🇦';

            if ($callingCode) {
                $country = Country::where('code', $callingCode)->first();
                if ($country && $country->currency) {
                    $this->setCurrencySession($country->currency, $callingCode, $country->flag ?? $flag);
                    return;
                }
            }

        } catch (\Exception $e) {
            Log::warning('IPWhois API failed, trying fallback', ['error' => $e->getMessage()]);
        }

        // Fallback to ipwho.is (free, no API key)
        try {
            $fallbackResponse = Http::timeout(10)->get("https://ipwho.is/{$ip}");
            $fallbackData = $fallbackResponse->json();
            Log::info('Fallback ipwho.is response', $fallbackData);

            if (!empty($fallbackData['success'])) {
                $callingCode = $fallbackData['calling_code'] ?? null;
                $flag = $fallbackData['flag']['emoji'] ?? '🇸🇦';

                if ($callingCode) {
                    $country = Country::where('code', $callingCode)->first();
                    if ($country && $country->currency) {
                        $this->setCurrencySession($country->currency, $callingCode, $country->flag ?? $flag);
                        return;
                    }
                }
            } else {
                Log::warning('ipwho.is fallback failed', ['data' => $fallbackData]);
            }

        } catch (\Exception $ex) {
            Log::error('ipwho.is fallback exception', ['error' => $ex->getMessage()]);
        }

        // Final fallback
        Log::info('Currency detection failed. Falling back to SAR.');
        $this->setCurrencySession('SAR', '966', '🇸🇦');
    }
}
