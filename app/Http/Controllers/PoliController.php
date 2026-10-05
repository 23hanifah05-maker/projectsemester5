<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poli;

class PoliController extends Controller
{
    public function saraf()
    {
        $poli = Poli::where('slug', 'syaraf')->first();
        return view('poli.syaraf', compact('poli'));
    }

    public function obgyn()
    {
        $poli = Poli::where('slug', 'obgyn')->first();
        return view('poli.obgyn', compact('poli'));
    }

    public function jantung()
    {
        $poli = Poli::where('slug', 'jantung')->first();
        return view('poli.jantung', compact('poli'));
    }

    public function jiwa()
    {
        $poli = Poli::where('slug', 'jiwa')->first();
        return view('poli.jiwa', compact('poli'));
    }
}