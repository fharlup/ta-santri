<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * FR-01: Proses Login Multi-Role
     * Menangani login untuk Kesiswaan, Komdis, Wali Kelas, dan Santri.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Coba Autentikasi
        // Auth::attempt akan otomatis mengecek password yang di-hash di database
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            /**
             * REDIRECT TUNGGAL:
             * Semua role diarahkan ke rute 'kesiswaan.dashboard'.
             * Pastikan rute ini sudah terdaftar di web.php Anda.
             */
            return redirect()->intended(route('kesiswaan.dashboard'));
        }

        // 3. JIKA GAGAL: Kembalikan pesan error ke halaman login
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * FR-02: Logout Sistem
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // Bersihkan session agar aman
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}