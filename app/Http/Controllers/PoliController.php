<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poli;

class PoliController extends Controller
{
    public function saraf()
    {
        // Mengambil data poli yang namanya mengandung kata 'saraf' atau 'syaraf'
        $data_poli = Poli::where('nama_poli', 'like', '%saraf%')->first();
        return view('poli.syaraf', compact('data_poli'));
    }

    public function obgyn()
    {
        $data_poli = Poli::where('nama_poli', 'like', '%obgyn%')->first();
        return view('poli.obgyn', compact('data_poli'));
    }

    public function jantung()
    {
        $data_poli = Poli::where('nama_poli', 'like', '%jantung%')->first();
        return view('poli.jantung', compact('data_poli'));
    }

    public function jiwa()
    {
        $data_poli = Poli::where('nama_poli', 'like', '%jiwa%')->first();
        return view('poli.jiwa', compact('data_poli'));
    }
}