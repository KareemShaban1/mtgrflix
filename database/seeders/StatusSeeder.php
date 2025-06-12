<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Status::truncate();
        $statuses = [
            [
                'name' => [
                    'en' => 'Pending',
                    'ar' => 'قيد الانتظار' //1
                ]
            ],
            [
                'name' => [
                    'en' => 'Shipped',
                    'ar' => 'تم الشحن' //2
                ]
            ],

            [
                'name' => [
                    'en' => 'Completed', //3
                    'ar' => 'تم التنفيذ'
                ]
            ],
            [
                'name' => [
                    'en' => 'Cancelled', //4
                    'ar' => 'تم الإلغاء'
                ]
            ],
            [
                'name' => [
                    'en' => 'declined', //5
                    'ar' => 'مرفوضة'
                ]
            ],
        ];


        foreach ($statuses as $status) {
            Status::create($status);
        }
    }
}
