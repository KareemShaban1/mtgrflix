<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MainCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Category::truncate();
        $categories = [
            [
                'identifier' => 'c16025430839',
                'name' => ['en' => 'subscription', 'ar' => ' اشتراكات'],
                'slug' => '-اشتراكات',
                "is_active" => 1,
                'image' => 'main-categories/7.webp',
            ],
            [
                'identifier' => 'c16551885013',
                'name' => ['en' => 'social media ', 'ar' => 'سوشيال ميديا'],
                'slug' => '-social-media',
                "is_active" => 1,
                'image' => 'main-categories/23.webp',
            ]
        ];
        foreach ($categories as $category) {
            Category::create([
                'identifier' => $category['identifier'],
                'name' => $category['name'],
                'slug' => $category['slug'],
                'is_active' => $category['is_active'],
                'image' => $category['image'],
            ]);
        }
    }
}
