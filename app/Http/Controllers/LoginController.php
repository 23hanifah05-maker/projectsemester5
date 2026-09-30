<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahan penting untuk memanggil sistem login Laravel

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Memastikan kolom isian tidak kosong
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Auth::attempt otomatis mengecek username dan mencocokkan hash password ke database
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            
            // 3. Jika cocok, perbarui session untuk keamanan (wajib standar Laravel)
            $request->session()->regenerate();

            // Arahkan ke halaman dashboard
            return redirect()->route('dashboard');
        }

        // 4. Jika tidak cocok di database, kembalikan dengan pesan error
        return back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        // Mematikan session login dengan aman
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}