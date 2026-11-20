<?php

namespace App\Http\Controllers;

use App\Helpers\Audit;
use App\Models\ClinicalPathway;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class ClinicalPathwayController extends Controller
{
    // Harus sama dengan POLA_DX_HNP pada view.
    // Cocok: HNP, hernia nukleus pulposus (termasuk salah eja "nuleus"),
    // saraf kejepit, saraf terjepit. Tidak peka huruf besar/kecil.
    private const POLA_DX_HNP = '/\bhnp\b|hernia\s+nuk?leus\s+pulposus|saraf\s+(?:kejepit|terjepit)/i';

    public function store(Request $request)
    {
        $request->validate([
            'id_pendaftaran' => 'required|integer',
            'dx_utama_soap'  => 'required|string',
            'data'           => 'required|array',
        ], [
            'dx_utama_soap.required' => 'Diagnosa Utama (SOAP bagian A) belum diisi.',
        ]);

        // Clinical Pathway hanya untuk Diagnosa Utama HNP / saraf terjepit.
        if (!preg_match(self::POLA_DX_HNP, $request->input('dx_utama_soap'))) {
            return response()->json([
                'success' => false,
                'message' => 'Clinical Pathway hanya dapat disimpan bila Diagnosa Utama (SOAP bagian A) '
                           . 'berisi HNP, Hernia Nukleus Pulposus, saraf kejepit, atau saraf terjepit.',
            ], 422);
        }

        $data  = $request->input('data');
        $baris = $data['baris'] ?? [];

        $kosongJadiNull = fn ($nilai) => ($nilai === null || $nilai === '') ? null : $nilai;

        $ket = function (string $nama) use ($baris, $kosongJadiNull) {
            $isi  = $baris[$nama]['keterangan'] ?? [];
            $teks = is_array($isi) ? implode("\n", $isi) : (string) $isi;

            return $kosongJadiNull($teks);
        };

        $waktu = fn (string $nama) => $kosongJadiNull($baris[$nama]['waktu'] ?? null);

        $tarif = fn (string $nama): int => (int) ($baris[$nama]['tarif'] ?? 0);

        $clinicalPathway = ClinicalPathway::updateOrCreate(
            ['id_pendaftaran' => $request->id_pendaftaran],
            [
                'dx_utama'              => $kosongJadiNull($data['dx_utama'] ?? $request->input('dx_utama_soap')),
                'dx_sekunder'           => $kosongJadiNull($data['dx_sekunder'] ?? null),
                'dx_banding'            => $kosongJadiNull($data['dx_banding'] ?? null),
                'waktu_dx_utama'        => $kosongJadiNull($data['waktu_dx_utama'] ?? null),
                'waktu_dx_sekunder'     => $kosongJadiNull($data['waktu_dx_sekunder'] ?? null),
                'waktu_dx_banding'      => $kosongJadiNull($data['waktu_dx_banding'] ?? null),

                'asesment_klinis'       => $ket('Asesmen Klinis'),
                'waktu_asesmen'         => $waktu('Asesmen Klinis'),

                'pemeriksaan_fisik'     => $ket('Pemeriksaan Fisik'),
                'waktu_fisik'           => $waktu('Pemeriksaan Fisik'),
                'tarif_fisik'           => $tarif('Pemeriksaan Fisik'),

                'pemeriksaan_penunjang' => $ket('Pemeriksaan Penunjang'),
                'waktu_penunjang'       => $waktu('Pemeriksaan Penunjang'),
                'tarif_penunjang'       => $tarif('Pemeriksaan Penunjang'),

                'farmasi'               => $ket('Farmakologis'),
                'waktu_farmasi'         => $waktu('Farmakologis'),
                'tarif_farmasi'         => $tarif('Farmakologis'),

                'fisioterapi'           => $ket('Fisioterapi'),
                'waktu_fisioterapi'     => $waktu('Fisioterapi'),
                'tarif_fisioterapi'     => $tarif('Fisioterapi'),

                'edukasi'               => $ket('Edukasi'),
                'waktu_edukasi'         => $waktu('Edukasi'),

                'variasi_pelayanan'     => $ket('Variasi Pelayanan'),
                'waktu_variasi'         => $waktu('Variasi Pelayanan'),

                'total_tarif'           => (int) ($data['total'] ?? 0),
            ]
        );

        $noRm = Pendaftaran::where(
            'id_pendaftaran',
            $request->id_pendaftaran
        )->value('no_rm');

        Audit::catat(
            $clinicalPathway->wasRecentlyCreated
                ? 'Menyimpan Clinical Pathway HNP'
                : 'Memperbarui Clinical Pathway HNP',
            'Poli Syaraf',
            $noRm
        );

        return response()->json([
            'success' => true,
            'message' => 'Clinical Pathway berhasil disimpan.',
        ]);
    }
}