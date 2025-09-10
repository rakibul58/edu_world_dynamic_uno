<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('facilities_sections', function (Blueprint $table) {
            $table->id();

            // Section Basic Info
            $table->string('title');
            $table->text('subtitle');
            $table->string('background_color', 7)->default('#ffffff');
            $table->boolean('is_active')->default(true);

            // Title Styling
            $table->string('title_color', 7)->default('#102e4a');
            $table->string('title_font_size', 10)->default('2.5rem');
            $table->string('title_font_weight', 3)->default('700');

            // Subtitle Styling
            $table->string('subtitle_color', 7)->default('#111111');
            $table->string('subtitle_font_size', 10)->default('1.1rem');
            $table->string('subtitle_font_weight', 3)->default('400');

            // Featured Facility (Left Side)
            $table->string('featured_title');
            $table->text('featured_description');
            $table->enum('featured_type', ['image', 'gradient'])->default('gradient');
            $table->string('featured_image_path')->nullable();
            $table->string('featured_gradient_start', 7)->default('#ff7101');
            $table->string('featured_gradient_end', 7)->default('#102e4a');
            $table->string('featured_gradient_direction')->default('135deg');
            $table->string('featured_tint_color', 7)->default('#000000');
            $table->decimal('featured_tint_opacity', 3, 2)->default(0.50);

            // Featured Text Styling
            $table->string('featured_text_color', 7)->default('#ffffff');
            $table->string('featured_title_font_size', 10)->default('2rem');
            $table->string('featured_desc_font_size', 10)->default('1.1rem');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facilities_sections');
    }
}
