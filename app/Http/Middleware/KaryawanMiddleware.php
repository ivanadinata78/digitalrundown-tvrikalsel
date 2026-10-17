<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KaryawanMiddleware
{
    /**
     * Izinkan akses untuk user dengan role 'admin' atau 'karyawan'.
     * User yang belum login diarahkan ke halaman login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, ['admin', 'karyawan'])) {
            abort(403, 'Akses ditolak.');
        }

        // Blokir akun yang dinonaktifkan admin
        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi Administrator.');
        }

        return $next($request);
    }
}
