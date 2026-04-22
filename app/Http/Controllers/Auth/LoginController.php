<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * LoginController — Menangani Proses Login & Logout
 *
 * Dipanggil ketika user mengisi form login dan menekan tombol "Masuk".
 *
 * Alur Login:
 * 1. User isi email + password → POST /login
 * 2. showLoginForm() → tampilkan form login (GET /login)
 * 3. login() → validasi credentials, jika valid → redirect ke dashboard
 * 4. logout() → hapus sesi, redirect ke halaman login
 */
class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     * Jika sudah login → redirect langsung ke dashboard.
     */
    public function showLoginForm()
    {
        // Kalau sudah login, langsung ke dashboard (cegah akses login berulang)
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Proses login: validasi kredensial dan buat sesi.
     *
     * Validasi bertingkat:
     * 1. Validasi format input (email valid, password minimal 6 char)
     * 2. Auth::attempt() → cek email + password ke database
     * 3. Regenerate session ID → mencegah Session Fixation Attack
     * 4. Redirect ke halaman intended (halaman yang ingin diakses sebelumnya)
     *    atau ke dashboard jika tidak ada.
     */
    public function login(Request $request)
    {
        // Validasi input form
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        // Coba login: Auth::attempt() akan cek DB dan buat session jika cocok
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Regenerate session ID untuk keamanan (prevent session fixation)
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . Auth::user()->name . '!');
        }

        // Login gagal: kembalikan ke form dengan pesan error
        // withInput() agar email yang sudah diisi tidak hilang
        return back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Email atau password salah. Silakan coba lagi.']);
    }

    /**
     * Proses logout: hapus sesi dan redirect ke login.
     *
     * Tiga langkah keamanan:
     * 1. Auth::logout() → hapus data autentikasi dari sesi
     * 2. session()->invalidate() → hapus seluruh data sesi
     * 3. session()->regenerateToken() → buat CSRF token baru
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
