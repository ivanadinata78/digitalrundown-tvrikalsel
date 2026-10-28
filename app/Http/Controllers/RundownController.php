<?php

namespace App\Http\Controllers;

use App\Models\ProgramDetail;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RundownController extends Controller
{
    /**
     * Tampilkan halaman rundown harian (Beranda).
     */
    public function index(): View
    {
        $programs = ProgramDetail::getProgramsWithDetails();
        $currentOnAir = ProgramDetail::getCurrentOnAir($programs);
        $categories = \App\Models\Category::orderBy('name')->pluck('name')->toArray();

        return view('rundown.index', compact('programs', 'currentOnAir', 'categories'));
    }
}
