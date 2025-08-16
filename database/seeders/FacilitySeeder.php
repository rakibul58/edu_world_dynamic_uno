<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            // Featured/Large Facility
            [
                'title' => 'Modern Classrooms',
                'description' => 'Smart boards, air conditioning, and ergonomic furniture in every classroom to enhance the learning experience.',
                'icon' => '🏛️',
                'type' => 'featured',
                'background_image' => null,
                'button_text' => 'Explore',
                'button_url' => '#',
                'sort_order' => 1,
                'is_active' => true,
            ],
            // Grid Facilities
            [
                'title' => 'Computer Lab',
                'description' => 'Latest technology and high-speed internet for digital learning.',
                'icon' => '💻',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Halal Cafeteria',
                'description' => 'Nutritious, halal meals prepared fresh daily by our kitchen staff.',
                'icon' => '🍽️',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Transport',
                'description' => 'Safe and reliable transportation covering major areas of the city.',
                'icon' => '🚌',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Health Center',
                'description' => 'On-campus medical facility with qualified healthcare professionals.',
                'icon' => '🏥',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 5,
                'is_active' => true,
            ],
            // Additional facilities that could be inferred from the site
            [
                'title' => 'Library',
                'description' => 'Extensive collection of books, digital resources, and quiet study spaces for students.',
                'icon' => '📚',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Science Laboratories',
                'description' => 'Well-equipped labs for physics, chemistry, and biology experiments and research.',
                'icon' => '🔬',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'Prayer Hall',
                'description' => 'Peaceful and spacious prayer facility for daily prayers and Islamic activities.',
                'icon' => '🕌',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'Sports Complex',
                'description' => 'Indoor and outdoor sports facilities for various physical activities and competitions.',
                'icon' => '⚽',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'Arts Center',
                'description' => 'Creative spaces for art, music, and cultural activities that align with Islamic values.',
                'icon' => '🎭',
                'type' => 'grid',
                'background_image' => null,
                'button_text' => null,
                'button_url' => null,
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
