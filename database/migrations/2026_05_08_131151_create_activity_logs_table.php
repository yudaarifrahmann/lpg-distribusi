<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $blueprint->string('aktivitas');
            $blueprint->string('module');
            $blueprint->json('data_sebelum')->nullable();
            $blueprint->json('data_sesudah')->nullable();
            $blueprint->string('ip_address')->nullable();
            $blueprint->string('user_agent')->nullable();
            $blueprint->timestamps();

            // Indexes for performance
            $blueprint->index('module');
            $blueprint->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
