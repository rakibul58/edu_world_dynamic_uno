<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicProgram;
use App\Models\AcademicProgramsSettings;

class AcademicProgramsSeeder extends Seeder
{
    public function run()
    {
        // Create default settings
        AcademicProgramsSettings::create([
            'section_title' => 'Academic Excellence at Every Level',
            'section_description' => 'From foundation to advanced levels, our comprehensive curriculum ensures holistic development.',
            'section_title_color' => '#102e4a',
            'section_title_font_size' => '2.5rem',
            'section_title_font_weight' => '700',
            'section_description_color' => '#111111',
            'section_description_font_size' => '1.1rem',
            'section_description_font_weight' => '400',
            'section_background_color' => '#f8fbfe',
            'section_padding' => '80px 0',
            'header_text_align' => 'center',
            'header_margin_bottom' => '60px',
            'container_max_width' => '1240px',
            'container_padding' => '0 20px',
            'programs_gap' => '30px',
            'enable_animations' => true,
            'animation_duration' => '0.6s',
            'animation_delay_increment' => '0.1s',
            'is_active' => true,
        ]);

        // Create default programs
        $programs = [
            [
                'title' => 'Foundation (Years 1-6)',
                'age_range' => 'Ages 5-11',
                'icon' => '🌱',
                'items' => [
                    'Cambridge Primary Curriculum',
                    'Arabic Language & Islamic Studies',
                    'Quran Recitation & Basic Hifz',
                    'Creative Arts & Physical Education',
                ],
                'order' => 0,
                'is_active' => true,
            ],
            [
                'title' => 'Lower Secondary (Years 7-9)',
                'age_range' => 'Ages 11-14',
                'icon' => '🌿',
                'items' => [
                    'Cambridge Lower Secondary',
                    'Advanced Islamic Studies',
                    'STEM Focus with Islamic Ethics',
                    'Leadership Development Programs',
                ],
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Upper Secondary (Years 10-12)',
                'age_range' => 'Ages 14-18',
                'icon' => '🌳',
                'items' => [
                    'Cambridge IGCSE & A Levels',
                    'University Preparation',
                    'Research Projects',
                    'Community Service Programs',
                ],
                'order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($programs as $program) {
            AcademicProgram::create($program);
        }
    }
}
