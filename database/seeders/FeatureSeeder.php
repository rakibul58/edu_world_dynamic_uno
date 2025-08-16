<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    public function run()
    {
        $features = [
            [
                'title' => 'Academic Excellence',
                'description' => 'Our Cambridge curriculum ensures international standards while our dedicated teachers provide personalized attention to help every student reach their full potential.',
                'icon' => '🎓',
                'icon_background_color' => '#ff7101',
                'sort_order' => 1,
            ],
            [
                'title' => 'Islamic Values',
                'description' => 'We integrate Islamic principles into every aspect of learning, fostering spiritual growth alongside academic achievement in a nurturing environment.',
                'icon' => '🕌',
                'icon_background_color' => '#20bf6b',
                'sort_order' => 2,
            ],
            [
                'title' => 'Character Development',
                'description' => 'Beyond academics, we focus on building strong moral character, leadership skills, and social responsibility in our students.',
                'icon' => '🌟',
                'icon_background_color' => '#f7b731',
                'sort_order' => 3,
            ],
            [
                'title' => 'Global Perspective',
                'description' => 'Preparing students for international opportunities while keeping them rooted in Islamic values and cultural heritage.',
                'icon' => '🌍',
                'icon_background_color' => '#ff7101',
                'sort_order' => 4,
            ],
            [
                'title' => 'Small Class Sizes',
                'description' => 'Our low student-teacher ratio ensures personalized attention and meaningful relationships between educators and students.',
                'icon' => '👥',
                'icon_background_color' => '#20bf6b',
                'sort_order' => 5,
            ],
            [
                'title' => 'Proven Results',
                'description' => 'Our graduates consistently excel in higher education and contribute meaningfully to their communities worldwide.',
                'icon' => '🏆',
                'icon_background_color' => '#f7b731',
                'sort_order' => 6,
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }
    }
}
