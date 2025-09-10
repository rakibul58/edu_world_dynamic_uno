<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityItemsTable extends Migration
{
    public function up()
    {
        Schema::create('facility_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facilities_section_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('title');
            $table->text('description');
            $table->string('icon');
            $table->integer('sort_order')->default(0);

            // Item Styling
            $table->string('background_color', 7)->default('#ffffff');
            $table->string('title_color', 7)->default('#102e4a');
            $table->string('description_color', 7)->default('#111111');
            $table->string('title_font_size', 10)->default('1.2rem');
            $table->string('description_font_size', 10)->default('0.95rem');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('facility_items');
    }
}
