<?php

namespace Database\Seeders;

use App\Models\HeaderSection;
use Illuminate\Database\Seeder;

class HeaderSectionSeeder extends Seeder
{
    public function run()
    {
        $headers = [
            [
                'name' => 'Default Header',
                'logo_type' => 'image',
                'logo_text' => 'EduSite',
                'logo_image_path' => '/public/images/logo.png',
                'logo_tagline' => 'Quality Education for All',
                'logo_link' => ['url' => '/', 'target' => '_self'], // Remove json_encode()
                'buttons' => [
                    [
                        'text' => 'Enroll Now',
                        'url' => '/enroll',
                        'style' => 'primary',
                        'type' => 'filled',
                        'custom_styles' => [
                            'background_color' => '#20bf6b',
                            'text_color' => '#ffffff',
                            'border_color' => '#20bf6b',
                            'border_radius' => '5px',
                            'hover_background_color' => '#1aa75b',
                            'hover_text_color' => '#ffffff'
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
                            'border_radius' => '5px',
                            'hover_background_color' => '#e06501',
                            'hover_text_color' => '#ffffff'
                        ]
                    ]
                ],
                'logo_styles' => [
                    'image' => ['width' => '180px', 'height' => 'auto'],
                    'text' => [
                        'font_family' => "'Poppins', sans-serif",
                        'font_size' => '24px',
                        'font_weight' => '700',
                        'color' => '#ffffff'
                    ]
                ],
                'tagline_styles' => [
                    'font_size' => '14px',
                    'color' => '#d1d5db'
                ],
                'nav_styles' => [
                    'background' => '#102e4a',
                    'padding' => '0 20px',
                    'box_shadow' => '0 2px 10px rgba(0,0,0,0.1)'
                ],
                'advanced_settings' => [
                    'enableAnimations' => true,
                    'stickyHeader' => false,
                    'hideOnMobile' => true,
                    'transitionDuration' => '0.3s',
                    'zIndex' => 1000
                ],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Alternative Header',
                'logo_type' => 'text',
                'logo_text' => 'Learning Hub',
                'logo_image_path' => null,
                'logo_tagline' => 'Expand Your Knowledge',
                'logo_link' => ['url' => '/', 'target' => '_self'],
                'buttons' => [
                    [
                        'text' => 'Get Started',
                        'url' => '/start',
                        'style' => 'primary',
                        'type' => 'filled',
                        'custom_styles' => [
                            'background_color' => '#3b82f6',
                            'text_color' => '#ffffff',
                            'border_color' => '#3b82f6',
                            'border_radius' => '5px',
                            'hover_background_color' => '#2563eb',
                            'hover_text_color' => '#ffffff'
                        ]
                    ]
                ],
                'logo_styles' => [
                    'image' => ['width' => '180px', 'height' => 'auto'],
                    'text' => [
                        'font_family' => "'Inter', sans-serif",
                        'font_size' => '28px',
                        'font_weight' => '800',
                        'color' => '#ffffff'
                    ]
                ],
                'tagline_styles' => [
                    'font_size' => '12px',
                    'color' => '#9ca3af'
                ],
                'nav_styles' => [
                    'background' => '#1e40af',
                    'padding' => '0 30px',
                    'box_shadow' => '0 4px 6px rgba(0,0,0,0.1)'
                ],
                'advanced_settings' => [
                    'enableAnimations' => true,
                    'stickyHeader' => true,
                    'hideOnMobile' => false,
                    'transitionDuration' => '0.3s',
                    'zIndex' => 1000
                ],
                'is_active' => false,
                'sort_order' => 2
            ]
        ];

        foreach ($headers as $header) {
            HeaderSection::create($header);
        }
    }
}
