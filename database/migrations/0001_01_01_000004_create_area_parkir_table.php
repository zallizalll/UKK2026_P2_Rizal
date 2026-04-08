<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('area_parkir', function (Blueprint $table) {
            $table->id('id_area');
            $table->string('nama_area');
            $table->integer('kapasitas');
            $table->integer('terisi')->default(0);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('area_parkir');
    }
};
