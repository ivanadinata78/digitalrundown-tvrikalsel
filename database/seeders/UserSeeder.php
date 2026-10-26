<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Membuat akun default: 1 admin dan 1 karyawan.
     */
    public function run(): void
    {
        // Akun Super Administrator utama
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'      => 'Super Administrator',
                'password'  => Hash::make('admin123'),
                'role'      => 'admin',
                'is_active' => true,
            ]
        );

        // Akun Karyawan contoh
        User::updateOrCreate(
            ['username' => 'karyawan'],
            [
                'name'      => 'Karyawan TVRI',
                'password'  => Hash::make('karyawan123'),
                'role'      => 'karyawan',
                'is_active' => true,
            ]
        );
    }
}
