<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Username dan password sementara untuk testing
        $username = 'admin';
        $password = 'admin123';

        if (
            $request->username === $username &&
            $request->password === $password
        ) {
            session([
                'login' => true,
                'username' => $request->username,
            ]);

            return redirect()->route('dashboard');
        }

        return back()->with('error', 'Username atau password salah.');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect()->route('login');
    }
}