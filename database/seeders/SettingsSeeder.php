<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    private const SETTINGS = [
        ['slug' => 'site_name', 'title' => 'Site Name', 'value' => 'Lashop'],
        ['slug' => 'text_on_home', 'title' => 'Text on Home', 'value' => 'Welcome to our online store! We offer a wide range of quality products at competitive prices. Free shipping on orders over $50.'],
        ['slug' => 'text_on_footer', 'title' => 'Text on Footer', 'value' => 'Your trusted online store for quality products. We ship worldwide and offer easy returns.'],
        ['slug' => 'products_count', 'title' => 'Products per Page', 'value' => '6'],
        ['slug' => 'search_count', 'title' => 'Search Results per Page', 'value' => '6'],
        ['slug' => 'rate', 'title' => 'Price Coefficient', 'value' => '1'],
        ['slug' => 'tel_1', 'title' => 'Phone 1', 'value' => '+1 (555) 123-4567'],
        ['slug' => 'tel_2', 'title' => 'Phone 2', 'value' => '+1 (555) 987-6543'],
        ['slug' => 'email', 'title' => 'Contact Email', 'value' => 'info@lashop.com'],
        ['slug' => 'address', 'title' => 'Contact Address', 'value' => '123 Commerce Street, New York, NY 10001'],
        ['slug' => 'image_size_big', 'title' => 'Big (WxH)', 'value' => '800x600'],
        ['slug' => 'image_size_medium', 'title' => 'Medium (WxH)', 'value' => '400x300'],
        ['slug' => 'image_size_small', 'title' => 'Small (WxH)', 'value' => '120x90'],
    ];

    public function run(): void
    {
        foreach (self::SETTINGS as $setting) {
            Setting::updateOrCreate(
                ['slug' => $setting['slug']],
                [
                    'title' => $setting['title'],
                    'value' => $setting['value'] ?? null,
                ]
            );
        }
    }
}
