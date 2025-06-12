<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Currency::truncate();
        $currencies = [
            // Middle East & Africa
            ['code' => 'AED', 'symbol' => 'د.إ', 'rate' => 0.98, 'name' => ['en' => 'UAE Dirham', 'ar' => 'درهم إماراتي'], 'country_code' => '971'],
            ['code' => 'BHD', 'symbol' => '.د.ب', 'rate' => 0.10, 'name' => ['en' => 'Bahraini Dinar', 'ar' => 'دينار بحريني'], 'country_code' => '973'],
            ['code' => 'DZD', 'symbol' => 'د.ج', 'rate' => 36.00, 'name' => ['en' => 'Algerian Dinar', 'ar' => 'دينار جزائري'], 'country_code' => '213'],
            ['code' => 'EGP', 'symbol' => 'ج.م', 'rate' => 12.50, 'name' => ['en' => 'Egyptian Pound', 'ar' => 'جنيه مصري'], 'country_code' => '20'],
            ['code' => 'IQD', 'symbol' => 'ع.د', 'rate' => 350.00, 'name' => ['en' => 'Iraqi Dinar', 'ar' => 'دينار عراقي'], 'country_code' => '964'],
            ['code' => 'JOD', 'symbol' => 'د.أ', 'rate' => 0.19, 'name' => ['en' => 'Jordanian Dinar', 'ar' => 'دينار أردني'], 'country_code' => '962'],
            ['code' => 'KWD', 'symbol' => 'د.ك', 'rate' => 0.08, 'name' => ['en' => 'Kuwaiti Dinar', 'ar' => 'دينار كويتي'], 'country_code' => '965'],
            ['code' => 'LYD', 'symbol' => 'ل.د', 'rate' => 1.27, 'name' => ['en' => 'Libyan Dinar', 'ar' => 'دينار ليبي'], 'country_code' => '218'],
            ['code' => 'MAD', 'symbol' => 'د.م.', 'rate' => 2.46, 'name' => ['en' => 'Moroccan Dirham', 'ar' => 'درهم مغربي'], 'country_code' => '212'],
            ['code' => 'OMR', 'symbol' => 'ر.ع.', 'rate' => 0.10, 'name' => ['en' => 'Omani Rial', 'ar' => 'ريال عماني'], 'country_code' => '968'],
            ['code' => 'QAR', 'symbol' => 'ر.ق', 'rate' => 0.97, 'name' => ['en' => 'Qatari Riyal', 'ar' => 'ريال قطري'], 'country_code' => '974'],
            ['code' => 'SAR', 'symbol' => '﷼', 'rate' => 1.00, 'name' => ['en' => 'Saudi Riyal', 'ar' => 'ريال سعودي'], 'country_code' => '966'],
            ['code' => 'SDG', 'symbol' => 'ج.س.', 'rate' => 330.00, 'name' => ['en' => 'Sudanese Pound', 'ar' => 'جنيه سوداني'], 'country_code' => '249'],
            ['code' => 'TND', 'symbol' => 'د.ت', 'rate' => 0.86, 'name' => ['en' => 'Tunisian Dinar', 'ar' => 'دينار تونسي'], 'country_code' => '216'],
            ['code' => 'YER', 'symbol' => 'ر.ي', 'rate' => 250.00, 'name' => ['en' => 'Yemeni Rial', 'ar' => 'ريال يمني'], 'country_code' => '967'],
            ['code' => 'ZAR', 'symbol' => 'R', 'rate' => 4.50, 'name' => ['en' => 'South African Rand', 'ar' => 'راند جنوب أفريقي'], 'country_code' => '27'],

            // Asia
            ['code' => 'CNY', 'symbol' => '¥', 'rate' => 1.95, 'name' => ['en' => 'Chinese Yuan', 'ar' => 'يوان صيني'], 'country_code' => '86'],
            ['code' => 'INR', 'symbol' => '₹', 'rate' => 22.00, 'name' => ['en' => 'Indian Rupee', 'ar' => 'روبية هندية'], 'country_code' => '91'],
            ['code' => 'JPY', 'symbol' => '¥', 'rate' => 40.00, 'name' => ['en' => 'Japanese Yen', 'ar' => 'ين ياباني'], 'country_code' => '81'],
            ['code' => 'MYR', 'symbol' => 'RM', 'rate' => 1.26, 'name' => ['en' => 'Malaysian Ringgit', 'ar' => 'رينغيت ماليزي'], 'country_code' => '60'],
            ['code' => 'PKR', 'symbol' => '₨', 'rate' => 75.00, 'name' => ['en' => 'Pakistani Rupee', 'ar' => 'روبية باكستانية'], 'country_code' => '92'],
            ['code' => 'THB', 'symbol' => '฿', 'rate' => 9.50, 'name' => ['en' => 'Thai Baht', 'ar' => 'بات تايلاندي'], 'country_code' => '66'],
            ['code' => 'TRY', 'symbol' => '₺', 'rate' => 8.60, 'name' => ['en' => 'Turkish Lira', 'ar' => 'ليرة تركية'], 'country_code' => '90'],
            ['code' => 'IDR', 'symbol' => 'Rp', 'rate' => 4200.00, 'name' => ['en' => 'Indonesian Rupiah', 'ar' => 'روبية إندونيسية'], 'country_code' => '62'],

            // Europe
            ['code' => 'EUR', 'symbol' => '€', 'rate' => 0.25, 'name' => ['en' => 'Euro', 'ar' => 'يورو'], 'country_code' => null], // Multiple countries
            ['code' => 'GBP', 'symbol' => '£', 'rate' => 0.21, 'name' => ['en' => 'British Pound', 'ar' => 'جنيه إسترليني'], 'country_code' => '44'],
            ['code' => 'CHF', 'symbol' => 'Fr', 'rate' => 0.23, 'name' => ['en' => 'Swiss Franc', 'ar' => 'فرنك سويسري'], 'country_code' => '41'],
            ['code' => 'SEK', 'symbol' => 'kr', 'rate' => 2.85, 'name' => ['en' => 'Swedish Krona', 'ar' => 'كرونة سويدية'], 'country_code' => '46'],
            ['code' => 'NOK', 'symbol' => 'kr', 'rate' => 2.80, 'name' => ['en' => 'Norwegian Krone', 'ar' => 'كرونة نرويجية'], 'country_code' => '47'],
            ['code' => 'DKK', 'symbol' => 'kr', 'rate' => 1.90, 'name' => ['en' => 'Danish Krone', 'ar' => 'كرونة دنماركية'], 'country_code' => '45'],
            ['code' => 'RUB', 'symbol' => '₽', 'rate' => 23.00, 'name' => ['en' => 'Russian Ruble', 'ar' => 'روبل روسي'], 'country_code' => '7'],

            // Americas
            ['code' => 'USD', 'symbol' => '$', 'rate' => 0.27, 'name' => ['en' => 'US Dollar', 'ar' => 'دولار أمريكي'], 'country_code' => '1'],
            ['code' => 'CAD', 'symbol' => 'C$', 'rate' => 0.36, 'name' => ['en' => 'Canadian Dollar', 'ar' => 'دولار كندي'], 'country_code' => '1'],
            ['code' => 'BRL', 'symbol' => 'R$', 'rate' => 1.40, 'name' => ['en' => 'Brazilian Real', 'ar' => 'ريال برازيلي'], 'country_code' => '55'],
            ['code' => 'MXN', 'symbol' => '$', 'rate' => 5.50, 'name' => ['en' => 'Mexican Peso', 'ar' => 'بيزو مكسيكي'], 'country_code' => '52'],
            ['code' => 'ARS', 'symbol' => '$', 'rate' => 280.00, 'name' => ['en' => 'Argentine Peso', 'ar' => 'بيزو أرجنتيني'], 'country_code' => '54'],

            // Oceania
            ['code' => 'AUD', 'symbol' => 'A$', 'rate' => 0.40, 'name' => ['en' => 'Australian Dollar', 'ar' => 'دولار أسترالي'], 'country_code' => '61'],
            ['code' => 'NZD', 'symbol' => 'NZ$', 'rate' => 0.44, 'name' => ['en' => 'New Zealand Dollar', 'ar' => 'دولار نيوزيلندي'], 'country_code' => '64'],

            // Africa (New)
            ['code' => 'ETB', 'symbol' => 'Br', 'rate' => 56.00, 'name' => ['en' => 'Ethiopian Birr', 'ar' => 'بير إثيوبي'], 'country_code' => '251'],
            ['code' => 'GHS', 'symbol' => '₵', 'rate' => 14.00, 'name' => ['en' => 'Ghanaian Cedi', 'ar' => 'سيدي غاني'], 'country_code' => '233'],
            ['code' => 'KES', 'symbol' => 'KSh', 'rate' => 160.00, 'name' => ['en' => 'Kenyan Shilling', 'ar' => 'شيلينغ كيني'], 'country_code' => '254'],
            ['code' => 'NGN', 'symbol' => '₦', 'rate' => 500.00, 'name' => ['en' => 'Nigerian Naira', 'ar' => 'نيرة نيجيرية'], 'country_code' => '234'],
            ['code' => 'RWF', 'symbol' => 'FRw', 'rate' => 1200.00, 'name' => ['en' => 'Rwandan Franc', 'ar' => 'فرنك رواندي'], 'country_code' => '250'],

            // Asia (New)
            ['code' => 'BDT', 'symbol' => '৳', 'rate' => 110.00, 'name' => ['en' => 'Bangladeshi Taka', 'ar' => 'تاكا بنغلاديشي'], 'country_code' => '880'],
            ['code' => 'KHR', 'symbol' => '៛', 'rate' => 4100.00, 'name' => ['en' => 'Cambodian Riel', 'ar' => 'رييل كمبودي'], 'country_code' => '855'],
            ['code' => 'MMK', 'symbol' => 'K', 'rate' => 2100.00, 'name' => ['en' => 'Myanmar Kyat', 'ar' => 'كيات ميانماري'], 'country_code' => '95'],
            ['code' => 'NPR', 'symbol' => '₨', 'rate' => 133.00, 'name' => ['en' => 'Nepalese Rupee', 'ar' => 'روبية نيبالية'], 'country_code' => '977'],
            ['code' => 'PHP', 'symbol' => '₱', 'rate' => 56.00, 'name' => ['en' => 'Philippine Peso', 'ar' => 'بيزو فلبيني'], 'country_code' => '63'],
            ['code' => 'LKR', 'symbol' => 'Rs', 'rate' => 330.00, 'name' => ['en' => 'Sri Lankan Rupee', 'ar' => 'روبية سريلانكية'], 'country_code' => '94'],
            ['code' => 'VND', 'symbol' => '₫', 'rate' => 25000.00, 'name' => ['en' => 'Vietnamese Dong', 'ar' => 'دونغ فيتنامي'], 'country_code' => '84'],

            // Europe (New)
            ['code' => 'HUF', 'symbol' => 'Ft', 'rate' => 400.00, 'name' => ['en' => 'Hungarian Forint', 'ar' => 'فورنت مجري'], 'country_code' => '36'],
            ['code' => 'PLN', 'symbol' => 'zł', 'rate' => 1.70, 'name' => ['en' => 'Polish Złoty', 'ar' => 'زلوتي بولندي'], 'country_code' => '48'],
            ['code' => 'RON', 'symbol' => 'lei', 'rate' => 1.25, 'name' => ['en' => 'Romanian Leu', 'ar' => 'ليو روماني'], 'country_code' => '40'],
            ['code' => 'UAH', 'symbol' => '₴', 'rate' => 11.00, 'name' => ['en' => 'Ukrainian Hryvnia', 'ar' => 'هريفنيا أوكرانية'], 'country_code' => '380'],

            // Americas (New)
            ['code' => 'CLP', 'symbol' => '$', 'rate' => 980.00, 'name' => ['en' => 'Chilean Peso', 'ar' => 'بيزو تشيلي'], 'country_code' => '56'],
            ['code' => 'COP', 'symbol' => '$', 'rate' => 4500.00, 'name' => ['en' => 'Colombian Peso', 'ar' => 'بيزو كولومبي'], 'country_code' => '57'],
            ['code' => 'PEN', 'symbol' => 'S/', 'rate' => 1.05, 'name' => ['en' => 'Peruvian Sol', 'ar' => 'سول بيروفي'], 'country_code' => '51'],
            ['code' => 'GTQ', 'symbol' => 'Q', 'rate' => 2.10, 'name' => ['en' => 'Guatemalan Quetzal', 'ar' => 'كيتزال غواتيمالي'], 'country_code' => '502'],

            // Oceania (New)
            ['code' => 'FJD', 'symbol' => 'FJ$', 'rate' => 0.55, 'name' => ['en' => 'Fijian Dollar', 'ar' => 'دولار فيجي'], 'country_code' => '679'],
            ['code' => 'PGK', 'symbol' => 'K', 'rate' => 1.90, 'name' => ['en' => 'Papua New Guinean Kina', 'ar' => 'كينا بابوا غينيا'], 'country_code' => '675'],

            // Special Cases
            ['code' => 'XOF', 'symbol' => 'CFA', 'rate' => 325.00, 'name' => ['en' => 'West African CFA Franc', 'ar' => 'فرنك غرب أفريقي'], 'country_code' => null], // Used in 8 countries
            ['code' => 'XPF', 'symbol' => '₣', 'rate' => 120.00, 'name' => ['en' => 'CFP Franc', 'ar' => 'فرنك باسيفيكي'], 'country_code' => null], // French territories
        ];
        $order = 1;
        foreach ($currencies as $index => $currency) {
            Currency::create([
                'code' => $currency['code'],
                'symbol' => $currency['symbol'],
                'name' => $currency['name'],
                'exchange_rate' => $currency['rate'],
                'order' => $order++
            ]);
        }
    }
}
