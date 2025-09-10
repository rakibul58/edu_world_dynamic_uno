<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\TestimonialSectionSettings;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        // Create section settings
        TestimonialSectionSettings::create([
            'title' => 'What Our Community Says',
            'subtitle' => 'Hear from parents, students, and graduates about their transformative experience at Edu World.',
            'background_color' => '#102e4a',
            'title_color' => '#ffffff',
            'text_color' => '#ffffff',
            'card_background' => 'rgba(255, 255, 255, 0.1)',
            'card_border_color' => 'rgba(255, 255, 255, 0.2)',
            'avatar_background' => '#ff7101',
            'animation_style' => 'fadeInUp',
            'animation_duration' => 600,
            'columns' => 'auto'
        ]);

        // Create sample testimonials
        $testimonials = [
            [
                'text' => 'Edu World has been a blessing for our family. The way they seamlessly integrate Islamic values with academic excellence is truly remarkable. My daughter has grown not just academically but spiritually as well.',
                'name' => 'Sarah Ahmed',
                'role' => 'Parent of Year 10 Student',
                'initials' => 'SA',
                'avatar_type' => 'initials',
                'order' => 1
            ],
            [
                'text' => 'The teachers at Edu World truly care about each student\'s success. The Islamic Studies program helped me understand my faith better while the Cambridge curriculum prepared me excellently for university.',
                'name' => 'Muhammad Hassan',
                'role' => 'Alumni - Cambridge University',
                'initials' => 'MH',
                'avatar_type' => 'initials',
                'order' => 2
            ],
            [
                'text' => 'What sets Edu World apart is their holistic approach to education. They don\'t just teach subjects; they shape character and nurture the whole person. I\'m proud to be part of this community.',
                'name' => 'Fatima Khan',
                'role' => 'Current Student - Year 12',
                'initials' => 'FK',
                'avatar_type' => 'initials',
                'order' => 3
            ]
        ];

        foreach ($testimonials as $testimonialData) {
            Testimonial::create($testimonialData);
        }
    }
}
