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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengeluaran');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('expense_category_id')->constrained();
            $table->string('nama_pengeluaran');
            $table->decimal('nominal', 15, 2);
            $table->enum('metode_pembayaran', ['cash', 'transfer']);
            $table->text('keterangan')->nullable();
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
