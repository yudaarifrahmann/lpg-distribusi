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
        Schema::create('penebusans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_do')->unique();
            $table->date('tanggal_penebusan');
            $table->foreignId('schedule_agreement_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah_tabung');
            $table->decimal('harga_per_do', 15, 2);
            $table->decimal('total_penebusan', 15, 2);
            $table->foreignId('truck_id')->constrained();
            $table->foreignId('driver_id')->constrained();
            $table->string('foto_nota')->nullable();
            $table->enum('status_penebusan', ['proses', 'berhasil', 'gagal'])->default('berhasil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penebusans');
    }
};
