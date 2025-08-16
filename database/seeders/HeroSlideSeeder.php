<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;

class HeroSlideSeeder extends Seeder
{
    public function run()
    {
        $slides = [
            [
                'title' => 'Welcome To Edu World',
                'subtitle' => 'A Complete ERP System',
                'description' => 'A complete system for educational institution management.',
                'background_color' => '#ff7101',
                'cta_primary_text' => 'Apply now',
                'cta_primary_url' => '#',
                'cta_secondary_text' => 'Learn more',
                'cta_secondary_url' => '#',
                'sort_order' => 1,
            ],
            [
                'title' => 'Excellence in Education',
                'subtitle' => 'Islamic Values & Modern Learning',
                'description' => 'Bridging traditional Islamic values with contemporary academic excellence.',
                'background_color' => '#20bf6b',
                'cta_primary_text' => 'Enroll Now',
                'cta_primary_url' => '#',
                'cta_secondary_text' => 'Contact Us',
                'cta_secondary_url' => '#',
                'sort_order' => 2,
            ]
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }
}
