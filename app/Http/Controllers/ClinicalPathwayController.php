<?php

namespace App\Http\Controllers;

use App\Models\ClinicalPathway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClinicalPathwayController extends Controller
{
    // Harus sama dengan KODE_HNP pada view.
    private const KODE_HNP = ['M51'];

    public function store(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required|integer',
            'penyakit_id'    => 'required|array|min:1',
            'penyakit_id.*'  => 'integer',
            'data'           => 'required|array',
        ]);

        $daftarKode = DB::table('kode_diagnosis')
            ->whereIn('id', $request->penyakit_id)
            ->pluck('kode');

        $adaHnp = $daftarKode->contains(function ($kode) {
            return Str::startsWith(strtoupper(trim($kode)), self::KODE_HNP);
        });

        if (!$adaHnp) {
            return response()->json([
                'success' => false,
                'message' => 'Clinical Pathway hanya dapat disimpan untuk diagnosa HNP (ICD-10 M51).',
            ], 422);
        }

        $data  = $request->input('data');
        $baris = $data['baris'] ?? [];

        $ket = function (string $nama) use ($baris) {
            $isi = $baris[$nama]['keterangan'] ?? [];
            return is_array($isi) ? implode("\n", $isi) : (string) $isi;
        };

        ClinicalPathway::updateOrCreate(
            ['id_pendaftaran' => $request->id_pendaftaran],
            [
                'dx_utama'              => $data['dx_utama'] ?? null,
                'dx_sekunder'           => $data['dx_sekunder'] ?? null,
                'dx_banding'            => $data['dx_banding'] ?? null,
                'asesment_klinis'       => $ket('Asesmen Klinis'),
                'pemeriksaan_fisik'     => $ket('Pemeriksaan Fisik'),
                'pemeriksaan_penunjang' => $ket('Pemeriksaan Penunjang'),
                'farmasi'               => $ket('Farmakologis'),
                'detail_json'           => json_encode($data, JSON_UNESCAPED_UNICODE),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Clinical Pathway berhasil disimpan.',
        ]);
    }
}