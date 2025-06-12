<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

class SitesSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('sites.site_name', 'flix');
        $this->migrator->add('sites.site_description', 'في متجر فليكس نوفرلك البطاقات والاشتراكات والخدمات الرقمية بأعلى جودة ممكنة وبأقل سعر ممكن لأن رضاك يهمنا 💙');
        $this->migrator->add('sites.site_keywords', 'Digital Products, Subscriptions, Streaming Services');
        $this->migrator->add('sites.site_profile', '');
        $this->migrator->add('sites.site_logo', '');
        $this->migrator->add('sites.site_author', 'flix Team');
        $this->migrator->add('sites.site_address', 'Riyadh, Saudi Arabia');
        $this->migrator->add('sites.site_email', 'support@fkx.sa');
        $this->migrator->add('sites.site_phone', '+966551200896');
        $this->migrator->add('sites.site_phone_code', '+966');
        $this->migrator->add('sites.site_location', 'Saudi Arabia');
        $this->migrator->add('sites.site_currency', 'SAR');
        $this->migrator->add('sites.site_language', 'Arabic');
        $this->migrator->add('sites.tiktok', 'https://www.tiktok.com/@mtgrflix');
        $this->migrator->add('sites.instagram', 'https://www.instagram.com/mtgrflixx');
        $this->migrator->add('sites.facebook', 'https://www.facebook.com/yourpage');
        $this->migrator->add('sites.top_menu', [
            'en' => '🚀 Exclusive Deals! Up to 50% Off on All Subscriptions! 🎉',
            'ar' => '🚀 عروض حصرية! خصومات تصل إلى 50% على جميع الاشتراكات! 🎉',
        ]);
        $this->migrator->add('sites.about', [
            'en' => '🚀 Exclusive Deals! Up to 50% Off on All Subscriptions! 🎉',
            'ar' => '🚀 عروض حصرية! خصومات تصل إلى 50% على جميع الاشتراكات! 🎉',
        ]);
        $this->migrator->add('sites.site_social', [
            'instagram' => 'https://instagram.com/fkx_store',
            'twitter' => 'https://twitter.com/fkx_store',
            'tiktok' => 'https://www.tiktok.com/@fkx_store',
            'snapchat' => 'https://www.snapchat.com/add/fkx_store',
        ]);
    }
}
