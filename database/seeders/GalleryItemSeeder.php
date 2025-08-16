<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GalleryItem;

class GalleryItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'title' => 'Main Building',
                'icon' => '🏛️',
                'background_gradient_start' => '#ff7101',
                'background_gradient_end' => '#102e4a',
                'sort_order' => 1,
            ],
            [
                'title' => 'Library',
                'icon' => '📚',
                'background_gradient_start' => '#20bf6b',
                'background_gradient_end' => '#102e4a',
                'sort_order' => 2,
            ],
            [
                'title' => 'Science Labs',
                'icon' => '🔬',
                'background_gradient_start' => '#f7b731',
                'background_gradient_end' => '#102e4a',
                'sort_order' => 3,
            ],
            [
                'title' => 'Prayer Hall',
                'icon' => '🕌',
                'background_gradient_start' => '#8e44ad',
                'background_gradient_end' => '#102e4a',
                'sort_order' => 4,
            ],
            [
                'title' => 'Sports Complex',
                'icon' => '⚽',
                'background_gradient_start' => '#e74c3c',
                'background_gradient_end' => '#102e4a',
                'sort_order' => 5,
            ],
            [
                'title' => 'Arts Center',
                'icon' => '🎭',
                'background_gradient_start' => '#2ecc71',
                'background_gradient_end' => '#102e4a',
                'sort_order' => 6,
            ],
        ];

        foreach ($items as $item) {
            GalleryItem::create($item);
        }
    }
}
