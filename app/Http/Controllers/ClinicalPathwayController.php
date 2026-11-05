<?php

namespace App\Http\Controllers;

use App\Helpers\Audit;
use App\Models\ClinicalPathway;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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

        // Nama kolom tabel kode_diagnosis dicari otomatis.
        $kolom = Schema::getColumnListing('kode_diagnosis');

        $kolomId = $kolom[0];

        $kolomKode = collect([
            'kode', 'kode_icd', 'kode_icd10', 'kode_diagnosa',
            'icd10', 'icd', 'code',
        ])->first(fn ($nama) => in_array($nama, $kolom)) ?? ($kolom[1] ?? $kolom[0]);

        // Diagnosa Utama = diagnosa urutan pertama yang dikirim dari form.
        $idDiagnosaUtama = array_values($request->penyakit_id)[0];

        $kodeUtama = DB::table('kode_diagnosis')
            ->where($kolomId, $idDiagnosaUtama)
            ->value($kolomKode);

        $utamaAdalahHnp = $kodeUtama !== null
            && Str::startsWith(strtoupper(trim($kodeUtama)), self::KODE_HNP);

        if (!$utamaAdalahHnp) {
            return response()->json([
                'success' => false,
                'message' => 'Clinical Pathway hanya dapat disimpan bila HNP (ICD-10 M51) menjadi Diagnosa Utama.',
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
                'dx_utama'              => $kosongJadiNull($data['dx_utama'] ?? null),
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