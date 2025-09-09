<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicProgramsTable extends Migration
{
    public function up()
    {
        Schema::create('academic_programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('age_range');
            $table->string('icon')->default('🎓');
            $table->json('items');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);

            // Icon styling
            $table->string('icon_background_color')->default('#ff7101');
            $table->string('icon_color')->default('#ffffff');
            $table->string('icon_size')->default('2.5rem');
            $table->string('icon_width')->default('60px');
            $table->string('icon_height')->default('60px');
            $table->string('icon_border_radius')->default('50%');

            // Title styling
            $table->string('title_color')->default('#102e4a');
            $table->string('title_font_size')->default('1.5rem');
            $table->string('title_font_weight')->default('600');
            $table->string('title_margin_bottom')->default('5px');

            // Age range styling
            $table->string('age_range_color')->default('#ff7101');
            $table->string('age_range_font_size')->default('0.9rem');
            $table->string('age_range_font_weight')->default('600');

            // Items styling
            $table->string('items_color')->default('#111111');
            $table->string('items_font_size')->default('1rem');
            $table->string('items_font_weight')->default('400');
            $table->string('items_padding')->default('8px 0');
            $table->string('items_check_color')->default('#20bf6b');
            $table->string('items_padding_left')->default('25px');

            // Card styling
            $table->string('card_background_color')->default('#ffffff');
            $table->string('card_border_radius')->default('15px');
            $table->string('card_padding')->default('30px');
            $table->string('card_shadow')->default('0 5px 20px rgba(0, 0, 0, 0.08)');
            $table->string('card_hover_shadow')->default('0 15px 30px rgba(0, 0, 0, 0.15)');
            $table->string('card_hover_transform')->default('translateY(-5px)');
            $table->string('header_gap')->default('20px');
            $table->string('content_margin_top')->default('20px');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_programs');
    }
}
