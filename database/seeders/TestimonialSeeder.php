<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $testimonials = [
            [
                'content' => 'Edu World has been a blessing for our family. The way they seamlessly integrate Islamic values with academic excellence is truly remarkable. My daughter has grown not just academically but spiritually as well.',
                'author_name' => 'Sarah Ahmed',
                'author_title' => 'Parent of Year 10 Student',
                'author_initials' => 'SA',
                'sort_order' => 1,
            ],
            [
                'content' => 'The teachers at Edu World truly care about each student\'s success. The Islamic Studies program helped me understand my faith better while the Cambridge curriculum prepared me excellently for university.',
                'author_name' => 'Muhammad Hassan',
                'author_title' => 'Alumni - Cambridge University',
                'author_initials' => 'MH',
                'sort_order' => 2,
            ],
            [
                'content' => 'What sets Edu World apart is their holistic approach to education. They don\'t just teach subjects; they shape character and nurture the whole person. I\'m proud to be part of this community.',
                'author_name' => 'Fatima Khan',
                'author_title' => 'Current Student - Year 12',
                'author_initials' => 'FK',
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
