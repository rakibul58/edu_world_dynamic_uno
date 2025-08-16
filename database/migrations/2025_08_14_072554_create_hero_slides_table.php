<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('background_image')->nullable();
            $table->string('background_color')->default('#ff7101');
            $table->string('cta_primary_text')->default('Apply Now');
            $table->string('cta_primary_url')->default('#');
            $table->string('cta_secondary_text')->default('Learn More');
            $table->string('cta_secondary_url')->default('#');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hero_slides');
    }
};
