<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cta_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('title_color')->default('#ffffff');
            $table->string('title_font_size')->default('2.5rem');
            $table->string('title_font_weight')->default('700');
            $table->string('description_color')->default('#ffffff');
            $table->string('description_font_size')->default('1.2rem');
            $table->string('description_font_weight')->default('400');
            $table->enum('background_type', ['solid', 'gradient'])->default('gradient');
            $table->string('background_color')->default('#ff7101');
            $table->string('gradient_start')->default('#ff7101');
            $table->string('gradient_end')->default('#d35b00');
            $table->string('gradient_direction')->default('135deg');
            $table->string('padding_top')->default('80px');
            $table->string('padding_bottom')->default('80px');
            $table->enum('text_align', ['left', 'center', 'right'])->default('center');
            $table->json('buttons')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cta_sections');
    }
};
