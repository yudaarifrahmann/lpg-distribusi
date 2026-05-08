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
        Schema::create('retur_tabungs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_retur');
            $table->foreignId('surat_jalan_id')->constrained();
            $table->foreignId('truck_id')->constrained();
            $table->foreignId('driver_id')->constrained('drivers');
            $table->integer('jumlah_retur');
            $table->enum('kondisi_tabung', ['baik', 'rusak', 'bocor'])->default('baik');
            $table->text('keterangan')->nullable();
            $table->enum('status_retur', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_tabungs');
    }
};
