<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::truncate();
        $tags = [
            ['name' => ['en' => 'TikTok', 'ar' => 'تيك توك'], 'slug' => 'tiktok'],
            ['name' => ['en' => 'Instagram', 'ar' => 'انستقرام'], 'slug' => 'instagram'],
            ['name' => ['en' => 'Social Media', 'ar' => 'وسائل التواصل الاجتماعي'], 'slug' => 'social-media'],
            ['name' => ['en' => 'Streaming', 'ar' => 'بث مباشر'], 'slug' => 'streaming'],
            ['name' => ['en' => 'Netflix', 'ar' => 'نتفليكس'], 'slug' => 'netflix'],
            ['name' => ['en' => 'WatchIT', 'ar' => 'واتش إت'], 'slug' => 'watch-it'],
            ['name' => ['en' => 'Crunchyroll', 'ar' => 'كرانشي رول'], 'slug' => 'crunchyroll'],
        ];

        // Insert tags into the database
        foreach ($tags as $tag) {
            $tagModel = Tag::create([
                'name' => $tag['name'],
                'slug' => $tag['slug'],
            ]);
        }
    }
}
