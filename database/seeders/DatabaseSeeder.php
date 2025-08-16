<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            SiteSettingSeeder::class,
            HeroSlideSeeder::class,
            ProgramSeeder::class,
            FeatureSeeder::class,
            TestimonialSeeder::class,
            GalleryItemSeeder::class,
            FacilitySeeder::class,
            NewsEventSeeder::class,
            ContactInfoSeeder::class,
            AcademicLevelSeeder::class,
        ]);
    }
}
