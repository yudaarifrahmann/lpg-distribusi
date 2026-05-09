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
            $table->boolean('is_supir_tembak')->default(false)->after('driver_id');
            $table->string('nama_supir_tembak')->nullable()->after('is_supir_tembak');
            $table->string('alamat_supir_tembak')->nullable()->after('nama_supir_tembak');
            $table->string('no_hp_supir_tembak')->nullable()->after('alamat_supir_tembak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_jalans', function (Blueprint $table) {
            //
        });
    }
};
