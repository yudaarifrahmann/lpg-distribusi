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
        Schema::create('stock_summaries', function (Blueprint $table) {
            $table->id();
            $table->integer('stok_saat_ini')->default(0);
            $table->timestamps();
        });
        
        // Initialize stock with 0
        DB::table('stock_summaries')->insert([
            'stok_saat_ini' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_summaries');
    }
};
