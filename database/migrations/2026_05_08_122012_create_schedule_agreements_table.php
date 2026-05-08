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
        Schema::create('schedule_agreements', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_sa');
            $table->integer('jumlah_do');
            $table->integer('jumlah_tabung');
            $table->text('keterangan')->nullable();
            $table->enum('status_sa', ['pending', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_agreements');
    }
};
