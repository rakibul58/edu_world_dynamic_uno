<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hero_sections', function (Blueprint $table) {
            $table->id();

            // Basic settings
            $table->boolean('is_active')->default(true);
            $table->string('section_name')->default('Hero Section');

            // Hero content
            $table->string('title')->nullable();
            $table->string('title_highlight')->nullable(); // For the colored part like "Edu World"
            $table->string('subtitle')->nullable();
            $table->text('tagline')->nullable();

            // Background settings
            $table->json('background_images')->nullable(); // Array of image paths
            $table->integer('slider_interval')->default(3500); // milliseconds
            $table->boolean('enable_slider')->default(true);
            $table->json('background_gradients')->nullable(); // Fallback gradients

            // CTA Buttons
            $table->json('cta_buttons')->nullable(); // Array of button objects

            // Styling
            $table->json('text_styles')->nullable(); // Title, subtitle, tagline styles
            $table->json('overlay_styles')->nullable(); // Overlay gradient and opacity
            $table->json('section_styles')->nullable(); // Min height, padding etc

            // Navigation settings
            $table->json('navigation')->nullable(); // Menu items and nav settings
            $table->boolean('show_navigation')->default(true);
            $table->json('nav_styles')->nullable(); // Navigation styling

            // Advanced settings
            $table->json('advanced_settings')->nullable(); // Animations, transitions, etc

            // SEO and meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_tags')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_sections');
    }
};
