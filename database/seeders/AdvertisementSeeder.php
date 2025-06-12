<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Advertisement::create([
            'text' => '🚀 عرض خاص! خصومات تصل إلى 50% على جميع المنتجات - لا تفوت الفرصة! 🎉',
            'color' => 'white',
            'background' => 'gray',
        ]);
    }
}
