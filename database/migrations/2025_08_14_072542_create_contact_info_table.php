<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contact_infos', function (Blueprint $table) { // Changed from 'contact_info' to 'contact_infos'
            $table->id();
            $table->string('type'); // address, phone, email, hours
            $table->string('icon');
            $table->string('title');
            $table->text('content');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_infos'); // Changed from 'contact_info' to 'contact_infos'
    }
};