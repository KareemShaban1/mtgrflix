<?php

namespace App\Filament\Pages\Settings;


use Spatie\LaravelSettings\Settings;


class Site_Settings extends Settings
{
    public string $site_name;
    public string $site_description_ar;
    public string $site_description_en;
    public string $site_keywords;
    public $site_profile;
    public $site_logo;
    public string $site_author;
    public string $site_address;
    public string $site_email;
    public string $site_phone;
    public string $site_phone_code;
    public string $site_location;
    public string $site_currency;
    public string $site_language;
    public array $site_social;
    public string $whatsapp_template_5_star_review;
    public string $whatsapp_template_4_star_review;
    public string $whatsapp_template_1_star_review;
    public string $whatsapp_template_abandoned_cart;
    public string $whatsapp_template_order_delivered;
    public string $whatsapp_template_review_request;
    public static function group(): string
    {
        return 'sites';
    }
}
