<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel audit trail — mencatat semua aksi yang dilakukan user di sistem.
     * Berguna untuk tracking siapa mengubah apa dan kapan.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            // User yang melakukan aksi
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            // Jenis aksi yang dilakukan
            $table->enum('action', [
                'create',         // Menambah data baru
                'update',         // Mengubah data
                'delete',         // Menghapus data
                'status_change',  // Mengubah status tayang
                'login',          // Login ke sistem
                'logout',         // Logout dari sistem
            ]);
            // Entitas/tabel yang diubah (contoh: program_details, broadcast_statuses)
            $table->string('entity_type')->nullable();
            // ID entitas yang diubah
            $table->unsignedBigInteger('entity_id')->nullable();
            // Deskripsi singkat aksi (contoh: "Update status acara #5 menjadi ON AIR")
            $table->string('description')->nullable();
            // Data sebelum diubah (JSON)
            $table->json('old_value')->nullable();
            // Data sesudah diubah (JSON)
            $table->json('new_value')->nullable();
            // IP address user saat melakukan aksi
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
