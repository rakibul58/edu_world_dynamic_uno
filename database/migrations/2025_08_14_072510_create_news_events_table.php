<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('news_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('excerpt');
            $table->text('content')->nullable();
            $table->string('slug')->unique();
            $table->string('icon');
            $table->string('background_gradient_start')->default('#ff7101');
            $table->string('background_gradient_end')->default('#102e4a');
            $table->string('featured_image')->nullable();
            $table->datetime('event_date')->nullable();
            $table->string('type')->default('news'); // news, event
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('news_events');
    }
};
