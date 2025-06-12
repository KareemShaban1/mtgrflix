<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SplitUserMobiles extends Command
{
    protected $signature = 'users:split-mobiles';
    protected $description = 'Split mobile number into country_code and phone';

    public function handle()
    {
        $this->info('Starting to split mobile numbers...');

        $countryCodes = [
            '+966', // Saudi Arabia
            '+20',  // Egypt
            '+971', // UAE
            '+1',   // USA/Canada
            '+44',  // United Kingdom
            '+91',  // India
            '+81',  // Japan
            '+49',  // Germany
            '+33',  // France
            '+61',  // Australia
            '+39',  // Italy
            '+34',  // Spain
            '+7',   // Russia
            '+86',  // China
            '+90',  // Turkey
            '+62',  // Indonesia
            '+63',  // Philippines
            '+880', // Bangladesh
            '+92',  // Pakistan
            '+964', // Iraq
            '+965', // Kuwait
            '+968', // Oman
            '+973', // Bahrain
            '+974', // Qatar
            '+972', // Israel
            '+212', // Morocco
            '+213', // Algeria
            '+216', // Tunisia
            '+254', // Kenya
            '+27',  // South Africa
            '+84',  // Vietnam
            '+82',  // South Korea
            '+855', // Cambodia
            '+66',  // Thailand
            '+998', // Uzbekistan
            '+994', // Azerbaijan
            '+964', // Iraq
            '+43',  // Austria
            '+46',  // Sweden
            '+47',  // Norway
            '+48',  // Poland
            '+351', // Portugal
            '+358', // Finland
            '+380', // Ukraine
            '+421', // Slovakia
            '+420', // Czech Republic
            '+40',  // Romania
        ];

        User::chunk(100, function ($users) use ($countryCodes) {
            foreach ($users as $user) {
                if (empty($user->mobile)) {
                    continue;
                }

                $mobile = trim($user->mobile);
                $matched = false;

                foreach ($countryCodes as $code) {
                    if (str_starts_with($mobile, $code)) {
                        $user->country_code = $code;
                        $user->phone = substr($mobile, strlen($code));
                        $user->save();

                        $this->info("Updated User ID {$user->id}: {$user->country_code} {$user->phone}");
                        $matched = true;
                        break;
                    }
                }

                if (! $matched) {
                    $this->warn("Skipped User ID {$user->id} (Unknown format): {$mobile}");
                }
            }
        });

        $this->info('Mobile numbers processed successfully.');
    }
}
