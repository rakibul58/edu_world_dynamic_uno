<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\HeroSlide;
use App\Models\Program;
use App\Models\Feature;
use App\Models\Testimonial;
use App\Models\GalleryItem;
use App\Models\Facility;
use App\Models\NewsEvent;
use App\Models\ContactInfo;
use App\Models\AcademicLevel;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function getSiteData()
    {
        return response()->json([
            'settings' => $this->getSiteSettings(),
            'heroSlides' => HeroSlide::active()->ordered()->get(),
            'programs' => Program::active()->ordered()->get(),
            'features' => Feature::active()->ordered()->get(),
            'testimonials' => Testimonial::active()->ordered()->get(),
            'gallery' => GalleryItem::active()->ordered()->get(),
            'facilities' => [
                'large' => Facility::active()->ordered()->byType('large')->first(),
                'items' => Facility::active()->ordered()->byType('item')->get()
            ],
            'news' => NewsEvent::active()->ordered()->byType('news')->take(3)->get(),
            'events' => NewsEvent::active()->ordered()->byType('event')->take(3)->get(),
            'contact' => ContactInfo::active()->ordered()->get(),
            'academicLevels' => AcademicLevel::active()->ordered()->get(),
        ]);
    }

    private function getSiteSettings()
    {
        $settings = SiteSetting::all()->pluck('value', 'key');

        // Default settings if not set
        $defaults = [
            // Colors
            'primary_color' => '#ff7101',
            'secondary_color' => '#102e4a',
            'accent_color' => '#20bf6b',
            'warning_color' => '#f7b731',
            'dark_color' => '#030811',
            'light_color' => '#f8fbfe',
            'white_color' => '#ffffff',
            'success_color' => '#20bf6b',

            // Typography
            'primary_font' => 'Rubik',
            'font_size_base' => '16px',
            'line_height_base' => '1.6',

            // Layout
            'border_radius_base' => '15px',
            'border_radius_small' => '5px',
            'border_radius_large' => '25px',
            'container_max_width' => '1240px',

            // Site Info
            'site_name' => 'Edu World',
            'site_tagline' => 'A Complete ERP System',
            'site_description' => 'Providing exceptional Islamic Cambridge education',

            // Contact
            'phone' => '+880 31 123 4567',
            'email' => 'info@eduworld.edu',
            'address' => '123 Education Street, Chattogram 4000, Bangladesh',

            // Images
            'logo' => '/images/logo.png',
            'favicon' => '/images/favicon.ico',
            'footer_logo' => '/images/logo.png',
        ];

        return array_merge($defaults, $settings->toArray());
    }

    public function getSettings()
    {
        return response()->json($this->getSiteSettings());
    }
}
