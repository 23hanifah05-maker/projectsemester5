<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Helpers\Audit;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        // Cek password dari database (yang sudah di-hash)
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            $request->session()->regenerate();

            Audit::catat('Login ke sistem', 'Autentikasi');

            return redirect()->route('dashboard');
        }

<<<<<<< HEAD
        Audit::catatGagal(
            'Gagal login ke sistem (username atau password salah)',
            'Autentikasi',
            $request->username
=======
        Audit::catat(
            'Gagal login: Username/password salah (' . $request->username . ')',
            'Autentikasi',
            null,
            null,
            null,
            null,
            'gagal'
>>>>>>> ddf835cc574b772e20d1bdb0b90352723bc7102a
        );

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        Audit::catat('Logout dari sistem', 'Autentikasi');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}