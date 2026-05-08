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
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat_jalan')->unique();
            $table->date('tanggal_berangkat');
            $table->foreignId('penebusan_id')->constrained()->onDelete('cascade');
            $table->foreignId('truck_id')->constrained();
            $table->foreignId('driver_id')->comment('Supir')->constrained('drivers');
            $table->foreignId('knek_id')->nullable()->comment('Knek')->constrained('drivers');
            $table->integer('jumlah_tabung');
            $table->enum('status_perjalanan', ['persiapan', 'berangkat', 'selesai', 'retur'])->default('persiapan');
            $table->text('catatan')->nullable();
            $table->string('foto_surat_jalan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};
