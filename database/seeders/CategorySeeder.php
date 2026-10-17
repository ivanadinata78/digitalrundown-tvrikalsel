<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Data awal kategori acara berdasarkan jenis siaran TVRI Kalsel.
     */
    public function run(): void
    {
        $categories = [
            // Siaran langsung - merah
            ['name' => 'LIVE',     'color_hex' => '#EF4444', 'icon' => 'signal'],
            // Relay dari pusat - biru
            ['name' => 'RELAY',    'color_hex' => '#3B82F6', 'icon' => 'broadcast'],
            // Pemutaran ulang rekaman - abu-abu
            ['name' => 'PLAYBACK', 'color_hex' => '#6B7280', 'icon' => 'play-circle'],
            // Tayang ulang episode lama - kuning
            ['name' => 'RERUN',    'color_hex' => '#F59E0B', 'icon' => 'refresh'],
            // Public Service Announcement - hijau
            ['name' => 'PSA',      'color_hex' => '#10B981', 'icon' => 'speakerphone'],
            // Bumper/jeda antar acara - ungu
            ['name' => 'STING',    'color_hex' => '#8B5CF6', 'icon' => 'film'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
