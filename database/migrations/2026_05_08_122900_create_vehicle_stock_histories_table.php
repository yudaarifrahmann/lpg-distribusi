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
        Schema::create('vehicle_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('truck_id')->constrained();
            $table->enum('jenis_mutasi', ['penebusan', 'distribusi_ke_truk', 'penjualan', 'retur_gudang', 'penyesuaian_stok']);
            $table->string('referensi'); // e.g., nomor_surat_jalan, nomor_invoice, etc.
            $table->integer('stok_masuk')->default(0);
            $table->integer('stok_keluar')->default(0);
            $table->integer('stok_akhir');
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_stock_histories');
    }
};
