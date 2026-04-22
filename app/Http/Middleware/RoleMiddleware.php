<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware — Penjaga Akses Berbasis Role (RBAC)
 *
 * Middleware ini memastikan user yang sudah login memiliki ROLE yang tepat
 * untuk mengakses route tertentu.
 *
 * Cara Kerja:
 * 1. Request masuk ke route yang dilindungi
 * 2. Middleware cek apakah user sudah login (via Auth::check())
 * 3. Jika ya, cek apakah role user termasuk dalam daftar role yang diizinkan
 * 4. Jika cocok → lanjutkan request
 * 5. Jika tidak cocok → tolak dengan error 403 (Forbidden)
 *
 * Cara penggunaan di route:
 *   Route::get('/admin', [AdminController::class, 'index'])
 *       ->middleware('role:super_admin');
 *
 *   Route::get('/guru', [GuruController::class, 'index'])
 *       ->middleware('role:guru,wali_kelas'); // Bisa multiple role dipisah koma
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Langkah 1: Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // Langkah 2: Cek apakah role user ada di daftar role yang diizinkan
        // Langkah 2: Cek apakah role user ada di daftar role yang diizinkan
        if ($user->hasAnyRole($roles) || $user->hasRole('super_admin')) {
            return $next($request);
        }

        // Langkah 3: Role tidak sesuai → tolak akses
        abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
    }
}
