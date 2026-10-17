<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class RundownController extends Controller
{
    /**
     * Tampilkan halaman rundown harian.
     * Data jadwal akan diambil dari Google Sheets API.
     */
    public function index(): View
    {
        return view('rundown.index');
    }
}
