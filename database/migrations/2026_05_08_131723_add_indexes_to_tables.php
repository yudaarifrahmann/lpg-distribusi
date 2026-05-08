<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->index('tanggal_penjualan');
            $table->index('status_pembayaran');
            $table->index('metode_pembayaran');
        });

        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->index('tanggal_berangkat');
            $table->index('status_perjalanan');
        });

        Schema::table('stock_mutations', function (Blueprint $table) {
            $table->index('tanggal');
            $table->index('jenis_mutasi');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->index('tanggal_pengeluaran');
            $table->index('status_verifikasi');
        });

        Schema::table('piutangs', function (Blueprint $table) {
            $table->index('status_piutang');
            $table->index('tanggal_jatuh_tempo');
        });
    }

    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropIndex(['tanggal_penjualan']);
            $table->dropIndex(['status_pembayaran']);
            $table->dropIndex(['metode_pembayaran']);
        });
        
        // ... and so on for others if needed for rollback
    }
};
