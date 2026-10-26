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
            ['name' => 'Berita',          'color_hex' => '#EF4444', 'icon' => 'newspaper'],
            ['name' => 'Wisata & Budaya', 'color_hex' => '#3B82F6', 'icon' => 'globe'],
            ['name' => 'Talkshow',        'color_hex' => '#8B5CF6', 'icon' => 'microphone'],
            ['name' => 'Dokumenter',      'color_hex' => '#10B981', 'icon' => 'film'],
            ['name' => 'Religi',          'color_hex' => '#F59E0B', 'icon' => 'sparkles'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
