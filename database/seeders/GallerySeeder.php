<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\GallerySetting;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run()
    {
        // Create default gallery settings
        GallerySetting::create([
            'section_title' => 'Explore Our Beautiful Campus',
            'section_description' => 'Take a virtual tour of our state-of-the-art facilities designed to inspire learning and spiritual growth.',
            'section_title_color' => '#102e4a',
            'section_title_font_size' => '2.5rem',
            'section_title_font_weight' => '700',
            'section_description_color' => '#111111',
            'section_description_font_size' => '1.1rem',
            'section_background_color' => '#f8fbfe',
            'section_padding' => '80px 0',
            'header_text_align' => 'center',
            'grid_columns' => 'repeat(auto-fit, minmax(350px, 1fr))',
            'grid_gap' => '20px',
            'container_max_width' => '1240px',
            'container_padding' => '0 20px',
            'is_active' => true,
        ]);

        // Create sample gallery items
        $items = [
            [
                'title' => 'Main Building',
                'icon' => '🏛️',
                'description' => 'Our iconic main building houses administrative offices and key facilities.',
                'background_type' => 'gradient',
                'gradient_start' => '#ff7101',
                'gradient_end' => '#102e4a',
                'gradient_direction' => '135deg',
                'order' => 0,
            ],
            [
                'title' => 'Library',
                'icon' => '📚',
                'description' => 'A comprehensive library with thousands of books and digital resources.',
                'background_type' => 'gradient',
                'gradient_start' => '#20bf6b',
                'gradient_end' => '#102e4a',
                'gradient_direction' => '135deg',
                'order' => 1,
            ],
            [
                'title' => 'Science Labs',
                'icon' => '🔬',
                'description' => 'State-of-the-art laboratories for hands-on scientific learning.',
                'background_type' => 'gradient',
                'gradient_start' => '#f7b731',
                'gradient_end' => '#102e4a',
                'gradient_direction' => '135deg',
                'order' => 2,
            ],
            [
                'title' => 'Prayer Hall',
                'icon' => '🕌',
                'description' => 'A peaceful space for daily prayers and spiritual reflection.',
                'background_type' => 'gradient',
                'gradient_start' => '#8e44ad',
                'gradient_end' => '#102e4a',
                'gradient_direction' => '135deg',
                'order' => 3,
            ],
            [
                'title' => 'Sports Complex',
                'icon' => '⚽',
                'description' => 'Modern sports facilities for physical education and recreation.',
                'background_type' => 'gradient',
                'gradient_start' => '#e74c3c',
                'gradient_end' => '#102e4a',
                'gradient_direction' => '135deg',
                'order' => 4,
            ],
            [
                'title' => 'Arts Center',
                'icon' => '🎭',
                'description' => 'Creative spaces for arts, music, and cultural activities.',
                'background_type' => 'gradient',
                'gradient_start' => '#2ecc71',
                'gradient_end' => '#102e4a',
                'gradient_direction' => '135deg',
                'order' => 5,
            ],
        ];

        foreach ($items as $item) {
            GalleryItem::create($item);
        }
    }
}
