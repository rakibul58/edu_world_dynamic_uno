<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // Colors
            ['key' => 'primary_color', 'value' => '#ff7101', 'type' => 'color', 'group' => 'colors'],
            ['key' => 'secondary_color', 'value' => '#102e4a', 'type' => 'color', 'group' => 'colors'],
            ['key' => 'accent_color', 'value' => '#20bf6b', 'type' => 'color', 'group' => 'colors'],
            ['key' => 'warning_color', 'value' => '#f7b731', 'type' => 'color', 'group' => 'colors'],
            ['key' => 'dark_color', 'value' => '#030811', 'type' => 'color', 'group' => 'colors'],
            ['key' => 'light_color', 'value' => '#f8fbfe', 'type' => 'color', 'group' => 'colors'],

            // Typography
            ['key' => 'primary_font', 'value' => 'Rubik', 'type' => 'text', 'group' => 'typography'],
            ['key' => 'font_size_base', 'value' => '16px', 'type' => 'text', 'group' => 'typography'],
            ['key' => 'line_height_base', 'value' => '1.6', 'type' => 'text', 'group' => 'typography'],

            // Layout
            ['key' => 'border_radius_base', 'value' => '15px', 'type' => 'text', 'group' => 'layout'],
            ['key' => 'border_radius_small', 'value' => '5px', 'type' => 'text', 'group' => 'layout'],
            ['key' => 'border_radius_large', 'value' => '25px', 'type' => 'text', 'group' => 'layout'],
            ['key' => 'container_max_width', 'value' => '1240px', 'type' => 'text', 'group' => 'layout'],

            // Site Info
            ['key' => 'site_name', 'value' => 'Edu World', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'A Complete ERP System', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Providing exceptional Islamic Cambridge education that prepares students for success in this world and the hereafter.', 'type' => 'textarea', 'group' => 'general'],

            // Images
            ['key' => 'logo', 'value' => '/images/logo.png', 'type' => 'image', 'group' => 'media'],
            ['key' => 'favicon', 'value' => '/images/favicon.ico', 'type' => 'image', 'group' => 'media'],
            ['key' => 'footer_logo', 'value' => '/images/logo.png', 'type' => 'image', 'group' => 'media'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }
    }
}
