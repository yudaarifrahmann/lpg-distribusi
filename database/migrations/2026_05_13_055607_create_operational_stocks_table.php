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
        Schema::create('stok_operasional', function (Blueprint $table) {
            $table->id();
            $table->string('lokasi_stok'); // Nama gudang atau Plat Nomor
            $table->integer('jumlah_tabung')->default(0);
            $table->enum('jenis_lokasi', ['gudang', 'kendaraan']);
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_operasional');
    }
};
