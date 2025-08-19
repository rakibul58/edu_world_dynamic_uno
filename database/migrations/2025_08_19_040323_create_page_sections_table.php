<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page_name')->default('home');
            $table->string('section_key')->unique(); // e.g., 'hero_section', 'about_section'
            $table->string('section_name'); // Display name
            $table->text('description')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->integer('sort_order')->default(0);
            $table->json('settings')->nullable(); // Additional settings for each section
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_sections');
    }
}
