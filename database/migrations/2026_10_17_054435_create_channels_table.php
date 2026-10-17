<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel channels menyimpan data saluran siaran TVRI.
     */
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            // Nama channel siaran (contoh: NASIONAL, KALSEL)
            $table->string('name', 50)->unique();
            // Deskripsi singkat channel
            $table->string('description')->nullable();
            // URL logo channel
            $table->string('logo_url')->nullable();
            // Status aktif/nonaktif channel
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
