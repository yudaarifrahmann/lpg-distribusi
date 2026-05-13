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
        Schema::table('schedule_agreements', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->after('tanggal_sa')->constrained()->onDelete('set null');
            $table->foreignId('truck_id')->nullable()->after('driver_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_agreements', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropForeign(['truck_id']);
            $table->dropColumn(['driver_id', 'truck_id']);
        });
    }
};
