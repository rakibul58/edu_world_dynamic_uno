<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admission_processes', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Simple Admission Process');
            $table->text('description');

            // Section styling
            $table->string('background_color')->default('#102e4a');
            $table->string('text_color')->default('#ffffff');
            $table->string('padding_top')->default('80px');
            $table->string('padding_bottom')->default('80px');
            $table->enum('text_align', ['left', 'center', 'right'])->default('center');

            // Title styling
            $table->string('title_color')->default('#ffffff');
            $table->string('title_font_size')->default('2.5rem');
            $table->string('title_font_weight')->default('700');

            // Description styling
            $table->string('description_color')->default('#ffffff');
            $table->string('description_font_size')->default('1.1rem');
            $table->string('description_opacity')->default('0.9');

            // Step styling
            $table->string('step_number_bg_color')->default('#ff7101');
            $table->string('step_number_text_color')->default('#ffffff');
            $table->string('step_title_color')->default('#ffffff');
            $table->string('step_title_font_size')->default('1.3rem');
            $table->string('step_description_color')->default('#ffffff');
            $table->string('step_description_opacity')->default('0.9');

            // Layout
            $table->string('grid_min_width')->default('250px');
            $table->string('step_gap')->default('30px');

            $table->boolean('is_active')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admission_processes');
    }
};
