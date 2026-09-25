<?php

namespace App\Http\Controllers;

class PoliController extends Controller
{
    public function saraf()
    {
        return view('poli.syaraf');
    }

    public function obgyn()
    {
        return view('poli.obgyn');
    }

    public function jantung()
    {
        return view('poli.jantung');
    }

    public function jiwa()
    {
        return view('poli.jiwa');
    }
}
