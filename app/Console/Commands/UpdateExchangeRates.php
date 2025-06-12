<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Currency;

class UpdateExchangeRates extends Command
{
    protected $signature = 'currency:update-rates';
    protected $description = 'Update currency exchange rates using exchangeratesapi.io';

    public function handle()
    {
        // Access Key for the API
        $accessKey = 'ca11ed109dc6b83591845b3c554bfad7';
        
        // Base currency (SAR in this case)
        $base = 'SAR'; 
        
        // Get all currency codes except the base currency (SAR)
        $symbols = Currency::where('code', '!=', $base)->pluck('code')->implode(',');
    
        // URL for the apilayer.net live API
        $url = "http://apilayer.net/api/live";
    
        // Make a GET request to the API to get the rates
        $response = Http::get($url, [
            'access_key' => $accessKey,
            'currencies' => $symbols,
            'source'     => $base,
            'format'     => 1, // Optional parameter for returning data in JSON format
        ]);
    
        // Check if the response is successful
        if ($response->ok() && isset($response['quotes'])) {
            $rates = $response['quotes'];
    
            // Loop through each rate and update the corresponding currency in the database
            foreach ($rates as $code => $rate) {
                $currencyCode = substr($code, 3); // Extract the 3-letter currency code (e.g., "USDSAR" -> "SAR")
                
                Currency::where('code', $currencyCode)->update([
                    'exchange_rate' => round($rate, 2),
                ]);
            }
    
            // Output a success message
            $this->info('Currency rates updated successfully.');
        } else {
            // Output an error message if the request failed
            $this->error('Failed to update rates: ' . json_encode($response->json()));
        }
    }
    
}
