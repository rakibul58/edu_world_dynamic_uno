<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('programs')->insert([
            [
                'title' => 'Islamic Studies',
                'description' => 'The Islamic Studies program at Edu World aims to provide students with a comprehensive understanding of Islam, its principles, and their relevance to contemporary issues.',
                'class_name' => 'islamic-studies',
                'border_color' => '#ff7101',
                'btn_color' => '#ff7101',
                'btn_border_color' => '#ff7101',
                'order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cambridge Curriculum',
                'description' => 'We take pride in delivering a top-tier educational experience through the internationally recognized Cambridge Curriculum while incorporating Islamic values.',
                'class_name' => 'cambridge',
                'border_color' => '#20bf6b',
                'btn_color' => '#20bf6b',
                'btn_border_color' => '#20bf6b',
                'order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Hifz Program',
                'description' => 'Students are empowered to engage with the Quran through reading, memorization and understanding, along with fundamental Islamic knowledge and culture.',
                'class_name' => 'hifz',
                'border_color' => '#f7b731',
                'btn_color' => '#f7b731',
                'btn_border_color' => '#f7b731',
                'order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('program_settings')->insert([
            ['key' => 'background', 'value' => '#102e4a', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'padding_top', 'value' => '60px', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'padding_bottom', 'value' => '80px', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'card_background', 'value' => 'linear-gradient(180deg, rgba(16, 46, 74, 0.35), rgba(3, 8, 17, 0.25))', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'border_radius', 'value' => '15px', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'border_width', 'value' => '10px', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'backdrop_filter', 'value' => 'blur(8px)', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'min_height', 'value' => '280px', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'programs_per_row', 'value' => '3', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}