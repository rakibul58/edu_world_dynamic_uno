<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeaturesItemsTable extends Migration
{
    public function up()
    {
        Schema::create('features_items', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 10)->default('🎓');
            $table->string('title');
            $table->text('description');
            $table->string('color', 7)->default('#ff7101');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);

            // Typography settings
            $table->string('title_color', 7)->default('#102e4a');
            $table->string('title_font_size', 20)->default('1.5rem');
            $table->string('title_font_weight', 10)->default('600');
            $table->string('description_color', 7)->default('#111111');
            $table->string('description_font_size', 20)->default('1rem');
            $table->string('description_font_weight', 10)->default('400');

            // Card styling
            $table->string('card_background_color', 7)->default('#ffffff');
            $table->string('card_border_radius', 20)->default('15px');
            $table->string('card_shadow')->default('0 10px 30px rgba(0, 0, 0, 0.1)');
            $table->string('card_hover_shadow')->default('0 20px 40px rgba(0, 0, 0, 0.15)');
            $table->string('card_hover_transform', 50)->default('translateY(-10px)');
            $table->string('card_border_top_width', 10)->default('4px');
            $table->string('card_border_top_style', 20)->default('solid');
            $table->string('card_padding', 20)->default('30px');

            // Icon styling
            $table->string('icon_size', 20)->default('80px');
            $table->string('icon_font_size', 20)->default('2rem');
            $table->string('icon_color', 7)->default('#ffffff');
            $table->string('icon_margin_bottom', 20)->default('20px');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('features_items');
    }
}
