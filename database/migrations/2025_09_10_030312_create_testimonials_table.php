<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialsTable extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('name');
            $table->string('role');
            $table->string('initials', 3)->nullable();
            $table->string('avatar_url')->nullable();
            $table->enum('avatar_type', ['initials', 'image'])->default('initials');

            // Avatar styling
            $table->string('avatar_bg_color')->default('#ff7101');
            $table->string('avatar_text_color')->default('#ffffff');

            // Text styling
            $table->string('text_color')->default('#ffffff');
            $table->string('name_color')->default('#ffffff');
            $table->string('role_color')->default('#ffffff');
            $table->string('text_font_size')->default('1.1rem');
            $table->string('name_font_size')->default('1rem');
            $table->string('role_font_size')->default('0.9rem');
            $table->string('text_font_weight')->default('400');
            $table->string('name_font_weight')->default('600');
            $table->string('role_font_weight')->default('400');

            // Card styling
            $table->string('card_background')->default('rgba(255, 255, 255, 0.1)');
            $table->string('card_border_color')->default('rgba(255, 255, 255, 0.2)');
            $table->string('card_border_radius')->default('15px');

            // Display settings
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
}
