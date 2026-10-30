<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\RundownSheetService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RundownController extends Controller
{
    public function __construct(
        protected RundownSheetService $sheetService
    ) {}

    /**
     * Tampilkan halaman rundown harian (Beranda) dengan data realtime dari Google Spreadsheet.
     */
    public function index(Request $request): View
    {
        $availableDays = $this->sheetService->getAvailableDays();
        $todayKey = $this->sheetService->getTodayKey();

        // Ambil hari dari query param ?hari=... (default: hari ini)
        $selectedDay = strtolower($request->query('hari', $todayKey));
        if (!isset($availableDays[$selectedDay])) {
            $selectedDay = $todayKey;
        }

        $programs = $this->sheetService->getProgramsByDay($selectedDay);
        $currentOnAir = $this->sheetService->getCurrentOnAir($programs);
        $categories = Category::orderBy('name')->pluck('name')->toArray();

        return view('rundown.index', compact(
            'programs',
            'currentOnAir',
            'categories',
            'availableDays',
            'selectedDay',
            'todayKey'
        ));
    }
}
