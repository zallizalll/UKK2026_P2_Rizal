<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat', function (Blueprint $table) {
            $table->id('id_riwayat');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->foreignId('id_transaksi')->constrained('transaksi', 'id_transaksi')->onDelete('cascade');
            $table->string('plat_kendaraan');
            $table->string('jenis_kendaraan');
            $table->string('nama_area');
            $table->timestamp('waktu_masuk');
            $table->timestamp('waktu_keluar')->nullable();
            $table->string('durasi')->nullable();
            $table->decimal('biaya_total', 10, 2)->nullable();
            $table->decimal('uang_dibayar', 10, 2)->nullable();
            $table->decimal('kembalian', 10, 2)->nullable();
            $table->enum('status_pembayaran', ['lunas', 'belum'])->default('belum');
            $table->string('metode_pembayaran')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat');
    }
};
