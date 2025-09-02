<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('about_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description_1');
            $table->text('description_2');
            $table->string('background_color')->default('#f8fbfe');
            $table->string('title_color')->default('#102e4a');
            $table->string('title_font_size')->default('2.6rem');
            $table->string('title_font_weight')->default('700');
            $table->string('text_color')->default('#111');
            $table->string('text_font_size')->default('1.3rem');
            $table->string('text_font_weight')->default('300');
            $table->string('image_path')->nullable();
            $table->string('gradient_start')->default('#ff7101');
            $table->string('gradient_end')->default('#102e4a');
            $table->string('gradient_direction')->default('135deg');
            $table->enum('right_side_type', ['image', 'gradient'])->default('gradient');
            $table->string('image_icon')->default('📚');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('about_sections');
    }
}
