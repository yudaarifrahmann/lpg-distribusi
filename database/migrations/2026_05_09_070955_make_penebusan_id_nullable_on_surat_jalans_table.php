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
        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->foreignId('penebusan_id')->nullable()->change();
            $table->boolean('muat_dari_gudang')->default(false)->after('penebusan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_jalans', function (Blueprint $table) {
            $table->foreignId('penebusan_id')->nullable(false)->change();
            $table->dropColumn('muat_dari_gudang');
        });
    }
};
