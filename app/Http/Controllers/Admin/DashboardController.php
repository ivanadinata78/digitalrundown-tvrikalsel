<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\Channel;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik singkat.
     */
    public function index(): View
    {
        $stats = [
            'total_users'      => User::count(),
            'total_karyawan'   => User::where('role', 'karyawan')->count(),
            'total_categories' => Category::count(),
            'total_channels'   => Channel::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
