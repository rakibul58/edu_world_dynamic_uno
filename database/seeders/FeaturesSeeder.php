<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeaturesSection;
use App\Models\FeaturesItem;

class FeaturesSeeder extends Seeder
{
    public function run()
    {
        // Create section settings
        FeaturesSection::create([
            'section_title' => 'Why Choose Edu World?',
            'section_description' => 'Discover what makes our Islamic Cambridge education exceptional and how we prepare students for success in this life and the next.',
            'is_active' => true
        ]);

        // Create default features
        $features = [
            [
                'icon' => '🎓',
                'title' => 'Academic Excellence',
                'description' => 'Our Cambridge curriculum ensures international standards while our dedicated teachers provide personalized attention to help every student reach their full potential.',
                'color' => '#ff7101',
                'order' => 0
            ],
            [
                'icon' => '🕌',
                'title' => 'Islamic Values',
                'description' => 'We integrate Islamic principles into every aspect of learning, fostering spiritual growth alongside academic achievement in a nurturing environment.',
                'color' => '#20bf6b',
                'order' => 1
            ],
            [
                'icon' => '🌟',
                'title' => 'Character Development',
                'description' => 'Beyond academics, we focus on building strong moral character, leadership skills, and social responsibility in our students.',
                'color' => '#f7b731',
                'order' => 2
            ],
            [
                'icon' => '🌍',
                'title' => 'Global Perspective',
                'description' => 'Preparing students for international opportunities while keeping them rooted in Islamic values and cultural heritage.',
                'color' => '#102e4a',
                'order' => 3
            ],
            [
                'icon' => '👥',
                'title' => 'Small Class Sizes',
                'description' => 'Our low student-teacher ratio ensures personalized attention and meaningful relationships between educators and students.',
                'color' => '#8e44ad',
                'order' => 4
            ],
            [
                'icon' => '🏆',
                'title' => 'Proven Results',
                'description' => 'Our graduates consistently excel in higher education and contribute meaningfully to their communities worldwide.',
                'color' => '#e74c3c',
                'order' => 5
            ]
        ];

        foreach ($features as $feature) {
            FeaturesItem::create($feature);
        }
    }
}
