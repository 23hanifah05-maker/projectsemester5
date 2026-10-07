<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poli;

class PoliController extends Controller
{
    public function saraf()
    {
        $poli = Poli::where('nama_poli', 'Saraf')->first();
        return view('poli.syaraf', compact('poli'));
    }

    public function obgyn()
    {
        $poli = Poli::where('nama_poli', 'Obgyn')->first();
        return view('poli.obgyn', compact('poli'));
    }

    public function jantung()
    {
        $poli = Poli::where('nama_poli', 'Jantung')->first();
        return view('poli.jantung', compact('poli'));
    }

    public function jiwa()
    {
        $poli = Poli::where('nama_poli', 'Jiwa')->first();
        return view('poli.jiwa', compact('poli'));
    }
}