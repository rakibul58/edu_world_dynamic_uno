<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('footer_sections', function (Blueprint $table) {
            $table->id();
            $table->string('logo_path')->nullable();
            $table->text('description');
            $table->text('tagline');

            // Social Links JSON
            $table->json('social_links')->nullable();

            // Quick Links JSON
            $table->json('quick_links')->nullable();

            // Programs JSON
            $table->json('programs')->nullable();

            // Contact Info
            $table->string('address');
            $table->string('phone');
            $table->string('email');
            $table->string('working_hours');

            // Styling
            $table->string('background_color')->default('#030811');
            $table->string('text_color')->default('#fff');
            $table->string('accent_color')->default('#ff7101');
            $table->string('link_color')->default('#ccc');
            $table->string('link_hover_color')->default('#ff7101');

            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('footer_sections');
    }
};
