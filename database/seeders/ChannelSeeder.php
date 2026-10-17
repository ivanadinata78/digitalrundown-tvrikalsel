<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    /**
     * Data awal channel siaran TVRI Kalimantan Selatan.
     */
    public function run(): void
    {
        $channels = [
            [
                'name'        => 'NASIONAL',
                'description' => 'Siaran TVRI Nasional yang di-relay ke daerah',
                'logo_url'    => null,
                'is_active'   => true,
            ],
            [
                'name'        => 'KALSEL',
                'description' => 'Siaran TVRI Kalimantan Selatan (lokal)',
                'logo_url'    => null,
                'is_active'   => true,
            ],
        ];

        foreach ($channels as $channel) {
            Channel::create($channel);
        }
    }
}
