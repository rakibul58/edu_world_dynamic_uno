<?php

namespace Database\Seeders;

use App\Models\CtaSection;
use Illuminate\Database\Seeder;

class CtaSectionSeeder extends Seeder
{
    public function run()
    {
        CtaSection::create([
            'title' => 'Ready to Begin Your Journey?',
            'description' => 'Join our community of learners where Islamic values meet academic excellence. Apply today and give your child the education they deserve.',
            'title_color' => '#ffffff',
            'title_font_size' => '2.5rem',
            'title_font_weight' => '700',
            'description_color' => '#ffffff',
            'description_font_size' => '1.2rem',
            'description_font_weight' => '400',
            'background_type' => 'gradient',
            'background_color' => '#ff7101',
            'gradient_start' => '#ff7101',
            'gradient_end' => '#d35b00',
            'gradient_direction' => '135deg',
            'padding_top' => '80px',
            'padding_bottom' => '80px',
            'text_align' => 'center',
            'buttons' => [
                [
                    'text' => 'Apply Now',
                    'url' => '/apply',
                    'background_color' => '#ffffff',
                    'text_color' => '#ff7101',
                    'hover_background' => 'transparent',
                    'hover_text_color' => '#ffffff',
                    'border_color' => '#ffffff',
                    'hover_border_color' => '#ffffff',
                    'font_size' => '1.1rem',
                    'font_weight' => '600',
                    'padding' => '15px 35px',
                    'border_radius' => '8px',
                    'border_width' => '2px'
                ],
                [
                    'text' => 'Schedule a Visit',
                    'url' => '/visit',
                    'background_color' => 'transparent',
                    'text_color' => '#ffffff',
                    'hover_background' => '#ffffff',
                    'hover_text_color' => '#ff7101',
                    'border_color' => '#ffffff',
                    'hover_border_color' => '#ffffff',
                    'font_size' => '1.1rem',
                    'font_weight' => '600',
                    'padding' => '15px 35px',
                    'border_radius' => '8px',
                    'border_width' => '2px'
                ]
            ],
            'is_active' => true,
            'order' => 1
        ]);

        // Create another example section
        CtaSection::create([
            'title' => 'Transform Your Business Today',
            'description' => 'Discover innovative solutions that will take your business to the next level. Join thousands of satisfied customers worldwide.',
            'title_color' => '#ffffff',
            'title_font_size' => '2.8rem',
            'title_font_weight' => '800',
            'description_color' => '#ffffff',
            'description_font_size' => '1.1rem',
            'description_font_weight' => '400',
            'background_type' => 'gradient',
            'background_color' => '#3b82f6',
            'gradient_start' => '#3b82f6',
            'gradient_end' => '#1e40af',
            'gradient_direction' => '45deg',
            'padding_top' => '100px',
            'padding_bottom' => '100px',
            'text_align' => 'center',
            'buttons' => [
                [
                    'text' => 'Get Started Free',
                    'url' => '/signup',
                    'background_color' => '#ffffff',
                    'text_color' => '#3b82f6',
                    'hover_background' => 'transparent',
                    'hover_text_color' => '#ffffff',
                    'border_color' => '#ffffff',
                    'hover_border_color' => '#ffffff',
                    'font_size' => '1.2rem',
                    'font_weight' => '700',
                    'padding' => '18px 40px',
                    'border_radius' => '12px',
                    'border_width' => '2px'
                ],
                [
                    'text' => 'Watch Demo',
                    'url' => '/demo',
                    'background_color' => 'transparent',
                    'text_color' => '#ffffff',
                    'hover_background' => '#ffffff',
                    'hover_text_color' => '#3b82f6',
                    'border_color' => '#ffffff',
                    'hover_border_color' => '#ffffff',
                    'font_size' => '1.2rem',
                    'font_weight' => '600',
                    'padding' => '18px 40px',
                    'border_radius' => '12px',
                    'border_width' => '2px'
                ],
                [
                    'text' => 'Contact Sales',
                    'url' => '/contact',
                    'background_color' => 'rgba(255,255,255,0.1)',
                    'text_color' => '#ffffff',
                    'hover_background' => '#ffffff',
                    'hover_text_color' => '#3b82f6',
                    'border_color' => 'rgba(255,255,255,0.3)',
                    'hover_border_color' => '#ffffff',
                    'font_size' => '1.1rem',
                    'font_weight' => '500',
                    'padding' => '16px 32px',
                    'border_radius' => '8px',
                    'border_width' => '1px'
                ]
            ],
            'is_active' => false,
            'order' => 2
        ]);
    }
}
