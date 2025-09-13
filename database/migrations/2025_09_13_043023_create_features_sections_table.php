<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('features_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->default('Why Choose Edu World?');
            $table->text('section_description');
            $table->string('section_title_color', 7)->default('#102e4a');
            $table->string('section_title_font_size', 20)->default('2.5rem');
            $table->string('section_title_font_weight', 10)->default('700');
            $table->string('section_description_color', 7)->default('#111111');
            $table->string('section_description_font_size', 20)->default('1.1rem');
            $table->string('section_description_font_weight', 10)->default('400');
            $table->string('section_background_color', 7)->default('#ffffff');
            $table->string('section_padding', 20)->default('80px 0');
            $table->enum('header_text_align', ['left', 'center', 'right'])->default('center');
            $table->string('header_margin_bottom', 20)->default('60px');
            $table->string('grid_columns')->default('repeat(auto-fit, minmax(300px, 1fr))');
            $table->string('grid_gap', 20)->default('30px');
            $table->string('container_max_width', 20)->default('1240px');
            $table->string('container_padding', 20)->default('0 20px');
            $table->boolean('enable_animations')->default(true);
            $table->string('animation_duration', 10)->default('0.6s');
            $table->string('animation_delay_increment', 10)->default('0.1s');
            $table->string('mobile_breakpoint', 20)->default('768px');
            $table->string('mobile_grid_columns')->default('1fr');
            $table->string('section_background_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('features_sections');
    }
}
