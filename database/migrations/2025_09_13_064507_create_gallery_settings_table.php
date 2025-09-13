<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGallerySettingsTable extends Migration
{
    public function up()
    {
        Schema::create('gallery_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->default('Explore Our Beautiful Campus');
            $table->text('section_description')->nullable();
            $table->string('section_title_color')->default('#102e4a');
            $table->string('section_title_font_size')->default('2.5rem');
            $table->string('section_title_font_weight')->default('700');
            $table->string('section_description_color')->default('#111111');
            $table->string('section_description_font_size')->default('1.1rem');
            $table->string('section_background_color')->default('#f8fbfe');
            $table->string('section_padding')->default('80px 0');
            $table->enum('header_text_align', ['left', 'center', 'right'])->default('center');
            $table->string('grid_columns')->default('repeat(auto-fit, minmax(350px, 1fr))');
            $table->string('grid_gap')->default('20px');
            $table->string('container_max_width')->default('1240px');
            $table->string('container_padding')->default('0 20px');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gallery_settings');
    }
}
