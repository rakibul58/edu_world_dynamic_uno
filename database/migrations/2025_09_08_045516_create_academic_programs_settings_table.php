<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicProgramsSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('academic_programs_settings', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->default('Academic Excellence at Every Level');
            $table->text('section_description');
            $table->string('section_title_color')->default('#102e4a');
            $table->string('section_title_font_size')->default('2.5rem');
            $table->string('section_title_font_weight')->default('700');
            $table->string('section_description_color')->default('#111111');
            $table->string('section_description_font_size')->default('1.1rem');
            $table->string('section_description_font_weight')->default('400');
            $table->string('section_background_color')->default('#f8fbfe');
            $table->string('section_padding')->default('80px 0');
            $table->string('header_text_align')->default('center');
            $table->string('header_margin_bottom')->default('60px');
            $table->string('container_max_width')->default('1240px');
            $table->string('container_padding')->default('0 20px');
            $table->string('programs_gap')->default('30px');
            $table->boolean('enable_animations')->default(true);
            $table->string('animation_duration')->default('0.6s');
            $table->string('animation_delay_increment')->default('0.1s');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_programs_settings');
    }
}
