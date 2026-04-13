<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['cash', 'qris'])->nullable()->after('biaya_total');
            $table->dropColumn('status');
            $table->enum('status', ['aktif', 'pending', 'selesai'])->default('aktif')->after('metode_pembayaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            //
        });
    }
};
