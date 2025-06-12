<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TomatoPHP\FilamentSettingsHub\Models\Setting;

class WhatsAppTemplateSeeder extends Seeder
{
    public function run()
    {
        $templates = [
            [
                'name' => '5_star_review',
                'payload' => 'واو سعيدين جداً ان خدمتنا نالت على رضاك 🌟
وهذا كود خصم {{code}} خاص فيك لطلبك القادم 🎉
ونسعد بخدمتك دائماً... متجر فليكس 💙'
            ],
            [
                'name' => '4_star_review',
                'payload' => 'كان كملتها 5 بس يالله مو مشكلة 😅
هذا كود خصم {{code}} خاص فيك لطلبك القادم 🎁
نتمنى مزحنا ماضايقك 😊
ونسعد بخدمتك دائماً... متجر فليكس 💙'
            ],
            [
                'name' => '1_star_review',
                'payload' => 'نعتذر جداً على التجربة السيئة 😔
الرجاء توضيح سبب التقييم حتى نستطيع تطوير خدمتنا بشكل افضل.
لنسعد بخدمتك دائماً... متجر فليكس 💙'
            ],
            [
                'name' => 'abandoned_cart',
                'payload' => 'مرحبا {{name}} 👋
[معك متجر فليكس] 💠
وجدنا أنك لم تكمل الطلب من متجرنا 🛒
ونود تذكيرك وتقديم خصم خاص {{code}} لأكمال الطلب:
{{checkout_url}}

🛑 مدة الخصم 12 ساعة لا يفوتك 🛑
متجر فليكس 💙'
            ],
            [
                'name' => 'order_delivered',
                'payload' => 'عميلنا العزيز {{name}} 💠
جايين نسلمك طلبك رقم {{order_number}} 🚚
{{code}}
شكراً لأختيارك متجر فليكس...
ونتمنى لك مشاهدة ممتعة ^_^'
            ],
            [
                'name' => 'review_request',
                'payload' => '{{name}} العزيز 🌟
نتمنى تقيم تجربتك مع متجر فليكس 🌐
يعنيلنا الكثير 🙏💙
{{review_url}}

🛒 إذا قيمت الطلب اليوم رح يوصلك هدية كود خصم خاص فيك 💳
لأستخدامه بطلبك القادم وتشرفنا بخدمتك 💙'
            ],
            [
                'name' => 'code_notification',
                'payload' => 'عميلنا العزيز {{name}} 💠
جايين نسلمك كود الطلب الخاص بك {{order_number}} 🤩

الكود الخاص بك هو:
{{code}}

🛑 مدة الخصم 12 ساعة لا يفوتك 🛑
متجر فليكس 💙'
            ]
        ];

        foreach ($templates as $template) {
            Setting::updateOrCreate(
                ['name' => 'whatsapp_template_' . $template['name']],
                [
                    'payload' => $template['payload'],
                    'group' => 'sites'
                ]
            );
        }

        $this->command->info('WhatsApp message templates seeded successfully!');
    }
}
