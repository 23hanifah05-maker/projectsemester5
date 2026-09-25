<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // TODO: ganti data di bawah ini dengan query asli ke database,
        // misalnya: Pasien::count(), Pendaftaran::whereDate('created_at', today())->count(), dst.
        // Selama belum ada query, view dashboard.blade.php sudah punya
        // nilai default (fallback ??) supaya tetap tampil.

        return view('dashboard');
    }
}