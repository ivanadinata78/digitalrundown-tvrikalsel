<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_title',
        'category',
        'thumbnail_url',
        'description',
        'updated_by',
    ];

    /**
     * Admin yang terakhir mengedit detail acara ini.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Data master program siaran default TVRI Kalimantan Selatan.
     */
    public static function getDefaultPrograms(): array
    {
        return [
            [
                'id'          => 1,
                'no'          => 1,
                'waktu'       => '09:01:00',
                'acara'       => 'HABAR BANUA',
                'kategori'    => 'Berita',
                'thumbnail'   => 'habarbanua.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:59:00',
                'info'        => 'ON AIR',
                'status'      => 'LIVE',
                'status_type' => 'live',
                'description' => 'Menghadirkan informasi terkini seputar Kalimantan Selatan, dengan beragam berita dan kabar terbaru dari masyarakat Banua.',
            ],
            [
                'id'          => 2,
                'no'          => 2,
                'waktu'       => '10:00:30',
                'acara'       => 'INI BORNEO',
                'kategori'    => 'Wisata & Budaya',
                'thumbnail'   => 'ini_borneo_thumb.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:59:00',
                'info'        => 'MENUNGGU',
                'status'      => 'RELAY',
                'status_type' => 'relay',
                'description' => 'Jelajah pesona alam, adat istiadat, dan kekayaan ragam budaya pulau Kalimantan yang memukau dan kaya sejarah nusantara.',
            ],
            [
                'id'          => 3,
                'no'          => 3,
                'waktu'       => '15:00:30',
                'acara'       => 'PERSPEKTIF',
                'kategori'    => 'Talkshow',
                'thumbnail'   => 'perspektif_thumb.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:59:00',
                'info'        => 'MENUNGGU',
                'status'      => 'RELAY',
                'status_type' => 'relay',
                'description' => 'Program dialog interaktif mengupas isu hangat, opini publik, dan wawasan mendalam bersama para narasumber terpercaya.',
            ],
            [
                'id'          => 4,
                'no'          => 4,
                'waktu'       => '17:00:00',
                'acara'       => 'KALSEL HARI INI',
                'kategori'    => 'Berita',
                'thumbnail'   => 'kalseltoday_thumb.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:30:00',
                'info'        => 'MENUNGGU',
                'status'      => 'LIVE',
                'status_type' => 'live',
                'description' => 'Rangkuman peristiwa penting, liputan langsung, dan perkembangan terkini dari penjuru Kalimantan Selatan pada sore hari.',
            ],
            [
                'id'          => 5,
                'no'          => 5,
                'waktu'       => '17:30:00',
                'acara'       => 'INSPIRASI INDONESIA',
                'kategori'    => 'Dokumenter',
                'thumbnail'   => 'inspirasiindo_thumb.PNG',
                'channel'     => 'Kalsel',
                'durasi'      => '0:15:00',
                'info'        => 'MENUNGGU',
                'status'      => 'RERUN',
                'status_type' => 'rerun',
                'description' => 'Kisah nyata penuh inspirasi dari sosok-sosok pejuang kehidupan dan inovator lokal yang memberi dampak positif bagi bangsa.',
            ],
            [
                'id'          => 6,
                'no'          => 6,
                'waktu'       => '17:45:00',
                'acara'       => 'KAJIAN TAUHID',
                'kategori'    => 'Religi',
                'thumbnail'   => 'kajian_thumb.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:15:00',
                'info'        => 'MENUNGGU',
                'status'      => 'PLAYBACK',
                'status_type' => 'playback',
                'description' => 'Tausiyah seputar penguatan keimanan, nilai-nilai ketauhidan, dan panduan ibadah sehari-hari dalam kehidupan bermasyarakat.',
            ],
            [
                'id'          => 7,
                'no'          => 7,
                'waktu'       => '18:00:00',
                'acara'       => 'ADZAN MAGHRIB',
                'kategori'    => 'Religi',
                'thumbnail'   => 'adzan_thumb.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:05:00',
                'info'        => 'MENUNGGU',
                'status'      => 'PLAYBACK',
                'status_type' => 'playback',
                'description' => 'Siaran kumandang adzan maghrib untuk wilayah Banjarmasin dan sekitarnya (Waktu Indonesia Tengah).',
            ],
            [
                'id'          => 8,
                'no'          => 8,
                'waktu'       => '18:05:00',
                'acara'       => 'KAJIAN TAUHID (LANJUTAN)',
                'kategori'    => 'Religi',
                'thumbnail'   => 'kajian_thumb.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:26:00',
                'info'        => 'MENUNGGU',
                'status'      => 'PLAYBACK',
                'status_type' => 'playback',
                'description' => 'Lanjutan pembahasan kajian keislaman dan sesi tanya jawab keagamaan bersama ustadz pembina.',
            ],
            [
                'id'          => 9,
                'no'          => 9,
                'waktu'       => '18:31:00',
                'acara'       => 'PESONA INDONESIA - KALSEL',
                'kategori'    => 'Dokumenter',
                'thumbnail'   => 'pesonaindo_thumb.PNG',
                'channel'     => 'Kalsel',
                'durasi'      => '0:30:00',
                'info'        => 'MENUNGGU',
                'status'      => 'PLAYBACK',
                'status_type' => 'playback',
                'description' => 'Dokumenter keindahan bentang alam, pasar terapung, dan kearifan lokal masyarakat Kalimantan Selatan.',
            ],
            [
                'id'          => 10,
                'no'          => 10,
                'waktu'       => '19:01:00',
                'acara'       => 'MAWARUNG',
                'kategori'    => 'Talkshow',
                'thumbnail'   => 'mawarung_thumb.jpg',
                'channel'     => 'Kalsel',
                'durasi'      => '0:59:00',
                'info'        => 'MENUNGGU',
                'status'      => 'PLAYBACK',
                'status_type' => 'playback',
                'description' => 'Obrolan santai dan jenaka bernuansa khas warung Banjar, sarat dengan kritik membangun dan humor segar.',
            ],
        ];
    }

    /**
     * Pastikan semua program default sudah tersimpan di database (program_details)
     * agar relasi database dengan kategori tersinkronisasi 100%.
     */
    public static function syncDefaultPrograms(): void
    {
        foreach (self::getDefaultPrograms() as $def) {
            self::firstOrCreate(
                ['program_title' => $def['acara']],
                [
                    'category'      => $def['kategori'],
                    'description'   => $def['description'] ?? null,
                    'thumbnail_url' => $def['thumbnail'] ?? null,
                ]
            );
        }
    }

    /**
     * Ambil seluruh program rundown yang sudah disesuaikan dengan perubahan dari database.
     */
    public static function getProgramsWithDetails(): array
    {
        // Pastikan program default tersimpan di database
        self::syncDefaultPrograms();

        $programs = self::getDefaultPrograms();
        $savedDetails = self::all()->keyBy('program_title');
        $validCategories = Category::pluck('name')->toArray();

        foreach ($programs as &$prog) {
            if (isset($savedDetails[$prog['acara']])) {
                $detail = $savedDetails[$prog['acara']];
                if (!empty($detail->description)) {
                    $prog['description'] = $detail->description;
                }
                if ($detail->category !== null) {
                    $prog['kategori'] = $detail->category;
                }
                if (!empty($detail->thumbnail_url)) {
                    $prog['thumbnail'] = $detail->thumbnail_url;
                }
            }

            // Jika kategori acara saat ini tidak terdaftar di master tabel kategori, kosongi
            if (!empty($prog['kategori']) && !in_array($prog['kategori'], $validCategories)) {
                $prog['kategori'] = '';
            }
        }
        unset($prog);

        return $programs;
    }

    /**
     * Dapatkan acara yang sedang tayang sekarang (Live / On Air) seperti di beranda.
     */
    public static function getCurrentOnAir(?array $programs = null): array
    {
        $programs = $programs ?? self::getProgramsWithDetails();

        return collect($programs)->firstWhere('info', 'ON AIR')
            ?? collect($programs)->firstWhere('status_type', 'live')
            ?? $programs[0];
    }
}
