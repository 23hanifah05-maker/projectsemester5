<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ttv;

class TtvController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pendaftaran'   => 'required|integer',
            'tekanan_darah'    => 'nullable|string|max:20',
            'suhu'             => 'nullable|numeric',
            'heart_rate'       => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'spo2'             => 'nullable|integer',
            'alergi'           => 'nullable|string|max:255',
        ]);

        $ttv = Ttv::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data TTV berhasil disimpan.',
            'data' => $ttv
        ]);
    }
}