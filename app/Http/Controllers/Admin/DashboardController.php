<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Channel;
use App\Models\ProgramDetail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Data master program siaran default TVRI Kalimantan Selatan.
     */
    protected function getDefaultPrograms(): array
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
     * Tampilkan dashboard admin dengan daftar acara dan form edit interaktif di sampingnya.
     */
    public function index(): View
    {
        $programs = ProgramDetail::getProgramsWithDetails();
        $currentOnAir = ProgramDetail::getCurrentOnAir($programs);

        // Kategori untuk dropdown dan filter tab
        $dbCategories = Category::orderBy('name')->pluck('name')->toArray();
        $defaultCategories = ['Berita', 'Wisata & Budaya', 'Talkshow', 'Dokumenter', 'Religi'];
        $categories = array_unique(array_merge($defaultCategories, $dbCategories));

        $stats = [
            'total_acara'    => count($programs),
            'total_live'     => collect($programs)->where('status_type', 'live')->count(),
            'total_selesai'  => 3,
            'total_menunggu' => 5,
        ];

        return view('admin.dashboard', compact('programs', 'categories', 'stats', 'currentOnAir'));
    }

    /**
     * Simpan pembaruan detail acara (deskripsi, kategori, dan upload/hapus thumbnail).
     */
    public function updateProgramDetail(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'program_title'    => ['required', 'string'],
            'category'         => ['nullable', 'string', 'max:100'],
            'description'      => ['nullable', 'string'],
            'thumbnail_image'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'remove_thumbnail' => ['nullable', 'boolean'],
        ]);

        $detail = ProgramDetail::firstOrNew(['program_title' => $validated['program_title']]);
        $detail->category    = $validated['category'] ?? null;
        $detail->description = $validated['description'] ?? null;
        $detail->updated_by  = auth()->id();

        // Handle upload thumbnail baru
        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $filename = 'thumb_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/thumbnails'), $filename);
            $detail->thumbnail_url = $filename;
        } elseif ($request->boolean('remove_thumbnail')) {
            $detail->thumbnail_url = null;
        }

        $detail->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Detail acara '{$detail->program_title}' berhasil disimpan.",
                'data'    => [
                    'program_title' => $detail->program_title,
                    'category'      => $detail->category,
                    'description'   => $detail->description,
                    'thumbnail'     => $detail->thumbnail_url,
                    'thumbnail_url' => $detail->thumbnail_url ? asset('images/thumbnails/' . $detail->thumbnail_url) : null,
                ],
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('status', "Detail acara '{$detail->program_title}' berhasil diperbarui.");
    }
}
