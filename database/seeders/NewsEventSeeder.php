<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsEvent;
use Carbon\Carbon;

class NewsEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $newsEvents = [
            [
                'title' => 'Science Fair Winners Announced',
                'excerpt' => 'Our students excel in the regional science competition with innovative projects combining Islamic principles and modern science.',
                'content' => 'We are proud to announce that our students have achieved remarkable success in the regional science competition. Their innovative projects demonstrated how Islamic principles can be seamlessly integrated with modern scientific research, showcasing the unique educational approach of Edu World. The winning projects covered topics ranging from sustainable energy solutions inspired by Islamic environmental ethics to medical research that considers Islamic bioethics. This achievement reflects our commitment to nurturing students who excel academically while maintaining their Islamic values.',
                'slug' => 'science-fair-winners-announced',
                'icon' => '🏆',
                'background_gradient_start' => '#ff7101',
                'background_gradient_end' => '#d35b00',
                'event_date' => Carbon::parse('2025-03-15'),
                'type' => 'news',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Ramadan Program Launch',
                'excerpt' => 'Special Ramadan activities including Tarawih prayers, community iftar, and Quran recitation competitions.',
                'content' => 'As we approach the blessed month of Ramadan, Edu World is excited to launch our comprehensive Ramadan program. This year\'s activities include daily Tarawih prayers, community iftar gatherings, Quran recitation competitions, and special Islamic studies workshops. Our program is designed to deepen students\' understanding of Islamic values while fostering a sense of community and spiritual growth. Parents and community members are invited to join us for these meaningful activities that strengthen our bonds as a Muslim educational community.',
                'slug' => 'ramadan-program-launch',
                'icon' => '📖',
                'background_gradient_start' => '#20bf6b',
                'background_gradient_end' => '#27ae60',
                'event_date' => Carbon::parse('2025-03-20'),
                'type' => 'event',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Graduation Ceremony 2025',
                'excerpt' => 'Celebrating our graduates as they move on to prestigious universities around the world.',
                'content' => 'Join us as we celebrate the achievements of our graduating class of 2025. This year\'s graduates have been accepted to prestigious universities around the world, including Cambridge University, Oxford University, and leading institutions in North America, Australia, and the Middle East. Our graduation ceremony will recognize not only their academic excellence but also their character development and commitment to Islamic values. The event will feature speeches from distinguished alumni, presentation of awards, and a celebration of the bright futures that await our graduates.',
                'slug' => 'graduation-ceremony-2025',
                'icon' => '🎓',
                'background_gradient_start' => '#f7b731',
                'background_gradient_end' => '#f39c12',
                'event_date' => Carbon::parse('2025-04-05'),
                'type' => 'event',
                'sort_order' => 3,
                'is_active' => true,
            ],
            // Additional news items
            [
                'title' => 'New Cambridge Curriculum Implementation',
                'excerpt' => 'Successfully implementing the latest Cambridge curriculum updates across all grade levels.',
                'content' => 'Edu World has successfully implemented the latest Cambridge curriculum updates across all grade levels. Our teaching staff has completed intensive training to ensure seamless delivery of the enhanced curriculum that maintains our high academic standards while preserving our Islamic educational values.',
                'slug' => 'new-cambridge-curriculum-implementation',
                'icon' => '📚',
                'background_gradient_start' => '#8e44ad',
                'background_gradient_end' => '#9b59b6',
                'event_date' => Carbon::parse('2025-02-15'),
                'type' => 'news',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Annual Sports Day',
                'excerpt' => 'Join us for our annual sports day featuring competitions, team building activities, and community fun.',
                'content' => 'Our annual sports day brings together students, parents, and teachers for a day of healthy competition and community building. The event features various sports competitions, team building activities, and opportunities for our school community to come together in celebration of physical fitness and sportsmanship.',
                'slug' => 'annual-sports-day',
                'icon' => '⚽',
                'background_gradient_start' => '#e74c3c',
                'background_gradient_end' => '#c0392b',
                'event_date' => Carbon::parse('2025-05-10'),
                'type' => 'event',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($newsEvents as $newsEvent) {
            NewsEvent::create($newsEvent);
        }
    }
}
