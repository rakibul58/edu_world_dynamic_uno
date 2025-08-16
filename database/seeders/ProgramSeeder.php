<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        $programs = [
            [
                'title' => 'Islamic Studies',
                'description' => 'The Islamic Studies program at Edu World aims to provide students with a comprehensive understanding of Islam, its principles, and their relevance to contemporary issues.',
                'slug' => 'islamic-studies',
                'icon' => '🕌',
                'background_color' => '#ff7101',
                'button_text' => 'Learn more',
                'button_url' => '#',
                'sort_order' => 1,
            ],
            [
                'title' => 'Cambridge Curriculum',
                'description' => 'We take pride in delivering a top-tier educational experience through the internationally recognized Cambridge Curriculum while incorporating Islamic values.',
                'slug' => 'cambridge-curriculum',
                'icon' => '🎓',
                'background_color' => '#20bf6b',
                'button_text' => 'Learn more',
                'button_url' => '#',
                'sort_order' => 2,
            ],
            [
                'title' => 'Hifz Program',
                'description' => 'Students are empowered to engage with the Quran through reading, memorization and understanding, along with fundamental Islamic knowledge and culture.',
                'slug' => 'hifz-program',
                'icon' => '📖',
                'background_color' => '#f7b731',
                'button_text' => 'Learn more',
                'button_url' => '#',
                'sort_order' => 3,
            ],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
