<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (
            $user &&
            $request->username === 'admin123' &&
            $request->password === 'admin@123456'
        ) {
            Auth::login($user);

            $request->session()->regenerate();

            Audit::catat('Login ke sistem', 'Autentikasi');

            return redirect()->route('dashboard');
        }

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