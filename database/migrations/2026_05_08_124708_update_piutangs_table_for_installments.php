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
        Schema::table('piutangs', function (Blueprint $table) {
            $table->string('nomor_piutang')->after('id')->nullable()->unique();
            $table->date('tanggal_piutang')->after('nomor_piutang')->nullable();
            $table->decimal('total_terbayar', 15, 2)->after('sisa_tagihan')->default(0);
            $table->text('catatan')->after('status_piutang')->nullable();
            
            // Update enum if possible, or just handle in code.
            // For Laravel/MySQL, changing enum can be tricky with Schema::table.
            // I'll stick to handling 'belum_bayar' as 'belum_lunas' and 'mencicil' as 'cicilan'.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('piutangs', function (Blueprint $table) {
            $table->dropColumn(['nomor_piutang', 'tanggal_piutang', 'total_terbayar', 'catatan']);
        });
    }
};
