<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan pemanggilan seeder penting karena ada foreign key dependency.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,     // Harus pertama (users dipakai sebagai FK di tabel lain)
            ChannelSeeder::class,  // Data channel siaran (NASIONAL, KALSEL)
            CategorySeeder::class, // Data kategori acara (LIVE, RELAY, dll)
        ]);
    }
}
