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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_adjustment');
            $table->enum('lokasi_stok', ['gudang', 'kendaraan']);
            $table->foreignId('truck_id')->nullable()->constrained(); // Only if lokasi is kendaraan
            $table->integer('stok_sebelum');
            $table->integer('stok_setelah');
            $table->integer('selisih');
            $table->text('alasan_penyesuaian');
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
    }
};
