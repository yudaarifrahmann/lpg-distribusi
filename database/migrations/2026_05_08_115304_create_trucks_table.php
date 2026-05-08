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
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_truk');
            $table->string('nomor_polisi')->unique();
            $table->integer('kapasitas_tabung')->comment('dalam kg');
            $table->enum('status_kendaraan', ['aktif', 'service', 'nonaktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('status_kendaraan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
