<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_section_id')->constrained()->onDelete('cascade');
            $table->string('icon');
            $table->string('title');
            $table->text('content');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_cards');
    }
};
