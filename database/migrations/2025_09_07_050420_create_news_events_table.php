<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('news_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('date');
            $table->text('description');
            $table->string('link')->nullable();

            // Card styling
            $table->string('card_background_type')->default('gradient'); // gradient or image
            $table->string('icon')->nullable(); // emoji or icon for gradient type
            $table->string('image_url')->nullable(); // for image type
            $table->string('gradient_start')->default('#ff7101');
            $table->string('gradient_end')->default('#d35b00');
            $table->string('gradient_direction')->default('135deg');

            // Text styling
            $table->string('title_color')->default('#102e4a');
            $table->string('title_font_size')->default('1.3rem');
            $table->string('title_font_weight')->default('600');
            $table->string('description_color')->default('#111111');
            $table->string('description_font_size')->default('1rem');
            $table->string('description_font_weight')->default('400');
            $table->string('date_color')->default('#ff7101');
            $table->string('date_font_size')->default('0.9rem');
            $table->string('date_font_weight')->default('600');
            $table->string('link_color')->default('#ff7101');
            $table->string('link_hover_color')->default('#d35b00');
            $table->string('link_font_weight')->default('600');

            // Card settings
            $table->string('card_border_radius')->default('15px');
            $table->string('card_shadow')->default('0 5px 20px rgba(0, 0, 0, 0.08)');
            $table->string('card_hover_shadow')->default('0 15px 30px rgba(0, 0, 0, 0.15)');
            $table->string('card_hover_transform')->default('translateY(-10px)');
            $table->string('card_background_color')->default('#ffffff');

            // Status and ordering
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_events');
    }
};
