<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AboutSection;
use App\Models\AboutStat;

class AboutSectionSeeder extends Seeder
{
    public function run()
    {
        $aboutSection = AboutSection::create([
            'title' => 'Excellence in Islamic Education',
            'description_1' => 'At Edu World, we bridge the gap between traditional Islamic values and modern academic excellence. Our unique approach combines the globally recognized Cambridge curriculum with comprehensive Islamic studies, creating well-rounded individuals prepared for both this world and the hereafter.',
            'description_2' => 'Founded on the principles of knowledge, wisdom, and character development, we nurture students to become confident, compassionate leaders who contribute positively to society while maintaining their Islamic identity.',
            'background_color' => '#f8fbfe',
            'title_color' => '#102e4a',
            'title_font_size' => '2.6rem',
            'title_font_weight' => '700',
            'text_color' => '#111',
            'text_font_size' => '1.3rem',
            'text_font_weight' => '300',
            'gradient_start' => '#ff7101',
            'gradient_end' => '#102e4a',
            'gradient_direction' => '135deg',
            'right_side_type' => 'gradient',
            'image_icon' => '📚',
            'is_active' => true
        ]);

        $stats = [
            ['value' => '500+', 'label' => 'Students'],
            ['value' => '50+', 'label' => 'Teachers'],
            ['value' => '95%', 'label' => 'Success Rate'],
            ['value' => '15+', 'label' => 'Years Experience'],
        ];

        foreach ($stats as $index => $stat) {
            AboutStat::create([
                'about_section_id' => $aboutSection->id,
                'value' => $stat['value'],
                'label' => $stat['label'],
                'number_color' => '#ff7101',
                'label_color' => '#111',
                'number_font_size' => '2rem',
                'label_font_size' => '0.9rem',
                'sort_order' => $index
            ]);
        }
    }
}
