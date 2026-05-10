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
        $tables = [
            'users', 'pangkalans', 'trucks', 'drivers', 'surat_jalans', 'penjualans', 'piutangs', 'expenses', 'penebusans', 'retur_tabungs', 'schedule_agreements', 'stock_summaries', 'stock_histories', 'vehicle_stocks', 'vehicle_stock_histories', 'pembayaran_piutangs'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) {
                    $t->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users', 'pangkalans', 'trucks', 'drivers', 'surat_jalans', 'penjualans', 'piutangs', 'expenses', 'penebusans', 'retur_tabungs', 'schedule_agreements', 'stock_summaries', 'stock_histories', 'vehicle_stocks', 'vehicle_stock_histories', 'pembayaran_piutangs'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropForeign(['branch_id']);
                    $t->dropColumn('branch_id');
                });
            }
        }
    }
};
