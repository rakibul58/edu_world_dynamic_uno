<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LocationSection;
use App\Models\ContactCard;
use App\Models\LocationMap;
use App\Models\SectionStyle;

class LocationSectionSeeder extends Seeder
{
    public function run()
    {
        // Create location section
        $section = LocationSection::create([
            'title' => 'Visit Our Campus',
            'description' => 'Located in the heart of Chattogram, our campus is easily accessible and provides a serene environment for learning.',
            'background_color' => '#f8fbfe',
            'padding_top' => 80,
            'padding_bottom' => 80,
            'is_active' => true
        ]);

        // Create contact cards
        $contacts = [
            [
                'icon' => '📍',
                'title' => 'Address',
                'content' => 'Edu World<br />123 Education Street<br />Chattogram 4000, Bangladesh',
                'order' => 1
            ],
            [
                'icon' => '📞',
                'title' => 'Phone',
                'content' => '+880 31 123 4567<br />+880 1712 345 678',
                'order' => 2
            ],
            [
                'icon' => '✉️',
                'title' => 'Email',
                'content' => 'info@eduworld.edu<br />admissions@eduworld.edu',
                'order' => 3
            ],
            [
                'icon' => '🕒',
                'title' => 'Office Hours',
                'content' => 'Saturday - Thursday<br />8:00 AM - 4:00 PM',
                'order' => 4
            ]
        ];

        foreach ($contacts as $contact) {
            ContactCard::create([
                'location_section_id' => $section->id,
                ...$contact,
                'is_active' => true
            ]);
        }

        // Create location map
        LocationMap::create([
            'location_section_id' => $section->id,
            'embed_url' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3079.6418820516014!2d91.80977059999998!3d22.360993000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30acd8909da98ea1%3A0x3d9b8053516f015c!2sThe%20world%20IT!5e1!3m2!1sen!2sbd!4v1755059269124!5m2!1sen!2sbd',
            'width' => 600,
            'height' => 450,
            'border_radius' => '15px',
            'is_active' => true
        ]);

        // Create section styles
        $styles = [
            'section-header' => [
                'font-size' => '2.5rem',
                'color' => '#102e4a',
                'margin-bottom' => '15px',
                'font-weight' => '700'
            ],
            'contact-card' => [
                'background' => '#fff',
                'padding' => '25px',
                'border-radius' => '15px',
                'box-shadow' => '0 5px 20px rgba(0, 0, 0, 0.08)'
            ],
            'contact-card-hover' => [
                'transform' => 'translateY(-5px)',
                'box-shadow' => '0 15px 30px rgba(0, 0, 0, 0.15)'
            ],
            'map-container' => [
                'border-radius' => '15px',
                'box-shadow' => '0 10px 30px rgba(0, 0, 0, 0.1)'
            ]
        ];

        foreach ($styles as $className => $styleData) {
            SectionStyle::create([
                'location_section_id' => $section->id,
                'element_class' => $className,
                'styles' => $styleData
            ]);
        }
    }
}
