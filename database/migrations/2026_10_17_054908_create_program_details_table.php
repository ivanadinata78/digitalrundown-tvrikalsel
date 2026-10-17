<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menyimpan detail tambahan acara (thumbnail & deskripsi) yang di-edit admin.
     * Data utama acara (judul, waktu, dll) tetap diambil dari Google Sheets API.
     * Key penghubung: program_title (harus sama persis dengan judul di Sheets).
     */
    public function up(): void
    {
        Schema::create('program_details', function (Blueprint $table) {
            $table->id();
            // Judul acara sebagai key penghubung dengan data dari Google Sheets API
            $table->string('program_title')->unique();
            // Thumbnail/gambar acara yang di-upload admin
            $table->string('thumbnail_url')->nullable();
            // Deskripsi lengkap acara yang di-tulis admin
            $table->text('description')->nullable();
            // Admin yang terakhir mengedit detail ini
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_details');
    }
};
