<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Mengganti sistem login email menjadi username dan menghapus kolom email.
     */
    public function up(): void
    {
        // 1. Tambah kolom username nullable dulu agar data lama tidak konflik
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username', 50)->nullable()->after('name');
            }
        });

        // 2. Isi username untuk user yang sudah ada
        if (Schema::hasColumn('users', 'email')) {
            $existingUsers = DB::table('users')->get();
            foreach ($existingUsers as $u) {
                if (empty($u->username)) {
                    $derived = !empty($u->email) ? explode('@', $u->email)[0] : 'user_' . $u->id;
                    DB::table('users')->where('id', $u->id)->update(['username' => $derived]);
                }
            }
        }

        // 3. Ubah username jadi unique & not null, lalu hapus kolom email
        Schema::table('users', function (Blueprint $table) {
            $table->string('username', 50)->nullable(false)->unique()->change();

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $table->dropColumn('email_verified_at');
            }
            if (Schema::hasColumn('users', 'email')) {
                $table->dropColumn('email');
            }
        });

        // Hapus tabel password_reset_tokens karena reset password ditangani langsung oleh Super Admin
        Schema::dropIfExists('password_reset_tokens');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }
            if (Schema::hasColumn('users', 'username')) {
                $table->dropColumn('username');
            }
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }
};
