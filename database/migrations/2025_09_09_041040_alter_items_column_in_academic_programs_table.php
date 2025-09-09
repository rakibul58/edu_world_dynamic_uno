<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('academic_programs', function (Blueprint $table) {
            $table->json('items')->change(); // force JSON type
        });
    }

    public function down()
    {
        Schema::table('academic_programs', function (Blueprint $table) {
            $table->text('items')->change(); // rollback
        });
    }
};
