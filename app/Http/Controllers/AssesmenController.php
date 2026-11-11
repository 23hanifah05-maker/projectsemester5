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
     *   admin   : S, O, A, P, dan kode ICD
     *   dokter  : S, O, A, P
     *   perawat : S, O
     *   pmik    : kode ICD saja
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

            // Field SOAP hanya diperiksa bila dikirim (tiap role mengirim bagiannya saja).
            'subjective' => ['sometimes', 'nullable', 'string'],
            'objective' => ['sometimes', 'nullable', 'string'],
            'assessment' => ['sometimes', 'nullable', 'string'],
            'plan' => ['sometimes', 'nullable', 'string'],

            'penyakit_id' => ['sometimes', 'nullable', 'array'],
            'penyakit_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:kode_diagnosis,id_icd10',
            ],

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

        // ------------------------------------------------------------------
        // Tentukan bagian mana yang benar-benar boleh diubah oleh role ini.
        // Field yang tidak diizinkan diabaikan walaupun dikirim lewat cara lain.
        // ------------------------------------------------------------------
        $petaSoap = [
            'subjective' => 'subjek',
            'objective'  => 'objek',
            'assessment' => 'assesmen',
            'plan'       => 'plan',
        ];

        $ubahSoap = [];

        foreach ($petaSoap as $input => $kolom) {
            if (in_array($input, $izin, true) && $request->has($input)) {
                $ubahSoap[$kolom] = (string) ($data[$input] ?? '');
            }
        }

        $icdBoleh = in_array('icd', $izin, true);
        $ubahPenyakit = $icdBoleh && $request->has('penyakit_id');
        $ubahTindakan = $icdBoleh && $request->has('tindakan_id');

        $diagnosisIds = $ubahPenyakit
            ? array_values(array_unique(array_map('intval', $data['penyakit_id'] ?? [])))
            : [];

        $tindakanIds = $ubahTindakan
            ? array_values(array_unique(array_map('intval', $data['tindakan_id'] ?? [])))
            : [];

        /*
         * PEMERIKSAAN KHUSUS POLI OBGYN (tindakan IUD, ICD-9-CM 69.7)
         * Berlaku saat dokter / admin menyimpan asesmen: Informed Consent
         * harus sudah tersimpan dengan keputusan "setuju".
         * PMIK yang menyimpan kode ICD tidak diperiksa, karena consent baru
         * bisa diisi dokter setelah kode tindakan IUD tersimpan.
         */
        $role_boleh_assplan = in_array('assessment', $izin, true) || in_array('plan', $izin, true);

        if ($request->routeIs('poli.obgyn.assesmen.store') && $role_boleh_assplan) {
            $assesmenAda = Assesmen::where('id_pendaftaran', $idPendaftaran)->first();

            // Pakai tindakan yang dikirim; bila tidak, pakai yang sudah tersimpan.
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
            // Ambil data lama sebelum diubah, untuk audit trail.
            $assesmenLama = Assesmen::where('id_pendaftaran', $idPendaftaran)->first();

            $soapLama = $assesmenLama
                ? [
                    'subjek'   => (string) $assesmenLama->subjek,
                    'objek'    => (string) $assesmenLama->objek,
                    'assesmen' => (string) $assesmenLama->assesmen,
                    'plan'     => (string) $assesmenLama->plan,
                ]
                : null;

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

            $idAssesmen = DB::transaction(function () use (
                $idPendaftaran,
                $ubahSoap,
                $ubahPenyakit,
                $ubahTindakan,
                $diagnosisIds,
                $tindakanIds
            ) {
                $assesmen = Assesmen::where('id_pendaftaran', $idPendaftaran)->first();

                if (!$assesmen) {
                    $assesmen = new Assesmen();
                    $assesmen->id_pendaftaran = $idPendaftaran;

                    // Nilai awal untuk data baru (kolom SOAP tidak boleh null).
                    $assesmen->subjek = '';
                    $assesmen->objek = '';
                    $assesmen->assesmen = '';
                    $assesmen->plan = '';
                    $assesmen->id_kode10 = null;
                    $assesmen->id_kode9 = null;
                }

                // Hanya kolom yang diizinkan dan dikirim yang diubah.
                // Kolom lain (mis. A dan P milik dokter) tidak tersentuh.
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

                // Perbarui detail diagnosis (hanya bila dikirim oleh role yang berhak).
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

                        DB::table('detail_diagnosis')->insert($detailDiagnosis);
                    }
                }

                // Perbarui detail tindakan (hanya bila dikirim oleh role yang berhak).
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

                        DB::table('detail_tindakan')->insert($detailTindakan);
                    }
                }

                return $assesmen->id_assesmen;
            });

            // ===== Audit trail =====
            $modul = $request->routeIs('poli.obgyn.assesmen.store')
                ? 'Poli Obgyn'
                : 'Poli Syaraf';

            $noRm = Pendaftaran::where('id_pendaftaran', $idPendaftaran)->value('no_rm');

            if ($soapLama === null) {
                Audit::catat('Menyimpan SOAP dan kode ICD', $modul, $noRm);
            } else {
                $labelSoap = [
                    'subjek'   => 'Subjective (S)',
                    'objek'    => 'Objective (O)',
                    'assesmen' => 'Assessment (A)',
                    'plan'     => 'Plan (P)',
                ];

                // Hanya field yang memang diubah role ini yang dibandingkan.
                foreach ($ubahSoap as $kolom => $nilaiBaru) {
                    if ($soapLama[$kolom] !== $nilaiBaru) {
                        Audit::catat('Mengubah ' . $labelSoap[$kolom], $modul, $noRm);
                    }
                }

                $sama = function (array $a, array $b) {
                    sort($a);
                    sort($b);

                    return $a === $b;
                };

                if ($ubahPenyakit && !$sama($diagnosisLama, $diagnosisIds)) {
                    Audit::catat(
                        'Mengubah diagnosis ICD-10: ['
                            . $this->kodeDiagnosis($diagnosisLama)
                            . '] menjadi ['
                            . $this->kodeDiagnosis($diagnosisIds) . ']',
                        $modul,
                        $noRm
                    );
                }

                if ($ubahTindakan && !$sama($tindakanLama, $tindakanIds)) {
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