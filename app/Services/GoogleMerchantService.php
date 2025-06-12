<?php

namespace App\Services;

use Google\Client;
use Google\Service\ShoppingContent;

class GoogleMerchantService
{
    protected $service;

    public function __construct()
    {
        $client = new Client();
        $client->setAuthConfig(storage_path('app/google-service-account.json'));
        $client->addScope(ShoppingContent::CONTENT);
        $this->service = new ShoppingContent($client);
    }

    public function insertProduct(array $productData)
    {
        $product = new \Google\Service\ShoppingContent\Product([
            'offerId' => $productData['id'],
            'title' => $productData['name'],
            'description' => $productData['description'],
            'link' => $productData['url'],
            'imageLink' => $productData['image'],
            // 'contentLanguage' => 'en',
            'targetCountry' => 'US',
            'availability' => 'in stock',
            'channel' => 'online', // REQUIRED FIELD
            'contentLanguage' => 'en', // Also recommended
            'price' => [
                'value' => $productData['price'],
                'currency' => 'USD'
            ]
        ]);

        return $this->service->products->insert(
            '5586377906',
            $product
        );
    }
}