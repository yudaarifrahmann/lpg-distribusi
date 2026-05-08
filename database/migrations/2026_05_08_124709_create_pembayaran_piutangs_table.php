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
        Schema::create('pembayaran_piutangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('piutang_id')->constrained()->onDelete('cascade');
            $table->date('tanggal_pembayaran');
            $table->decimal('nominal_pembayaran', 15, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer']);
            $table->string('bukti_pembayaran')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained(); // tracking user who input
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_piutangs');
    }
};
