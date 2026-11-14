<?php

namespace App\Http\Controllers;

use App\Helpers\Audit;
use App\Models\Assesmen;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssesmenController extends Controller
{
    /**
     * Bagian yang boleh disimpan oleh tiap role.
     *
     * admin   : S, O, A, P, dan kode ICD
     * dokter  : S, O, A, P
     * perawat : S, O
     * pmik    : kode ICD saja
     */
    private function izinRole(?string $role): array
    {
        return match ($role) {
            'admin'   => ['subjective', 'objective', 'assessment', 'plan', 'icd'],
            'dokter'  => ['subjective', 'objective', 'assessment', 'plan'],
            'perawat' => ['subjective', 'objective'],
            'pmik'    => ['icd'],
            default   => [],
        };
    }

    public function store(Request $request)
    {
        $role = optional($request->user())->role;
        $izin = $this->izinRole($role);

        if (empty($izin)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki hak akses untuk menyimpan data ini.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => [
                'required',
                'integer',
                'exists:pendaftaran,id_pendaftaran',
            ],

            // SUBJECTIVE
            'keluhan_utama' => ['sometimes', 'nullable', 'string'],
            'riwayat_penyakit_sekarang' => ['sometimes', 'nullable', 'string'],
            'riwayat_penyakit_dahulu' => ['sometimes', 'nullable', 'string'],
            'riwayat_penyakit_keluarga' => ['sometimes', 'nullable', 'string'],
            'riwayat_pengobatan' => ['sometimes', 'nullable', 'string'],
            'riwayat_sosial' => ['sometimes', 'nullable', 'string'],
            'riwayat_operasi' => ['sometimes', 'nullable', 'string'],
            'riwayat_alergi' => ['sometimes', 'nullable', 'string'],
            'riwayat_imunisasi' => ['sometimes', 'nullable', 'string'],

            // OBJECTIVE
            'pemeriksaan_fisik_lainnya' => ['sometimes', 'nullable', 'string'],

            // ASSESSMENT
            'dx_utama' => ['sometimes', 'nullable', 'string'],
            'dx_sekunder' => ['sometimes', 'nullable', 'string'],

            // PLAN
            'tindakan_medis' => ['sometimes', 'nullable', 'string'],
            'konseling_edukasi' => ['sometimes', 'nullable', 'string'],
            'rencana_tindak_lanjut' => ['sometimes', 'nullable', 'string'],
            'diet' => ['sometimes', 'nullable', 'string'],
            'resep_obat' => ['sometimes', 'nullable', 'string'],

            // ICD-10
            'penyakit_id' => ['sometimes', 'nullable', 'array'],
            'penyakit_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:kode_diagnosis,id_icd10',
            ],

            // ICD-9-CM
            'tindakan_id' => ['sometimes', 'nullable', 'array'],
            'tindakan_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:kode_tindakan,id_icd9',
            ],
        ], [
            'id_pendaftaran.required' => 'ID pendaftaran tidak ditemukan.',
            'id_pendaftaran.exists' => 'Data pendaftaran tidak ditemukan.',
            'penyakit_id.*.exists' => 'Salah satu diagnosis ICD-10 tidak ditemukan.',
            'tindakan_id.*.exists' => 'Salah satu tindakan ICD-9-CM tidak ditemukan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data belum lengkap atau tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $idPendaftaran = (int) $data['id_pendaftaran'];

        /*
         * PEMETAAN KOLOM SOAP
         */
        $petaSoap = [
            // SUBJECTIVE
            'keluhan_utama' => 'subjective',
            'riwayat_penyakit_sekarang' => 'subjective',
            'riwayat_penyakit_dahulu' => 'subjective',
            'riwayat_penyakit_keluarga' => 'subjective',
            'riwayat_pengobatan' => 'subjective',
            'riwayat_sosial' => 'subjective',
            'riwayat_operasi' => 'subjective',
            'riwayat_alergi' => 'subjective',
            'riwayat_imunisasi' => 'subjective',

            // OBJECTIVE
            'pemeriksaan_fisik_lainnya' => 'objective',

            // ASSESSMENT
            'dx_utama' => 'assessment',
            'dx_sekunder' => 'assessment',

            // PLAN
            'tindakan_medis' => 'plan',
            'konseling_edukasi' => 'plan',
            'rencana_tindak_lanjut' => 'plan',
            'diet' => 'plan',
            'resep_obat' => 'plan',
        ];

        /*
         * Hanya kolom yang diizinkan untuk role tersebut
         * dan benar-benar dikirim dalam request yang diubah.
         */
        $ubahSoap = [];

        foreach ($petaSoap as $kolom => $bagian) {
            if (
                in_array($bagian, $izin, true)
                && array_key_exists($kolom, $data)
            ) {
                $ubahSoap[$kolom] = (string) ($data[$kolom] ?? '');
            }
        }

        $icdBoleh = in_array('icd', $izin, true);

        $ubahPenyakit = $icdBoleh
            && array_key_exists('penyakit_id', $data);

        $ubahTindakan = $icdBoleh
            && array_key_exists('tindakan_id', $data);

        $diagnosisIds = $ubahPenyakit
            ? array_values(array_unique(array_map(
                'intval',
                $data['penyakit_id'] ?? []
            )))
            : [];

        $tindakanIds = $ubahTindakan
            ? array_values(array_unique(array_map(
                'intval',
                $data['tindakan_id'] ?? []
            )))
            : [];

        /*
         * PEMERIKSAAN KHUSUS POLI OBGYN
         *
         * Tindakan IUD (ICD-9-CM 69.7) hanya dapat disimpan
         * jika Informed Consent sudah disetujui.
         *
         * Berlaku untuk admin/dokter yang menyimpan A/P
         * dan PMIK yang mengubah tindakan ICD-9-CM.
         */
        $roleBolehAssPlan =
            in_array('assessment', $izin, true)
            || in_array('plan', $izin, true);

        if (
            $request->routeIs('poli.obgyn.assesmen.store')
            && ($roleBolehAssPlan || $ubahTindakan)
        ) {
            $assesmenAda = Assesmen::where(
                'id_pendaftaran',
                $idPendaftaran
            )->first();

            // Gunakan tindakan yang dikirim atau yang sudah tersimpan.
            $idTindakanPeriksa = $ubahTindakan
                ? $tindakanIds
                : ($assesmenAda
                    ? DB::table('detail_tindakan')
                        ->where('id_assesmen', $assesmenAda->id_assesmen)
                        ->pluck('id_kode9')
                        ->map(fn ($id) => (int) $id)
                        ->all()
                    : []);

            $adaIud = !empty($idTindakanPeriksa)
                && DB::table('kode_tindakan')
                    ->whereIn('id_icd9', $idTindakanPeriksa)
                    ->where('icd_9', 'like', '69.7%')
                    ->exists();

            if ($adaIud) {
                $consentDisetujui = DB::table('informed_consent')
                    ->where('id_kunjungan', $idPendaftaran)
                    ->where('keputusan', 'setuju')
                    ->exists();

                if (!$consentDisetujui) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tindakan IUD belum dapat disimpan. Lengkapi dan simpan Informed Consent dengan keputusan setuju terlebih dahulu.',
                        'requires_informed_consent' => true,
                    ], 422);
                }
            }
        }

        try {
            // Ambil data lama sebelum diubah untuk audit trail.
            $assesmenLama = Assesmen::where(
                'id_pendaftaran',
                $idPendaftaran
            )->first();

            $soapLama = null;

            if ($assesmenLama) {
                $soapLama = [];

                foreach (array_keys($petaSoap) as $kolom) {
                    $soapLama[$kolom] = (string) (
                        $assesmenLama->{$kolom} ?? ''
                    );
                }
            }

            $diagnosisLama = $assesmenLama
                ? DB::table('detail_diagnosis')
                    ->where('id_assesmen', $assesmenLama->id_assesmen)
                    ->pluck('id_kode10')
                    ->map(fn ($id) => (int) $id)
                    ->all()
                : [];

            $tindakanLama = $assesmenLama
                ? DB::table('detail_tindakan')
                    ->where('id_assesmen', $assesmenLama->id_assesmen)
                    ->pluck('id_kode9')
                    ->map(fn ($id) => (int) $id)
                    ->all()
                : [];

            /*
             * TRANSAKSI DATABASE
             */
            $idAssesmen = DB::transaction(function () use (
                $idPendaftaran,
                $ubahSoap,
                $ubahPenyakit,
                $ubahTindakan,
                $diagnosisIds,
                $tindakanIds
            ) {
                $assesmen = Assesmen::where(
                    'id_pendaftaran',
                    $idPendaftaran
                )->first();

                if (!$assesmen) {
                    $assesmen = new Assesmen();

                    $assesmen->id_pendaftaran = $idPendaftaran;

                    /*
                     * Kolom lama masih NOT NULL.
                     * Beri nilai kosong agar record baru dapat disimpan.
                     */
                    $assesmen->subjek = '';
                    $assesmen->objek = '';
                    $assesmen->assesmen = '';
                    $assesmen->plan = '';

                    $assesmen->id_kode10 = null;
                    $assesmen->id_kode9 = null;
                }

                // Simpan masing-masing isian ke kolom tersendiri.
                foreach ($ubahSoap as $kolom => $nilai) {
                    $assesmen->{$kolom} = $nilai;
                }

                if ($ubahPenyakit) {
                    $assesmen->id_kode10 = $diagnosisIds[0] ?? null;
                }

                if ($ubahTindakan) {
                    $assesmen->id_kode9 = $tindakanIds[0] ?? null;
                }

                $assesmen->save();

                /*
                 * Perbarui detail diagnosis ICD-10
                 * hanya jika dikirim oleh role yang berhak.
                 */
                if ($ubahPenyakit) {
                    DB::table('detail_diagnosis')
                        ->where('id_assesmen', $assesmen->id_assesmen)
                        ->delete();

                    if (!empty($diagnosisIds)) {
                        $detailDiagnosis = [];

                        foreach ($diagnosisIds as $idKode10) {
                            $detailDiagnosis[] = [
                                'id_assesmen' => $assesmen->id_assesmen,
                                'id_kode10' => $idKode10,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        DB::table('detail_diagnosis')
                            ->insert($detailDiagnosis);
                    }
                }

                /*
                 * Perbarui detail tindakan ICD-9-CM
                 * hanya jika dikirim oleh role yang berhak.
                 */
                if ($ubahTindakan) {
                    DB::table('detail_tindakan')
                        ->where('id_assesmen', $assesmen->id_assesmen)
                        ->delete();

                    if (!empty($tindakanIds)) {
                        $detailTindakan = [];

                        foreach ($tindakanIds as $idKode9) {
                            $detailTindakan[] = [
                                'id_assesmen' => $assesmen->id_assesmen,
                                'id_kode9' => $idKode9,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }

                        DB::table('detail_tindakan')
                            ->insert($detailTindakan);
                    }
                }

                return $assesmen->id_assesmen;
            });

            /*
             * AUDIT TRAIL
             */
            $modul = $request->routeIs('poli.obgyn.assesmen.store')
                ? 'Poli Obgyn'
                : 'Poli Syaraf';

            $noRm = Pendaftaran::where(
                'id_pendaftaran',
                $idPendaftaran
            )->value('no_rm');

            if ($soapLama === null) {
                Audit::catat(
                    'Menyimpan SOAP dan kode ICD',
                    $modul,
                    $noRm
                );
            } else {
                $labelSoap = [
                    'keluhan_utama' => 'Keluhan Utama',
                    'riwayat_penyakit_sekarang' => 'Riwayat Penyakit Sekarang',
                    'riwayat_penyakit_dahulu' => 'Riwayat Penyakit Dahulu',
                    'riwayat_penyakit_keluarga' => 'Riwayat Penyakit Keluarga',
                    'riwayat_pengobatan' => 'Riwayat Pengobatan',
                    'riwayat_sosial' => 'Riwayat Sosial',
                    'riwayat_operasi' => 'Riwayat Operasi',
                    'riwayat_alergi' => 'Riwayat Alergi',
                    'riwayat_imunisasi' => 'Riwayat Imunisasi',
                    'pemeriksaan_fisik_lainnya' => 'Pemeriksaan Fisik Lainnya',
                    'dx_utama' => 'Diagnosis Utama',
                    'dx_sekunder' => 'Diagnosis Sekunder',
                    'tindakan_medis' => 'Tindakan Medis',
                    'konseling_edukasi' => 'Konseling Edukasi',
                    'rencana_tindak_lanjut' => 'Rencana Tindak Lanjut',
                    'diet' => 'Diet',
                    'resep_obat' => 'Resep Obat',
                ];

                // Catat perubahan masing-masing kolom SOAP.
                foreach ($ubahSoap as $kolom => $nilaiBaru) {
                    if (($soapLama[$kolom] ?? '') !== $nilaiBaru) {
                        Audit::catat(
                            'Mengubah ' . $labelSoap[$kolom],
                            $modul,
                            $noRm
                        );
                    }
                }

                $sama = function (array $a, array $b) {
                    sort($a);
                    sort($b);

                    return $a === $b;
                };

                if (
                    $ubahPenyakit
                    && !$sama($diagnosisLama, $diagnosisIds)
                ) {
                    Audit::catat(
                        'Mengubah diagnosis ICD-10: ['
                            . $this->kodeDiagnosis($diagnosisLama)
                            . '] menjadi ['
                            . $this->kodeDiagnosis($diagnosisIds) . ']',
                        $modul,
                        $noRm
                    );
                }

                if (
                    $ubahTindakan
                    && !$sama($tindakanLama, $tindakanIds)
                ) {
                    Audit::catat(
                        'Mengubah tindakan ICD-9-CM: ['
                            . $this->kodeTindakan($tindakanLama)
                            . '] menjadi ['
                            . $this->kodeTindakan($tindakanIds) . ']',
                        $modul,
                        $noRm
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'id_assesmen' => $idAssesmen,
            ]);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Penyimpanan SOAP dan ICD gagal. Periksa log Laravel untuk detail error.',
            ], 500);
        }
    }

    /**
     * Mengubah daftar ID diagnosis menjadi daftar kode ICD-10.
     */
    private function kodeDiagnosis(array $ids): string
    {
        if (empty($ids)) {
            return '-';
        }

        return DB::table('kode_diagnosis')
            ->whereIn('id_icd10', $ids)
            ->pluck('icd_10')
            ->implode(', ');
    }

    /**
     * Mengubah daftar ID tindakan menjadi daftar kode ICD-9-CM.
     */
    private function kodeTindakan(array $ids): string
    {
        if (empty($ids)) {
            return '-';
        }

        return DB::table('kode_tindakan')
            ->whereIn('id_icd9', $ids)
            ->pluck('icd_9')
            ->implode(', ');
    }
}
