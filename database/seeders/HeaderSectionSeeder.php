<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeaderSection;
use Illuminate\Support\Facades\DB;

class HeaderSectionSeeder extends Seeder
{
    public function run()
    {
        // Clear the table first
        DB::table('header_sections')->truncate();

        // Create default header section
        HeaderSection::create([
            'logo_type' => 'image',
            'logo_image_path' => '/uploads/logo.png',
            'logo_text' => 'EduConnect Academy',
            'logo_text_style' => json_encode([
                'color' => '#ffffff',
                'font_size' => '24px',
                'font_weight' => '700',
                'font_family' => "'Poppins', sans-serif",
                'text_transform' => 'uppercase',
                'letter_spacing' => '1px'
            ]),
            'logo_styles' => json_encode([
                'image' => [
                    'width' => '180px',
                    'height' => 'auto',
                    'object_fit' => 'contain',
                    'border' => [
                        'width' => '0',
                        'style' => 'solid',
                        'color' => '#000000',
                        'radius' => '0'
                    ],
                    'shadow' => [
                        'enabled' => false,
                        'color' => 'rgba(0,0,0,0.1)',
                        'x' => '0',
                        'y' => '2',
                        'blur' => '4',
                        'spread' => '0'
                    ],
                    'hover' => [
                        'opacity' => '0.8',
                        'scale' => '1.05'
                    ]
                ],
                'text' => [
                    'font_family' => "'Poppins', sans-serif",
                    'font_size' => '24px',
                    'font_weight' => '700',
                    'color' => '#ffffff',
                    'transform' => 'uppercase',
                    'spacing' => '1px',
                    'shadow' => [
                        'enabled' => false,
                        'color' => 'rgba(0,0,0,0.3)',
                        'x' => '1',
                        'y' => '1',
                        'blur' => '2'
                    ],
                    'hover' => [
                        'color' => '#20bf6b'
                    ]
                ],
                'container' => [
                    'background' => 'transparent',
                    'padding' => '0',
                    'margin' => '0',
                    'alignment' => 'flex-start',
                    'display' => 'flex',
                    'justify' => 'flex-start'
                ]
            ]),
            'logo_link' => json_encode([
                'url' => '/',
                'target' => '_self',
                'nofollow' => false,
                'title' => 'Home Page'
            ]),
            'logo_responsive' => json_encode([
                'mobile' => [
                    'type' => 'same',
                    'image' => [
                        'path' => '/uploads/logo-mobile.png',
                        'width' => '120px'
                    ],
                    'text' => [
                        'font_size' => '18px'
                    ]
                ]
            ]),
            'logo_tagline' => 'Quality Education for Everyone',
            'tagline_styles' => json_encode([
                'font_family' => "'Poppins', sans-serif",
                'font_size' => '14px',
                'font_weight' => '400',
                'color' => '#d1d5db',
                'style' => 'italic',
                'margin' => '0 0 0 12px',
                'display' => 'block'
            ]),
            'buttons' => json_encode([
                [
                    'text' => 'Enroll Now',
                    'url' => '/enroll',
                    'style' => 'primary',
                    'type' => 'filled',
                    'custom_styles' => [
                        'background_color' => '#20bf6b',
                        'text_color' => '#ffffff',
                        'border_color' => '#20bf6b',
                        'border_width' => '2px',
                        'border_style' => 'solid',
                        'border_radius' => '5px',
                        'padding_top' => '8px',
                        'padding_right' => '30px',
                        'padding_bottom' => '8px',
                        'padding_left' => '30px',
                        'font_size' => '0.95rem',
                        'font_weight' => '500',
                        'hover_background_color' => 'transparent',
                        'hover_text_color' => '#20bf6b',
                        'hover_border_color' => '#20bf6b',
                        'transition_duration' => '0.18s'
                    ]
                ],
                [
                    'text' => 'Contact us',
                    'url' => '/contact',
                    'style' => 'secondary',
                    'type' => 'filled',
                    'custom_styles' => [
                        'background_color' => '#ff7101',
                        'text_color' => '#ffffff',
                        'border_color' => '#ff7101',
                        'border_width' => '2px',
                        'border_style' => 'solid',
                        'border_radius' => '5px',
                        'padding_top' => '8px',
                        'padding_right' => '30px',
                        'padding_bottom' => '8px',
                        'padding_left' => '30px',
                        'font_size' => '0.95rem',
                        'font_weight' => '500',
                        'hover_background_color' => 'transparent',
                        'hover_text_color' => '#ff7101',
                        'hover_border_color' => '#ff7101',
                        'transition_duration' => '0.18s'
                    ]
                ],
                [
                    'text' => 'View Programs',
                    'url' => '/programs',
                    'style' => 'outlined',
                    'type' => 'outlined',
                    'custom_styles' => [
                        'background_color' => 'transparent',
                        'text_color' => '#ffffff',
                        'border_color' => '#ffffff',
                        'border_width' => '2px',
                        'border_style' => 'solid',
                        'border_radius' => '5px',
                        'padding_top' => '8px',
                        'padding_right' => '25px',
                        'padding_bottom' => '8px',
                        'padding_left' => '25px',
                        'font_size' => '0.9rem',
                        'font_weight' => '500',
                        'hover_background_color' => '#ffffff',
                        'hover_text_color' => '#102e4a',
                        'hover_border_color' => '#ffffff',
                        'transition_duration' => '0.3s'
                    ]
                ]
            ]),
            'nav_styles' => json_encode([
                'background' => '#102e4a',
                'padding' => '0 20px',
                'justifyContent' => 'space-between',
                'box_shadow' => '0 2px 10px rgba(0,0,0,0.1)'
            ]),
            'is_active' => true
        ]);

        $this->command->info('Default header section created successfully.');
    }
}
