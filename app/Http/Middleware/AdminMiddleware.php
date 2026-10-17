<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Hanya izinkan akses untuk user dengan role 'admin'.
     * User dengan role 'karyawan' akan diarahkan ke halaman rundown.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect()->route('rundown.index')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}
