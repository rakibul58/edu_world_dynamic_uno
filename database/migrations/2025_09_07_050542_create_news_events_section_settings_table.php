<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('news_events_section_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // 'main_settings'

            // Section header
            $table->string('section_title')->default('Latest News & Upcoming Events');
            $table->text('section_description');

            // Section header styling
            $table->string('section_title_color')->default('#102e4a');
            $table->string('section_title_font_size')->default('2.5rem');
            $table->string('section_title_font_weight')->default('700');
            $table->string('section_description_color')->default('#111111');
            $table->string('section_description_font_size')->default('1.1rem');
            $table->string('section_description_font_weight')->default('400');

            // Section background
            $table->string('section_background_color')->default('#ffffff');
            $table->string('section_background_image')->nullable();
            $table->string('section_padding')->default('80px 0');

            // Grid settings
            $table->string('grid_columns')->default('repeat(auto-fit, minmax(350px, 1fr))');
            $table->string('grid_gap')->default('30px');

            // Container settings
            $table->string('container_max_width')->default('1240px');
            $table->string('container_padding')->default('0 20px');

            // Header alignment and spacing
            $table->string('header_text_align')->default('center');
            $table->string('header_margin_bottom')->default('60px');

            // Animation settings
            $table->boolean('enable_animations')->default(true);
            $table->string('animation_duration')->default('0.6s');
            $table->string('animation_delay_increment')->default('0.1s');

            // Responsive breakpoints
            $table->string('mobile_breakpoint')->default('768px');
            $table->string('mobile_grid_columns')->default('1fr');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_events_section_settings');
    }
};
