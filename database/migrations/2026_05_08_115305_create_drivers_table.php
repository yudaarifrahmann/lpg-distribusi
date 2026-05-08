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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->string('nomor_hp', 20);
            $table->text('alamat');
            $table->enum('role_pekerjaan', ['supir', 'knek']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
