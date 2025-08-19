<?php

namespace Database\Seeders;

use App\Models\PageSection;
use Illuminate\Database\Seeder;

class PageSectionSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            [
                'section_key' => 'header_section',
                'section_name' => 'Header Section',
                'description' => 'Navigation and site header',
                'sort_order' => 1,
                'is_visible' => true
            ],
            [
                'section_key' => 'hero_section',
                'section_name' => 'Hero Section',
                'description' => 'Main banner and call-to-action',
                'sort_order' => 2,
                'is_visible' => true
            ],
            [
                'section_key' => 'programs_section',
                'section_name' => 'Programs Section',
                'description' => 'Educational programs showcase',
                'sort_order' => 3,
                'is_visible' => true
            ],
            [
                'section_key' => 'about_section',
                'section_name' => 'About Section',
                'description' => 'About us information',
                'sort_order' => 4,
                'is_visible' => true
            ],
            [
                'section_key' => 'features_section',
                'section_name' => 'Features Section',
                'description' => 'Key features and benefits',
                'sort_order' => 5,
                'is_visible' => true
            ],
            [
                'section_key' => 'testimonials_section',
                'section_name' => 'Testimonials Section',
                'description' => 'Student and parent testimonials',
                'sort_order' => 6,
                'is_visible' => true
            ],
            [
                'section_key' => 'gallery_section',
                'section_name' => 'Gallery Section',
                'description' => 'Photo gallery',
                'sort_order' => 7,
                'is_visible' => true
            ],
            [
                'section_key' => 'facilities_section',
                'section_name' => 'Facilities Section',
                'description' => 'School facilities showcase',
                'sort_order' => 8,
                'is_visible' => true
            ],
            [
                'section_key' => 'academic_programs',
                'section_name' => 'Academic Programs',
                'description' => 'Detailed academic programs',
                'sort_order' => 9,
                'is_visible' => true
            ],
            [
                'section_key' => 'news_events',
                'section_name' => 'News & Events',
                'description' => 'Latest news and upcoming events',
                'sort_order' => 10,
                'is_visible' => true
            ],
            [
                'section_key' => 'location_section',
                'section_name' => 'Location Section',
                'description' => 'School location and contact info',
                'sort_order' => 11,
                'is_visible' => true
            ],
            [
                'section_key' => 'admission_process',
                'section_name' => 'Admission Process',
                'description' => 'How to apply information',
                'sort_order' => 12,
                'is_visible' => true
            ],
            [
                'section_key' => 'cta_section',
                'section_name' => 'Call to Action Section',
                'description' => 'Final call-to-action',
                'sort_order' => 13,
                'is_visible' => true
            ],
            [
                'section_key' => 'footer_section',
                'section_name' => 'Footer Section',
                'description' => 'Site footer with links and info',
                'sort_order' => 14,
                'is_visible' => true
            ]
        ];

        foreach ($sections as $section) {
            PageSection::create($section);
        }
    }
}
