<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactInfo;

class ContactInfoSeeder extends Seeder
{
    public function run()
    {
        $contacts = [
            [
                'type' => 'address',
                'icon' => '📍',
                'title' => 'Address',
                'content' => "Edu World\n123 Education Street\nChattogram 4000, Bangladesh",
                'sort_order' => 1,
            ],
            [
                'type' => 'phone',
                'icon' => '📞',
                'title' => 'Phone',
                'content' => "+880 31 123 4567\n+880 1712 345 678",
                'sort_order' => 2,
            ],
            [
                'type' => 'email',
                'icon' => '✉️',
                'title' => 'Email',
                'content' => "info@eduworld.edu\nadmissions@eduworld.edu",
                'sort_order' => 3,
            ],
            [
                'type' => 'hours',
                'icon' => '🕒',
                'title' => 'Office Hours',
                'content' => "Saturday - Thursday\n8:00 AM - 4:00 PM",
                'sort_order' => 4,
            ],
        ];

        foreach ($contacts as $contact) {
            ContactInfo::create($contact);
        }
    }
}