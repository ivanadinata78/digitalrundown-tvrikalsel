<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProgramDetail;
use App\Services\RundownSheetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        protected RundownSheetService $sheetService
    ) {}

    /**
     * Tampilkan dashboard admin dengan daftar acara dari Google Spreadsheet
     * dan panel editor detail acara interaktif.
     */
    public function index(Request $request): View
    {
        $availableDays = $this->sheetService->getAvailableDays();
        $todayKey = $this->sheetService->getTodayKey();

        // Hari yang dipilih melalui tab / query param ?hari= (default: hari ini)
        $selectedDay = strtolower($request->query('hari', $todayKey));
        if (!isset($availableDays[$selectedDay])) {
            $selectedDay = $todayKey;
        }

        $programs = $this->sheetService->getProgramsByDay($selectedDay);
        $currentOnAir = $this->sheetService->getCurrentOnAir($programs);

        // Pastikan seeder kategori ada jika kosong
        if (Category::count() === 0) {
            (new \Database\Seeders\CategorySeeder())->run();
        }
        $categories = Category::orderBy('name')->pluck('name')->toArray();

        // Hitung statistik aktual dari spreadsheet
        $stats = [
            'total_acara'    => count($programs),
            'total_live'     => collect($programs)->where('status_type', 'live')->count(),
            'total_selesai'  => collect($programs)->where('info', 'SUDAH TAYANG')->count(),
            'total_menunggu' => collect($programs)->where('info', 'MENUNGGU')->count(),
        ];

        return view('admin.dashboard', compact(
            'programs',
            'categories',
            'stats',
            'currentOnAir',
            'availableDays',
            'selectedDay',
            'todayKey'
        ));
    }

    /**
     * Sinkronkan ulang data langsung dari Google Spreadsheet (menghapus cache dan re-fetch).
     */
    public function syncFromSheets(Request $request): RedirectResponse
    {
        $this->sheetService->clearAllCache();
        $todayKey = $this->sheetService->getTodayKey();
        $this->sheetService->getProgramsByDay($todayKey, true);

        return redirect()->back()
            ->with('status', 'Jadwal siaran berhasil disinkronkan langsung dari Google Spreadsheet TVRI Kalsel!');
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

        // Hapus cache agar pembaruan thumbnail/kategori/deskripsi langsung terlihat di rundown
        $this->sheetService->clearAllCache();

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

        return redirect()->back()
            ->with('status', "Detail acara '{$detail->program_title}' berhasil diperbarui.");
    }
}
