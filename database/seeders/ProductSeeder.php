<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Product::truncate();
        // Insert product data
        $products = [
            [
                'identifier' => 'p348520274',
                'name' => [
                    'en' => 'TikTok Likes',
                    'ar' => 'زيادة لايكات تيك توك'
                ],
                'sub_title' => null,
                'type' => 'service',
                'slug' => 'زيادة-لايكات-تيك-توك',
                'images' => 'products/01JSVPTG8JMAZSMDZXKTPTZER0.webp',
                'tags' => [],
                'sku' => null,
                'description_ar' => null,
                'description_en' => null,
                'price' => 1.00,
                'promotional_price' => 1.00,
                'is_active' => 1,
                'is_featured' => 0,
                'in_stock' => 1,
                'on_sale' => 0,
                'category_id' => 3,
                'brand_id' => null,
                'product_fields' => [
                    [
                        'name' => [
                            'ar' => ' ادخل رابط ',
                            'en' => 'enter link'
                        ],
                        'input_type' => 'text',
                        'required' => 1,
                        'options' => null,
                        'multiple' => 0,
                        'order' => 0
                    ],
                    [
                        'name' => [
                            'ar' => 'اختر عدد المشاهدات',
                            'en' => 'choose'
                        ],
                        'input_type' => 'select',
                        'required' => 1,
                        'options' => [
                            [
                                'key' => '10k',
                                'value' => '5'
                            ],
                            [
                                'key' => '20k',
                                'value' => '10'
                            ],
                            [
                                'key' => '30k',
                                'value' => '20'
                            ],
                            [
                                'key' => '50k',
                                'value' => '30'
                            ],
                            [
                                'key' => '100k',
                                'value' => '50'
                            ],
                            [
                                'key' => '200k',
                                'value' => '100'
                            ],
                            [
                                'key' => '500k',
                                'value' => '200'
                            ],
                            [
                                'key' => '1M',
                                'value' => '500'
                            ],
                        ],
                        'multiple' => 1,
                        'order' => 0
                    ]
                ],
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'identifier' => 'p1701160253',
                'name' => [
                    'en' => 'Instagram Likes',
                    'ar' => 'زيادة لايكات انستقرام'
                ],
                'price' => 1.00,
                'promotional_price' => 1.00,
                'slug' => 'زيادة-لايكات-انستقرام',
                'category_id' => 3, // زيادة لايكات
                'images' => 'products/01JSVPTG8JMAZSMDZXKTPTZER0.webp',
                'description_ar' => null,
                'description_en' => null,
                
            ],
            [
                'identifier' => 'p347185942',
                'name' => [
                    'en' => 'TikTok Views',
                    'ar' => 'زيادة مشاهدات تيك توك'
                ],
                'price' => 1.00,
                'promotional_price' => 1.00,
                'slug' => 'زيادة-مشاهدات-تيك-توك',
                'category_id' => 4, // زيادة مشاهدات
            ],
            [
                'identifier' => 'p2078631665',
                'name' => [
                    'en' => 'Instagram Views',
                    'ar' => 'زيادة مشاهدات انستقرام'
                ],
                'price' => 1.00,
                'promotional_price' => 1.00,
                'slug' => 'زيادة-مشاهدات-انستقرام',
                'category_id' => 4, // زيادة مشاهدات
            ],
            [
                'identifier' => 'p1946380331',
                'name' => [
                    'en' => 'TikTok Followers',
                    'ar' => 'زيادة متابعين تيك توك'
                ],
                'price' => 1.00,
                'promotional_price' => 1.00,
                'slug' => 'زيادة-متابعين-تيك-توك',
                'category_id' => 11, // زيادة متابعين
            ],
            [
                'identifier' => 'p1734243089',
                'name' => [
                    'en' => 'Instagram Followers',
                    'ar' => 'زيادة متابعين انستقرام'
                ],
                'price' => 1.00,
                'promotional_price' => 1.00,
                'slug' => 'زيادة-متابعين-انستقرام',
                'category_id' => 11, // زيادة متابعين
            ],
            [
                'identifier' => 'p2098653069',
                'name' => [
                    'en' => 'Crunchyroll 6 Months',
                    'ar' => 'اشتراك كرانشي رول [رسمي] 6 أشهر'
                ],
                'price' => 58.00,
                'promotional_price' => 58.00,
                'slug' => 'اشتراك-كرانشي-رول-رسمي-6اشهر',
                'category_id' => 5, // كرانشي رول
            ],
            [
                'identifier' => 'p41137242',
                'name' => [
                    'en' => 'Crunchyroll 1 Month',
                    'ar' => 'اشتراك كرانشي رول [رسمي] شهر'
                ],
                'price' => 11.00,
                'promotional_price' => 11.00,
                'slug' => 'اشتراك-كرانشي-رول-رسمي-شهر',
                'category_id' => 5, // كرانشي رول
            ],
            [
                'identifier' => 'p381359885',
                'name' => [
                    'en' => 'Watch IT 6 Months',
                    'ar' => 'اشتراك واتش إت [رسمي] 6 أشهر'
                ],
                'price' => 74.00,
                'promotional_price' => 74.00,
                'slug' => 'اشتراك-واتش-إت-رسمي-6اشهر',
                'category_id' => 6, // watch it
            ],
            [
                'identifier' => 'p759602934',
                'name' => [
                    'en' => 'Watch IT 1 Month',
                    'ar' => 'اشتراك واتش إت [رسمي] شهر'
                ],
                'price' => 14.00,
                'promotional_price' => 14.00,
                'slug' => 'اشتراك-واتش-إت-رسمي-شهر',
                'category_id' => 6, // watch it
                'type' => 'digital',

            ],
            [
                'identifier' => 'p312022680',
                'name' => [
                    'en' => 'Netflix Official - 2 Months',
                    'ar' => 'حساب نتفلكس [رسمي] شهرين'
                ],
                'price' => 32.00,
                'promotional_price' => 32.00,
                'slug' => 'حساب-نتفلكس-رسمي-شهرين',
                'category_id' => 10, // NETFLIX
                'images' => 'products/flextwomonth.webp',
                'type' => 'digital',

                'description_ar' => '{اشتراك فليكس رسمي مضمون}

                الضمان الذهبي: 
            
                اذا تقفل الحساب قبل المدة بنرجعلك فلوسك دبل🤩 
            
                المميزات:
            
                •مدة الأشتراك:شهرين✅
            
                •اعلى جودة:4k✅
            
                •اللغة العربية✅
            
                •{تقدر تدخل الحساب بجميع اجهزتك}✅
            
                •{يشتغل على جميع الأجهزة}✅
            
                •{ضمان58يوم}✅
            
                •{مستحيل يقفل}✅
            
                •[الحساب ممكن يطول معك اكثر من شهرين]
            
                {الحساب لشخص واحد}
            
                •التسليم[فوري]✅
            
                [بعد الدفع تصلك رسالة نصية على جوالك فيها جميع معلومات الحساب ✅]
            
                -------------------------------------
            
                للدفع عبر  سوا او تحويل بنكي[اضغط هنا] ',
                'description_en' => 'Golden Guarantee:  
            
                If you close your account before the deadline, we will double your money.  
            
                Features:
            
                •Subscription period: two months✅
            
                •Highest quality: 4k✅
            
                •Arabic language✅
            
                •{You can access your account on all your devices}✅
            
                •{Works on all devices}✅
            
                •{58-day warranty}✅
            
                •{Impossible to close}✅
            
                •[The account may take more than two months]
            
                {Account for one person}
            
                •Delivery [immediate]✅
            
                [After payment, you will receive a text message on your mobile phone containing all the account information ✅]
            
                -------------------------------------
            
                To pay via Either by bank transfer [ click here ]',
                'is_active' => 1,

            ],
            [
                'identifier' => 'p1886408876',
                'name' => [
                    'en' => 'Netflix Official - 1 Month',
                    'ar' => 'حساب نتفلكس [رسمي] شهر'
                ],
                'price' => 17.00,
                'promotional_price' => 17.00,
                'slug' => 'حساب-نتفلكس-رسمي-شهر',
                'category_id' => 10, // NETFLIX
                'images' => 'products/flexmonth.jpeg',


                                'description_ar' => '{اشتراك فليكس رسمي مضمون}

                    الضمان الذهبي: 

                    اذا تقفل الحساب قبل المدة بنرجعلك فلوسك دبل🤩 

                    المميزات:

                    •مدة الأشتراك:شهر✅

                    •اعلى جودة:4k✅

                    •اللغة العربية✅

                    •{تقدر تدخل الحساب بجميع اجهزتك}✅

                    •{يشتغل على جميع الأجهزة}✅

                    •{ضمان29يوم}✅

                    •{مستحيل يقفل}✅

                    •[الحساب ممكن يطول معك اكثر من شهر]

                    {الحساب لشخص واحد}

                    •التسليم[فوري]✅

                    [بعد الدفع تصلك رسالة نصية على جوالك فيها جميع معلومات الحساب ✅]

                    -------------------------------------

                    للدفع عبر  سوا او تحويل بنكي[اضغط هنا]',
                                'description_en' => '{ Official guaranteed Flex subscription }

                    Golden Guarantee:  

                    If you close your account before the deadline, we will double your money.  

                    Features:

                    •Subscription period: one month✅

                    •Highest quality: 4k✅

                    •Arabic language✅

                    •{You can access your account on all your devices}✅

                    •{Works on all devices}✅

                    •{29-day warranty}✅

                    •{Impossible to close}✅

                    •[The account may take more than a month]

                    {Account for one person}

                    •Delivery [immediate]✅

                    [After payment, you will receive a text message on your mobile phone containing all the account information ✅]

                    -------------------------------------

                    To pay via Either by bank transfer [ click here ]',
                                'is_active' => 1,

            ],
            [
                'identifier' => 'p1886408876',
                'name' => [
                    'en' => 'PlayStation Store Saudi 50',
                    'ar' => 'بلايستيشن ستور سعودي 50'
                ],
                'price' => 199.00,
                'promotional_price' => null,
                'slug' => 'بلايستيشن-ستور-سعودي-50',
                'category_id' => 12, // PlayStation Store category ID
                'images' => 'products/card50.jpeg',
                'is_active' => 1,
                'type' => 'digital',

            ],
            [
                'identifier' => 'p1886908877',
                'name' => [
                    'en' => 'PlayStation Store Saudi 10',
                    'ar' => 'بلايستيشن ستور سعودي 10'
                ],
                'price' => 43.00,
                'promotional_price' => null,
                'slug' => 'بلايستيشن-ستور-سعودي-10',
                'category_id' => 12, // PlayStation Store category ID
                'images' => 'products/card10.jpeg',
                'is_active' => 1,
                'type' => 'digital',

            ],

        ];



        // Insert products into database
        foreach ($products as $productData) {
            // Create the main product
            $product = Product::create([
                'identifier' => $productData['identifier'],
                'name' => $productData['name'],
                'sub_title' => $productData['sub_title'] ?? null,
                'type' => $productData['type'] ?? 'service',
                'slug' => $productData['slug'],
                'images' => $productData['images'] ?? null,
                'tags' => $productData['tags'] ?? [],
                'sku' => $productData['sku'] ?? null,
                'description_ar' => $productData['description_ar'] ?? null,
                'description_en' => $productData['description_en'] ?? null,
                'price' => $productData['price'],
                'promotional_price' => $productData['promotional_price'],
                'is_active' => $productData['is_active'] ?? 1,
                'is_featured' => $productData['is_featured'] ?? 0,
                'in_stock' => $productData['in_stock'] ?? 1,
                'on_sale' => $productData['on_sale'] ?? 0,
                'category_id' => $productData['category_id'],
                'brand_id' => $productData['brand_id'] ?? null,
            ]);
        
            // Create product fields if they exist
            if (!empty($productData['product_fields'])) {
                foreach ($productData['product_fields'] as $fieldData) {
                    $productField = $product->productFields()->create([
                        'name' => $fieldData['name'],
                        'input_type' => $fieldData['input_type'],
                        'required' => $fieldData['required'] ?? 0,
                        'multiple' => $fieldData['multiple'] ?? 0,
                        // 'order' => $fieldData['order'] ?? 0,
                        // 'hint' => $fieldData['hint'] ?? null,
                        'options' => $fieldData['options'] ?? null,
                    ]);
        
                    // Create options if they exist (for select, radio, etc.)
                    // if (!empty($fieldData['options'])) {
                    //     foreach ($fieldData['options'] as $optionData) {
                    //         $productField->options()->create([
                    //             'key' => $optionData['key'],
                    //             'value' => $optionData['value'],
                    //             // 'description' => $optionData['description'] ?? null,
                    //         ]);
                    //     }
                    // }
                }
            }
        }
    }
}
