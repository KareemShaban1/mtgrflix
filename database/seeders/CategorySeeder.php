<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
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
                'parent_id' => null,
            ],
            [
                'identifier' => 'c16551885013',
                'name' => ['en' => 'social media ', 'ar' => 'سوشيال ميديا'],
                'slug' => '-social-media',
                "is_active" => 1,
                'image' => 'main-categories/23.webp',
                'parent_id' => null,
            ],
            [
                'identifier' => 'c1602480839',
                'name' => ['en' => 'Increase likes', 'ar' => 'زيادة لايكات'],
                'slug' => 'زيادة-لايكات',
                "is_active" => 1,
                'parent_id' => 2,
            ],
            [
                'identifier' => 'c1861885013',
                'name' => ['en' => 'Increase views', 'ar' => 'زيادة مشاهدات'],
                'slug' => 'زيادة-مشاهدات',
                "is_active" => 0,
                'parent_id' => 2,

            ],
            [
                'identifier' => 'c1442625887',
                'name' => ['en' => 'Crunchy roll', 'ar' => 'كرانشي رول'],
                'slug' => 'كرانشي-رول',
                "is_active" => 0,
                'parent_id' => 1,

            ],
            [
                'identifier' => 'c9281534',
                'name' => ['en' => 'watch it', 'ar' => 'watch it'],
                'slug' => 'watch-it',
                "is_active" => 0,
                'parent_id' => 1,

            ],
            [
                'identifier' => 'c1906612214',
                'name' => ['en' => 'amazon prime video', 'ar' => 'amazon prime video'],
                'slug' => 'amazon-prime-video',
                "is_active" => 0,
                'parent_id' => 1,

            ],
            [
                'identifier' => 'c1549929705',
                'name' => ['en' => 'osn plus', 'ar' => 'osn plus'],
                'slug' => 'osn-plus',
                "is_active" => 0,
                'parent_id' => 1,

            ],
            [
                'identifier' => 'c42331624',
                'name' => ['en' => 'Watch vip', 'ar' => 'Watch vip'],
                'slug' => 'watch-vip',
                "is_active" => 0,
                'parent_id' => 1,
            ],
            [
                'identifier' => 'c1883964982',
                'name' => ['en' => 'NETFLIX', 'ar' => 'NETFLIX'],
                'slug' => 'netflix',
                "is_active" => 1,
                'parent_id' => 1,
            ],
            [
                'identifier' => 'c1717036389',
                'name' => ['en' => 'Increase followers', 'ar' => 'زيادة متابعين'],
                'slug' => 'زيادة-متابعين',
                "is_active" => 0,
                'parent_id' => 2,
            ],
            [
                'identifier' => 'c1717036378',
                'name' => ['en' => ' PlayStation Store', 'ar' => 'بلايستيشن ستور سعودي'],
                'slug' => 'بلايستيشن-ستور-سعودي',
                "is_active" => 1,
                'parent_id' => 1,
            ],
        ];


        foreach ($categories as $category) {
            Category::create([
                'identifier' => $category['identifier'],
                'name' => $category['name'],
                'slug' => $category['slug'],
                'is_active' => $category['is_active'],
                'parent_id' => $category['parent_id'],
            ]);
        }
    }
    /**
     * Reverse the migrations.
     */
}
