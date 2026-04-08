<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_kendaraan')->constrained('kendaraan', 'id_kendaraan')->onDelete('cascade');
            $table->foreignId('id_tarif')->constrained('tarif', 'id_tarif')->onDelete('cascade');
            $table->timestamp('waktu_masuk')->useCurrent();
            $table->timestamp('waktu_keluar')->nullable();
            $table->integer('durasi_jam')->nullable();
            $table->integer('durasi_menit')->nullable();
            $table->string('durasi')->nullable();
            $table->decimal('biaya_total', 10, 2)->nullable();
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->foreignId('id_area')->constrained('area_parkir', 'id_area')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
