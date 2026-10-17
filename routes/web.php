<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RundownController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Rundown Digital TVRI Kalimantan Selatan
|--------------------------------------------------------------------------
*/

// Halaman publik (tidak perlu login)
Route::get('/', function () {
    return view('welcome');
});

// -----------------------------------------------------------------------
// Route yang memerlukan login (admin + karyawan)
// -----------------------------------------------------------------------
Route::middleware(['karyawan'])->group(function () {
    Route::get('/rundown', [RundownController::class, 'index'])->name('rundown.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -----------------------------------------------------------------------
// Route khusus Admin
// -----------------------------------------------------------------------
Route::middleware(['karyawan', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
