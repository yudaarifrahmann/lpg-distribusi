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
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->id();
            $table->timestamp('tanggal');
            $table->enum('jenis_mutasi', [
                'penebusan', 
                'distribusi_ke_truk', 
                'penjualan', 
                'retur_gudang', 
                'penyesuaian_stok'
            ]);
            $table->string('referensi'); // Nomor DO, Invoice, SJ, etc
            $table->string('lokasi_asal')->nullable(); // Gudang, Truck Plat No, Pangkalan
            $table->string('lokasi_tujuan')->nullable();
            $table->integer('stok_masuk')->default(0);
            $table->integer('stok_keluar')->default(0);
            $table->integer('stok_akhir');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            
            $table->index(['jenis_mutasi', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
