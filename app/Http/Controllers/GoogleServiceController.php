<?php

namespace App\Http\Controllers;

use Google\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Google\Service\ShoppingContent;
use App\Services\GoogleMerchantService;

class GoogleServiceController extends Controller
{
    public function index()
    {
        // try {
        //     $client = new Client();
        //     $client->setAuthConfig(storage_path('app/google-service-account.json'));
        //     $client->addScope(ShoppingContent::CONTENT);

        //     $service = new ShoppingContent($client);

        //     // Test API by fetching merchant account info
        //     $merchantInfo = $service->accounts->get('5586377906', '5586377906');
        //     dd($merchantInfo); // Should return merchant details
        // } catch (\Exception $e) {
        //     dd("API Error: " . $e->getMessage());
        // }



        $products = Product::where('is_active', true)->get();
        $merchantService = new GoogleMerchantService();
    
        foreach ($products as $product) {
            try {
                $merchantService->insertProduct([
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description_en,
                    'url' => route('product', ['productSlug' => $product->slug, 'productId' => $product->identifier]),
                    'image' =>asset ('storage/' . $product->images),
                    'price' => $product->price,
                  
                ]);
                return true;
                $this->info("Synced: {$product->name}");
            } catch (\Exception $e) {
                return $e->getMessage();
                \Log::error($e->getMessage());
                // $this->error("Failed {$product->name}: ".$e->getMessage());
            }
        }
    }
}
