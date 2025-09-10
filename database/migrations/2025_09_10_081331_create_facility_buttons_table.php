<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityButtonsTable extends Migration
{
    public function up()
    {
        Schema::create('facility_buttons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilities_section_id')->constrained()->onDelete('cascade');

            // Button Content
            $table->string('text');
            $table->string('url')->nullable();
            $table->integer('sort_order')->default(0);

            // Button Styling
            $table->string('background_color', 7)->default('#ffffff');
            $table->string('text_color', 7)->default('#ff7101');
            $table->string('hover_background_color', 7)->default('#ff7101');
            $table->string('hover_text_color', 7)->default('#ffffff');
            $table->string('border_radius', 10)->default('25px');
            $table->string('font_size', 10)->default('1rem');
            $table->string('font_weight', 3)->default('600');
            $table->string('padding', 20)->default('12px 30px');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility_buttons');
    }
}
