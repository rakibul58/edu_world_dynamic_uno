<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('header_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('logo_type', ['image', 'text'])->default('text');
            $table->string('logo_text')->nullable();
            $table->string('logo_image_path')->nullable();
            $table->string('logo_tagline')->nullable();
            $table->json('logo_link')->nullable();
            $table->json('buttons')->nullable();
            $table->json('logo_styles')->nullable();
            $table->json('tagline_styles')->nullable();
            $table->json('nav_styles')->nullable();
            $table->json('advanced_settings')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('header_sections');
    }
}
