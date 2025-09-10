<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialSectionSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('testimonial_section_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('What Our Community Says');
            $table->text('subtitle');
            $table->string('background_color')->default('#102e4a');
            $table->string('title_color')->default('#ffffff');
            $table->string('text_color')->default('#ffffff');
            $table->string('card_background')->default('rgba(255, 255, 255, 0.1)');
            $table->string('card_border_color')->default('rgba(255, 255, 255, 0.2)');
            $table->string('avatar_background')->default('#ff7101');
            $table->string('animation_style')->default('fadeInUp');
            $table->integer('animation_duration')->default(600);
            $table->string('columns')->default('auto');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimonial_section_settings');
    }
}
