<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use UserSeeder;
use App\Models\Page;
use Database\Seeders\UserSeeder as SeedersUserSeeder;
use GPBMetadata\Google\Ads\GoogleAds\V17\Resources\Ad;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // MainCategorySeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            SeedersUserSeeder::class,
            CountrySeeder::class,
            CurrencySeeder::class,
            TagSeeder::class,
            StatusSeeder::class,
            PageSeeder::class,
            WhatsAppTemplateSeeder::class,
            AdvertisementSeeder::class,
        ]);
    }
}
