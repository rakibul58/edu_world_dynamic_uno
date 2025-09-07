<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FooterSection;

class FooterSectionSeeder extends Seeder
{
    public function run(): void
    {
        // First Footer (matches your hard-coded footer)
        FooterSection::create([
            'logo_path' => 'uploads/image/logo.png',
            'description' => 'Providing exceptional Islamic Cambridge education that prepares students for success in this world and the hereafter.',
            'tagline' => 'A Complete ERP System',

            'social_links' => [
                ['platform' => 'facebook', 'url' => '#', 'icon' => 'fab fa-facebook-f'],
                ['platform' => 'twitter', 'url' => '#', 'icon' => 'fab fa-twitter'],
                ['platform' => 'instagram', 'url' => '#', 'icon' => 'fab fa-instagram'],
                ['platform' => 'youtube', 'url' => '#', 'icon' => 'fab fa-youtube'],
            ],

            'quick_links' => [
                ['text' => 'About Us', 'url' => '#'],
                ['text' => 'Admissions', 'url' => '#'],
                ['text' => 'Programs', 'url' => '#'],
                ['text' => 'Campus Life', 'url' => '#'],
                ['text' => 'News & Events', 'url' => '#'],
            ],

            'programs' => [
                ['text' => 'Islamic Studies', 'url' => '#'],
                ['text' => 'Cambridge Curriculum', 'url' => '#'],
                ['text' => 'Hifz Program', 'url' => '#'],
                ['text' => 'Primary Education', 'url' => '#'],
                ['text' => 'Secondary Education', 'url' => '#'],
            ],

            'address' => '📍 Chattogram, Bangladesh',
            'phone' => '📞 +880 123 456 789',
            'email' => '✉️ info@eduworld.edu',
            'working_hours' => '🕒 Mon-Fri: 8:00 AM - 4:00 PM',

            'background_color' => '#030811',
            'text_color' => '#ffffff',
            'accent_color' => '#ff7101',
            'link_color' => '#cccccc',
            'link_hover_color' => '#ff7101',

            'is_active' => true,
        ]);

        // Second Footer (variation for testing)
        FooterSection::create([
            'logo_path' => 'uploads/image/alt-logo.png',
            'description' => 'Blending academic excellence with Islamic values for a brighter tomorrow.',
            'tagline' => 'Empowering Future Leaders',

            'social_links' => [
                ['platform' => 'facebook', 'url' => 'https://facebook.com/eduworldalt', 'icon' => 'fab fa-facebook-f'],
                ['platform' => 'linkedin', 'url' => 'https://linkedin.com/company/eduworld', 'icon' => 'fab fa-linkedin-in'],
                ['platform' => 'instagram', 'url' => 'https://instagram.com/eduworldalt', 'icon' => 'fab fa-instagram'],
            ],

            'quick_links' => [
                ['text' => 'Our Mission', 'url' => '/mission'],
                ['text' => 'Faculty', 'url' => '/faculty'],
                ['text' => 'Library', 'url' => '/library'],
                ['text' => 'Research', 'url' => '/research'],
            ],

            'programs' => [
                ['text' => 'Early Childhood', 'url' => '/programs/early-childhood'],
                ['text' => 'STEM Education', 'url' => '/programs/stem'],
                ['text' => 'Quran & Tajweed', 'url' => '/programs/quran'],
            ],

            'address' => '📍 Dhaka, Bangladesh',
            'phone' => '📞 +880 987 654 321',
            'email' => '✉️ contact@eduworld.edu',
            'working_hours' => '🕒 Sat-Thu: 9:00 AM - 5:00 PM',

            'background_color' => '#111827', // Dark Gray
            'text_color' => '#e5e7eb',       // Light Gray
            'accent_color' => '#10b981',     // Green
            'link_color' => '#9ca3af',       // Gray
            'link_hover_color' => '#10b981',

            'is_active' => false,
        ]);
    }
}
