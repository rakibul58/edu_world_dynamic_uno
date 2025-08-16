<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('academic_levels', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('age_range');
            $table->string('icon');
            $table->json('subjects'); // array of subjects
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_levels');
    }
};
