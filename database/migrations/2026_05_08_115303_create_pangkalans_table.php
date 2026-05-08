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
        Schema::create('pangkalans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pangkalan')->unique();
            $table->string('nama_pemilik');
            $table->text('alamat');
            $table->string('no_hp', 20);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pangkalans');
    }
};
