<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutStatsTable extends Migration
{
    public function up()
    {
        Schema::create('about_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('about_section_id');
            $table->string('value');
            $table->string('label');
            $table->string('number_color')->default('#ff7101');
            $table->string('label_color')->default('#111');
            $table->string('number_font_size')->default('2rem');
            $table->string('label_font_size')->default('0.9rem');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('about_section_id')->references('id')->on('about_sections')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('about_stats');
    }
}
