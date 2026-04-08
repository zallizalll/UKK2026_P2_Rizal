<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tarif', function (Blueprint $table) {
            $table->id('id_tarif');
            $table->string('jenis_kendaraan');
            $table->decimal('tarif_per_jam', 10, 2);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('tarif');
    }
};
