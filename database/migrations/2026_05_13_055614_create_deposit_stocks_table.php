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
        Schema::create('stok_titipan', function (Blueprint $table) {
            $table->id();
            $table->string('pemilik_tabung');
            $table->integer('jumlah_tersedia')->default(0);
            $table->integer('jumlah_dipinjam')->default(0);
            $table->integer('total_tabung')->default(0);
            $table->foreignId('branch_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_titipan');
    }
};
