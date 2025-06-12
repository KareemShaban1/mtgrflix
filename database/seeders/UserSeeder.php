<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();
        // Super Admin (Saudi)
        $adminSaudi =     User::create([
            'first_name' => 'Admin',
            'last_name' => 'Saudi',
            'name' => 'Admin Saudi',
            'birthday' => '1985-05-15',
            'gender' => 'male',
            'email' => 'admin.sa@flix.com',
            'email_verified_at' => now(),
            'country_code' => '+966',
            'phone' => '501234567',
            'mobile' => '+966501234567', // Combined field
            'password' => Hash::make('password123'),
            'prefer_ads' => false,
            'is_admin' => true,
            'is_verified' => true,
            'is_active' => true,
        ]);

        $adminSaudi->assignRole('super_admin');

        // Super Admin (Egyptian)
        $adminEgypt =    User::create([
            'first_name' => 'Admin',
            'last_name' => 'Egypt',
            'name' => 'Admin Egypt',
            'birthday' => '1988-08-20',
            'gender' => 'male',
            'email' => 'admin.eg@flix.com',
            'email_verified_at' => now(),
            'country_code' => '+20',
            'phone' => '1001234567',
            'mobile' => '+201001234567', // Combined field
            'password' => Hash::make('password123'),
            'prefer_ads' => false,
            'is_admin' => true,
            'is_verified' => true,
            'is_active' => true,
        ]);

        $adminEgypt->assignRole('admin');

        // Test Users (Saudi)
        $saudiUsers = [
            [
                'first_name' => 'Mohammed',
                'last_name' => 'Al-Saud',
                'phone' => '502345678',
                'email' => 'mohammed@example.com'
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Al-Ghamdi',
                'phone' => '503456789',
                'email' => 'sarah@example.com'
            ]
        ];

        // Test Users (Egyptian)
        $egyptianUsers = [
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Mohamed',
                'phone' => '1012345678',
                'email' => 'ahmed@example.com'
            ],
            [
                'first_name' => 'Fatima',
                'last_name' => 'Ibrahim',
                'phone' => '1023456789',
                'email' => 'fatima@example.com'
            ]
        ];

        // Create Saudi users
        foreach ($saudiUsers as $user) {
            User::create([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'birthday' => fake()->date(),
                'gender' => fake()->randomElement(['male', 'female']),
                'email' => $user['email'],
                'email_verified_at' => now(),
                'country_code' => '+966',
                'phone' => $user['phone'],
                'mobile' => '+966' . $user['phone'], // Combined field
                'password' => Hash::make('password123'),
                'prefer_ads' => fake()->boolean(),
                'is_admin' => false,
                'is_verified' => true,
                'is_active' => true,
            ]);
        }

        // Create Egyptian users
        foreach ($egyptianUsers as $user) {
            User::create([
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'birthday' => fake()->date(),
                'gender' => fake()->randomElement(['male', 'female']),
                'email' => $user['email'],
                'email_verified_at' => now(),
                'country_code' => '+20',
                'phone' => $user['phone'],
                'mobile' => '+20' . $user['phone'], // Combined field
                'password' => Hash::make('password123'),
                'prefer_ads' => fake()->boolean(),
                'is_admin' => false,
                'is_verified' => true,
                'is_active' => true,
            ]);
        }

        $this->command->info('Successfully seeded users with combined mobile numbers!');
    }
}
