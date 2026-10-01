<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Username dan password login yang digunakan
        if (
            $user &&
            $request->username === 'admin123' &&
            $request->password === 'admin@123456'
        ) {
            // Login user tanpa mengecek password dari database
            Auth::login($user);

            // Regenerasi session
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}