<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('location_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('Visit Our Campus');
            $table->text('description');
            $table->string('background_color')->default('#f8fbfe');
            $table->integer('padding_top')->default(80);
            $table->integer('padding_bottom')->default(80);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('location_sections');
    }
};
