<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FacilitiesSection;
use App\Models\FacilityItem;
use App\Models\FacilityButton;

class FacilitiesSectionSeeder extends Seeder
{
    public function run()
    {
        // Create main facilities section
        $section = FacilitiesSection::create([
            'title' => 'World-Class Facilities',
            'subtitle' => 'Our campus is equipped with modern amenities and traditional Islamic architecture to create the perfect learning environment.',
            'background_color' => '#ffffff',
            'is_active' => true,
            'title_color' => '#102e4a',
            'title_font_size' => '2.5rem',
            'title_font_weight' => '700',
            'subtitle_color' => '#111111',
            'subtitle_font_size' => '1.1rem',
            'subtitle_font_weight' => '400',
            'featured_title' => 'Modern Classrooms',
            'featured_description' => 'Smart boards, air conditioning, and ergonomic furniture in every classroom to enhance the learning experience.',
            'featured_type' => 'gradient',
            'featured_gradient_start' => '#ff7101',
            'featured_gradient_end' => '#102e4a',
            'featured_gradient_direction' => '135deg',
            'featured_tint_color' => '#000000',
            'featured_tint_opacity' => 0.5,
            'featured_text_color' => '#ffffff',
            'featured_title_font_size' => '2rem',
            'featured_desc_font_size' => '1.1rem',
        ]);

        // Create facility items
        $facilityItems = [
            [
                'title' => 'Computer Lab',
                'description' => 'Latest technology and high-speed internet for digital learning.',
                'icon' => '💻',
                'sort_order' => 0,
                'background_color' => '#ffffff',
                'title_color' => '#102e4a',
                'description_color' => '#111111',
                'title_font_size' => '1.2rem',
                'description_font_size' => '0.95rem',
            ],
            [
                'title' => 'Halal Cafeteria',
                'description' => 'Nutritious, halal meals prepared fresh daily by our kitchen staff.',
                'icon' => '🍽️',
                'sort_order' => 1,
                'background_color' => '#ffffff',
                'title_color' => '#102e4a',
                'description_color' => '#111111',
                'title_font_size' => '1.2rem',
                'description_font_size' => '0.95rem',
            ],
            [
                'title' => 'Transport',
                'description' => 'Safe and reliable transportation covering major areas of the city.',
                'icon' => '🚌',
                'sort_order' => 2,
                'background_color' => '#ffffff',
                'title_color' => '#102e4a',
                'description_color' => '#111111',
                'title_font_size' => '1.2rem',
                'description_font_size' => '0.95rem',
            ],
            [
                'title' => 'Health Center',
                'description' => 'On-campus medical facility with qualified healthcare professionals.',
                'icon' => '🏥',
                'sort_order' => 3,
                'background_color' => '#ffffff',
                'title_color' => '#102e4a',
                'description_color' => '#111111',
                'title_font_size' => '1.2rem',
                'description_font_size' => '0.95rem',
            ],
        ];

        foreach ($facilityItems as $item) {
            $section->facilityItems()->create($item);
        }

        // Create facility buttons
        $facilityButtons = [
            [
                'text' => 'Explore',
                'url' => '#',
                'sort_order' => 0,
                'background_color' => '#ffffff',
                'text_color' => '#ff7101',
                'hover_background_color' => '#ff7101',
                'hover_text_color' => '#ffffff',
                'border_radius' => '25px',
                'font_size' => '1rem',
                'font_weight' => '600',
                'padding' => '12px 30px',
            ],
        ];

        foreach ($facilityButtons as $button) {
            $section->facilityButtons()->create($button);
        }
    }
}
