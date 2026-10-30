<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RundownController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Rundown Digital TVRI Kalimantan Selatan
|--------------------------------------------------------------------------
*/

// Halaman publik (tidak perlu login)
Route::get('/', [RundownController::class, 'index'])->name('home');

// -----------------------------------------------------------------------
// Route yang memerlukan login (admin + karyawan)
// -----------------------------------------------------------------------
Route::middleware(['karyawan'])->group(function () {
    Route::get('/rundown', [RundownController::class, 'index'])->name('rundown.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Alias dashboard untuk mengarahkan ke admin dashboard
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

// -----------------------------------------------------------------------
// Route Admin & Karyawan / Operator (Dashboard, Thumbnail, Kategori, Deskripsi)
// -----------------------------------------------------------------------
Route::middleware(['karyawan'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/programs/update-detail', [DashboardController::class, 'updateProgramDetail'])->name('programs.update-detail');
    Route::post('/programs/sync', [DashboardController::class, 'syncFromSheets'])->name('programs.sync');

    // Manajemen Kategori Acara (Bisa diakses Karyawan & Super Admin)
    Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/kategori', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/kategori/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/kategori/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Khusus Super Administrator: Manajemen Pengguna Internal
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
});

require __DIR__.'/auth.php';
