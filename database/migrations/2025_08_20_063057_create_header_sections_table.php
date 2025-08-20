<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('header_sections', function (Blueprint $table) {
            $table->id();
            $table->string('logo_type')->default('image');
            $table->string('logo_image_path')->nullable();
            $table->string('logo_text')->nullable();
            $table->json('logo_text_style')->nullable();
            $table->json('logo_styles')->nullable();
            $table->json('logo_link')->nullable();
            $table->json('logo_responsive')->nullable();
            $table->string('logo_tagline')->nullable();
            $table->json('tagline_styles')->nullable();
            $table->json('buttons')->nullable();
            $table->json('nav_styles')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('header_sections');
    }
};
