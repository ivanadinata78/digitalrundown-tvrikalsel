<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Menyimpan status tayang real-time setiap baris rundown.
     * Key penghubung: broadcast_date + no_urut (nomor urut dari Sheets).
     * Status tidak ada di Google Sheets sehingga harus disimpan di DB.
     */
    public function up(): void
    {
        Schema::create('broadcast_statuses', function (Blueprint $table) {
            $table->id();
            // Tanggal siaran (contoh: 2026-10-17)
            $table->date('broadcast_date');
            // Nomor urut acara di hari tersebut (sesuai kolom NO di Sheets)
            $table->unsignedTinyInteger('no_urut');
            // Status tayang saat ini
            $table->enum('status', ['menunggu', 'on_air', 'sudah_tayang', 'batal'])->default('menunggu');
            // Karyawan/admin yang mengubah status terakhir
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Satu tanggal + nomor urut hanya boleh ada satu status
            $table->unique(['broadcast_date', 'no_urut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broadcast_statuses');
    }
};
