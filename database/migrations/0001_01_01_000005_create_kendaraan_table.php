<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id('id_kendaraan');
            $table->string('plat_nomor')->unique();
            $table->string('warna');
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->unsignedBigInteger('id_Tarif');
            $table->unsignedBigInteger('id_user');

            $table->foreign('id_Tarif')->references('id_tarif')->on('tarif')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->timestamp('Created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};
