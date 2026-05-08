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
        Schema::create('lpg_prices', function (Blueprint $table) {
            $table->id();
            $table->string('nama_harga');
            $table->decimal('harga', 12, 2);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lpg_prices');
    }
};
