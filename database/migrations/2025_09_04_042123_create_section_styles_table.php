<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('section_styles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_section_id')->constrained()->onDelete('cascade');
            $table->string('element_class');
            $table->json('styles'); // Store CSS properties as JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_styles');
    }
};
