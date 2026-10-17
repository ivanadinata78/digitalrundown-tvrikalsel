<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel categories menyimpan jenis/kategori acara seperti Live, Relay, Playback, Rerun, dll.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Nama kategori acara (contoh: LIVE, RELAY, PLAYBACK, RERUN, PSA)
            $table->string('name', 100)->unique();
            // Warna label kategori dalam format hex (contoh: #FF0000 untuk merah/LIVE)
            $table->string('color_hex', 7)->default('#6B7280');
            // Ikon kategori (opsional, nama icon atau URL)
            $table->string('icon')->nullable();
            // Admin yang membuat kategori ini
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
