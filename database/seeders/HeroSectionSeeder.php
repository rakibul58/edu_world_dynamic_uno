<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSection;

class HeroSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default hero section
        HeroSection::create([
            'is_active' => true,
            'section_name' => 'Main Hero Section',
            'title' => 'Welcome To',
            'title_highlight' => 'Edu World',
            'subtitle' => 'A Complete ERP System',
            'tagline' => 'A complete system for educational institution management.',

            'background_images' => [
                'uploads/slides/1.jpg',
                'uploads/slides/2.jpg',
                'uploads/slides/3.jpg',
                'uploads/slides/4.jpg',
                'uploads/slides/5.jpg',
            ],

            'background_gradients' => [
                'linear-gradient(135deg, #ff7101, #102e4a)',
                'linear-gradient(135deg, #20bf6b, #102e4a)',
                'linear-gradient(135deg, #f7b731, #102e4a)',
                'linear-gradient(135deg, #102e4a, #030811)',
                'linear-gradient(135deg, #d35b00, #102e4a)'
            ],

            'slider_interval' => 3500,
            'enable_slider' => true,

            'cta_buttons' => [
                [
                    'text' => 'Apply now',
                    'url' => '#apply',
                    'target' => '_self',
                    'type' => 'primary',
                    'styles' => [
                        'background' => '#20bf6b',
                        'color' => '#fff',
                        'border' => '2px solid #20bf6b',
                        'borderRadius' => '6px',
                        'padding' => '10px 28px',
                        'fontWeight' => '500'
                    ],
                    'hover_styles' => [
                        'background' => 'transparent',
                        'color' => '#20bf6b',
                        'transform' => 'translateY(-2px)'
                    ]
                ],
                [
                    'text' => 'Learn more',
                    'url' => '#learn',
                    'target' => '_self',
                    'type' => 'secondary',
                    'styles' => [
                        'background' => 'transparent',
                        'color' => '#ff7101',
                        'border' => '2px solid #ff7101',
                        'borderRadius' => '6px',
                        'padding' => '10px 28px',
                        'fontWeight' => '500'
                    ],
                    'hover_styles' => [
                        'background' => '#ff7101',
                        'color' => '#fff',
                        'transform' => 'translateY(-2px)'
                    ]
                ]
            ],

            'text_styles' => [
                'title' => [
                    'fontSize' => '1.9rem',
                    'fontWeight' => '700',
                    'color' => '#ffffff',
                    'marginBottom' => '8px'
                ],
                'title_highlight' => [
                    'color' => '#ff7101'
                ],
                'subtitle' => [
                    'fontSize' => '1.5rem',
                    'fontWeight' => '600',
                    'color' => '#21bf6b',
                    'marginBottom' => '12px'
                ],
                'tagline' => [
                    'fontSize' => '2.2rem',
                    'fontWeight' => '800',
                    'color' => '#f7b731',
                    'lineHeight' => '1.05',
                    'marginBottom' => '18px'
                ]
            ],

            'overlay_styles' => [
                'background' => 'linear-gradient(135deg, rgba(6, 6, 7, 0.8), rgba(0, 30, 60, 0.7))',
                'opacity' => '1'
            ],

            'section_styles' => [
                'minHeight' => '80vh',
                'padding' => '110px 20px 80px',
                'display' => 'block',
                'overflow' => 'hidden'
            ],

            'navigation' => [
                'logo' => [
                    'type' => 'image',
                    'image_path' => 'uploads/image/logo.png',
                    'text' => 'Edu World',
                    'url' => '#',
                    'alt' => 'Logo'
                ],
                'menu_items' => [
                    [
                        'title' => 'Home',
                        'url' => '#',
                        'sub_items' => [],
                        'target' => '_self'
                    ],
                    [
                        'title' => 'About Us',
                        'url' => '#',
                        'sub_items' => [
                            ['text' => 'Our Story', 'url' => '#story'],
                            ['text' => 'Mission & Vision', 'url' => '#mission']
                        ],
                        'target' => '_self'
                    ],
                    [
                        'title' => 'Admission',
                        'url' => '#',
                        'sub_items' => [
                            ['text' => 'Apply Now', 'url' => '#apply'],
                            ['text' => 'Requirements', 'url' => '#requirements']
                        ],
                        'target' => '_self'
                    ],
                    [
                        'title' => 'Campus',
                        'url' => '#',
                        'sub_items' => [
                            ['text' => 'Facilities', 'url' => '#facilities'],
                            ['text' => 'Virtual Tour', 'url' => '#tour']
                        ],
                        'target' => '_self'
                    ],
                    [
                        'title' => 'IA Life',
                        'url' => '#',
                        'sub_items' => [
                            ['text' => 'Student Life', 'url' => '#student-life'],
                            ['text' => 'Events', 'url' => '#events']
                        ],
                        'target' => '_self'
                    ],
                    [
                        'title' => 'Academics',
                        'url' => '#',
                        'sub_items' => [
                            ['text' => 'Curriculum', 'url' => '#curriculum'],
                            ['text' => 'Programs', 'url' => '#programs']
                        ],
                        'target' => '_self'
                    ],
                    [
                        'title' => 'Information',
                        'url' => '#',
                        'sub_items' => [
                            ['text' => 'News', 'url' => '#news'],
                            ['text' => 'Resources', 'url' => '#resources']
                        ],
                        'target' => '_self'
                    ],
                    [
                        'title' => 'More',
                        'url' => '#',
                        'sub_items' => [
                            ['text' => 'Contact', 'url' => '#contact'],
                            ['text' => 'Gallery', 'url' => '#gallery']
                        ],
                        'target' => '_self'
                    ]
                ]
            ],

            'show_navigation' => true,

            'nav_styles' => [
                'background' => 'transparent',
                'scrolled_background' => 'rgba(0, 30, 60, 0.95)',
                'link_color' => '#fff',
                'link_hover_color' => '#d35b00',
                'dropdown_background' => '#fff',
                'dropdown_border_color' => '#ff7101',
                'mobile_background' => 'rgba(0, 30, 60, 0.95)'
            ],

            'advanced_settings' => [
                'enable_animations' => true,
                'transition_duration' => '1s',
                'enable_zoom_effect' => true,
                'zoom_duration' => '8s',
                'mobile_responsive' => true,
                'lazy_load_images' => true,
                'scroll_behavior' => 'smooth',
                'preload_images' => true
            ],

            'meta_title' => 'Edu World - Complete ERP System for Educational Institutions',
            'meta_description' => 'A comprehensive educational management system designed for modern institutions. Streamline admissions, academics, and campus life with Edu World.',
            'meta_tags' => [
                'education',
                'erp',
                'school management',
                'educational institution',
                'student management'
            ]
        ]);

        // Create alternative hero section (inactive)
        HeroSection::create([
            'is_active' => false,
            'section_name' => 'Alternative Hero Section',
            'title' => 'Transform Your',
            'title_highlight' => 'Education',
            'subtitle' => 'Modern Learning Solutions',
            'tagline' => 'Empowering students and educators with cutting-edge technology.',

            'background_images' => [
                'uploads/slides/alt1.jpg',
                'uploads/slides/alt2.jpg',
                'uploads/slides/alt3.jpg',
            ],

            'background_gradients' => [
                'linear-gradient(135deg, #667eea, #764ba2)',
                'linear-gradient(135deg, #f093fb, #f5576c)',
                'linear-gradient(135deg, #4facfe, #00f2fe)'
            ],

            'slider_interval' => 4000,
            'enable_slider' => true,

            'cta_buttons' => [
                [
                    'text' => 'Get Started',
                    'url' => '#start',
                    'target' => '_self',
                    'type' => 'primary',
                    'styles' => [
                        'background' => '#667eea',
                        'color' => '#fff',
                        'border' => '2px solid #667eea',
                        'borderRadius' => '8px',
                        'padding' => '12px 30px',
                        'fontWeight' => '600'
                    ],
                    'hover_styles' => [
                        'background' => 'transparent',
                        'color' => '#667eea',
                        'transform' => 'translateY(-3px)'
                    ]
                ],
                [
                    'text' => 'Watch Demo',
                    'url' => '#demo',
                    'target' => '_blank',
                    'type' => 'secondary',
                    'styles' => [
                        'background' => 'transparent',
                        'color' => '#f5576c',
                        'border' => '2px solid #f5576c',
                        'borderRadius' => '8px',
                        'padding' => '12px 30px',
                        'fontWeight' => '600'
                    ],
                    'hover_styles' => [
                        'background' => '#f5576c',
                        'color' => '#fff',
                        'transform' => 'translateY(-3px)'
                    ]
                ]
            ],

            'text_styles' => [
                'title' => [
                    'fontSize' => '2.1rem',
                    'fontWeight' => '800',
                    'color' => '#ffffff',
                    'marginBottom' => '10px'
                ],
                'title_highlight' => [
                    'color' => '#f5576c'
                ],
                'subtitle' => [
                    'fontSize' => '1.4rem',
                    'fontWeight' => '500',
                    'color' => '#4facfe',
                    'marginBottom' => '15px'
                ],
                'tagline' => [
                    'fontSize' => '2rem',
                    'fontWeight' => '700',
                    'color' => '#ffffff',
                    'lineHeight' => '1.1',
                    'marginBottom' => '20px'
                ]
            ],

            'show_navigation' => false, // Different nav setup
            'meta_title' => 'Transform Education - Modern Learning Solutions',
            'meta_description' => 'Revolutionary educational technology solutions for the digital age.'
        ]);
    }
}
