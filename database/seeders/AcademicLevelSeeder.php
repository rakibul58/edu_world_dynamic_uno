<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicLevel;

class AcademicLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $academicLevels = [
            [
                'title' => 'Foundation (Years 1-6)',
                'age_range' => 'Ages 5-11',
                'icon' => '🌱',
                'subjects' => [
                    'Cambridge Primary Curriculum',
                    'Arabic Language & Islamic Studies',
                    'Quran Recitation & Basic Hifz',
                    'Creative Arts & Physical Education'
                ],
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Lower Secondary (Years 7-9)',
                'age_range' => 'Ages 11-14',
                'icon' => '🌿',
                'subjects' => [
                    'Cambridge Lower Secondary',
                    'Advanced Islamic Studies',
                    'STEM Focus with Islamic Ethics',
                    'Leadership Development Programs'
                ],
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Upper Secondary (Years 10-12)',
                'age_range' => 'Ages 14-18',
                'icon' => '🌳',
                'subjects' => [
                    'Cambridge IGCSE & A Levels',
                    'University Preparation',
                    'Research Projects',
                    'Community Service Programs'
                ],
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($academicLevels as $level) {
            AcademicLevel::create($level);
        }
    }
}
