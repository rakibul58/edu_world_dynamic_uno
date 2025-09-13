<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryItemsTable extends Migration
{
    public function up()
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->enum('background_type', ['gradient', 'image', 'color'])->default('gradient');
            $table->string('gradient_start')->nullable();
            $table->string('gradient_end')->nullable();
            $table->string('gradient_direction')->default('135deg');
            $table->string('background_color')->nullable();
            $table->string('background_image')->nullable();
            $table->string('title_color')->default('#ffffff');
            $table->string('title_font_size')->default('1.5rem');
            $table->string('title_font_weight')->default('600');
            $table->string('description_color')->default('#ffffff');
            $table->string('description_font_size')->default('1rem');
            $table->string('item_height')->default('250px');
            $table->string('border_radius')->default('15px');
            $table->string('icon_size')->default('3rem');
            $table->string('hover_transform')->default('translateY(-5px) scale(1.02)');
            $table->string('hover_shadow')->default('0 15px 40px rgba(0, 0, 0, 0.2)');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gallery_items');
    }
}
