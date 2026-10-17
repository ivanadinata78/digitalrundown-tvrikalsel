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
        Schema::table('users', function (Blueprint $table) {
            // Role akun: admin bisa kelola semua fitur, karyawan hanya lihat & update status
            $table->enum('role', ['admin', 'karyawan'])->default('karyawan')->after('email');
            // Foto profil user (opsional)
            $table->string('avatar')->nullable()->after('role');
            // Status aktif/nonaktif akun (admin dapat menonaktifkan akun karyawan)
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'avatar', 'is_active']);
        });
    }
};
