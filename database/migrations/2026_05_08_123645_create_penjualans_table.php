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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->date('tanggal_penjualan');
            $table->foreignId('surat_jalan_id')->constrained();
            $table->foreignId('truck_id')->constrained();
            $table->foreignId('driver_id')->constrained('drivers');
            $table->foreignId('pangkalan_id')->constrained();
            $table->foreignId('lpg_price_id')->constrained();
            $table->integer('jumlah_tabung');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_penjualan', 15, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'utang']);
            $table->enum('status_pembayaran', ['lunas', 'belum_lunas', 'cicilan']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
