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
        // Akun Admin utama
        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@tvri-kalsel.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Akun Karyawan contoh
        User::create([
            'name'      => 'Karyawan TVRI',
            'email'     => 'karyawan@tvri-kalsel.com',
            'password'  => Hash::make('karyawan123'),
            'role'      => 'karyawan',
            'is_active' => true,
        ]);
    }
}
