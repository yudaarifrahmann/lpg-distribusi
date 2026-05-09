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
        Schema::table('penjualans', function (Blueprint $table) {
            $table->decimal('nominal_cash', 15, 2)->default(0)->after('total_penjualan');
            $table->decimal('nominal_transfer', 15, 2)->default(0)->after('nominal_cash');
            $table->enum('status_transfer', ['pending', 'verified', 'rejected'])->nullable()->after('status_pembayaran');
        });
        
        // Add 'split' to the ENUM values using raw SQL since doctrine/dbal might not be available
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('penjualans', function (Blueprint $table) {
                $table->string('metode_pembayaran')->change();
            });
        } else {
            DB::statement("ALTER TABLE penjualans MODIFY COLUMN metode_pembayaran ENUM('cash', 'transfer', 'utang', 'split') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualans', function (Blueprint $table) {
            $table->dropColumn(['nominal_cash', 'nominal_transfer', 'status_transfer']);
        });
        
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE penjualans MODIFY COLUMN metode_pembayaran ENUM('cash', 'transfer', 'utang') NOT NULL");
        }
    }
};
