<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE `vehicle_stock_histories` MODIFY `jenis_mutasi` ENUM('penebusan', 'distribusi_ke_truk', 'penjualan', 'retur_gudang', 'penyesuaian_stok') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE `vehicle_stock_histories` MODIFY `jenis_mutasi` ENUM('distribusi_ke_truk', 'penjualan', 'retur_gudang') NOT NULL");
    }
};
