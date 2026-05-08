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
        Schema::create('piutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained()->onDelete('cascade');
            $table->foreignId('pangkalan_id')->constrained();
            $table->decimal('nominal_piutang', 15, 2);
            $table->decimal('sisa_tagihan', 15, 2);
            $table->date('tanggal_jatuh_tempo');
            $table->enum('status_piutang', ['belum_bayar', 'mencicil', 'lunas'])->default('belum_bayar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piutangs');
    }
};
