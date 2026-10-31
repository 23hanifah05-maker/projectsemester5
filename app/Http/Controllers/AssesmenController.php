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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => [
                'required',
                'integer',
                'exists:pendaftaran,id_pendaftaran',
            ],

            'subjective' => ['present', 'nullable', 'string'],
            'objective' => ['present', 'nullable', 'string'],
            'assessment' => ['present', 'nullable', 'string'],
            'plan' => ['present', 'nullable', 'string'],

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

        /*
         * PEMERIKSAAN KHUSUS POLI OBGYN
         * Poli Saraf tidak terkena pemeriksaan ini.
         */
        if ($request->routeIs('poli.obgyn.assesmen.store')) {
            $idPendaftaran = (int) $data['id_pendaftaran'];

            $tindakanIds = array_map(
                'intval',
                $data['tindakan_id'] ?? []
            );

            // Periksa apakah tindakan IUD (kode ICD-9-CM 69.7) dipilih.
            $adaIud = !empty($tindakanIds)
                && DB::table('kode_tindakan')
                    ->whereIn('id_icd9', $tindakanIds)
                    ->where('icd_9', 'like', '69.7%')
                    ->exists();

            if ($adaIud) {
                // Consent harus tersimpan untuk kunjungan yang sama
                // dan keputusan pasien harus "setuju".
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
            $assesmenLama = Assesmen::where(
                'id_pendaftaran',
                (int) $data['id_pendaftaran']
            )->first();

            $soapLama = $assesmenLama
                ? [
                    'subjek'    => (string) $assesmenLama->subjek,
                    'objek'     => (string) $assesmenLama->objek,
                    'assesmen'  => (string) $assesmenLama->assesmen,
                    'plan'      => (string) $assesmenLama->plan,
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

            $idAssesmen = DB::transaction(function () use ($data) {
                $idPendaftaran = (int) $data['id_pendaftaran'];

                $diagnosisIds = array_values(array_unique(array_map(
                    'intval',
                    $data['penyakit_id'] ?? []
                )));

                $tindakanIds = array_values(array_unique(array_map(
                    'intval',
                    $data['tindakan_id'] ?? []
                )));

                // Simpan SOAP dan kode utama.
                $assesmen = Assesmen::updateOrCreate(
                    ['id_pendaftaran' => $idPendaftaran],
                    [
                        'subjek' => $data['subjective'] ?? '',
                        'objek' => $data['objective'] ?? '',
                        'assesmen' => $data['assessment'] ?? '',
                        'plan' => $data['plan'] ?? '',
                        'id_kode10' => $diagnosisIds[0] ?? null,
                        'id_kode9' => $tindakanIds[0] ?? null,
                    ]
                );

                // Perbarui detail diagnosis.
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

                // Perbarui detail tindakan.
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

                return $assesmen->id_assesmen;
            });

            // ===== Audit trail =====
            $modul = $request->routeIs('poli.obgyn.assesmen.store')
                ? 'Poli Obgyn'
                : 'Poli Syaraf';

            $noRm = Pendaftaran::where(
                'id_pendaftaran',
                (int) $data['id_pendaftaran']
            )->value('no_rm');

            $diagnosisBaru = array_values(array_unique(array_map(
                'intval',
                $data['penyakit_id'] ?? []
            )));

            $tindakanBaru = array_values(array_unique(array_map(
                'intval',
                $data['tindakan_id'] ?? []
            )));

            if ($soapLama === null) {
                Audit::catat('Menyimpan SOAP dan kode ICD', $modul, $noRm);
            } else {
                $soapBaru = [
                    'subjek'   => (string) ($data['subjective'] ?? ''),
                    'objek'    => (string) ($data['objective'] ?? ''),
                    'assesmen' => (string) ($data['assessment'] ?? ''),
                    'plan'     => (string) ($data['plan'] ?? ''),
                ];

                $labelSoap = [
                    'subjek'   => 'Subjective (S)',
                    'objek'    => 'Objective (O)',
                    'assesmen' => 'Assessment (A)',
                    'plan'     => 'Plan (P)',
                ];

                foreach ($soapBaru as $field => $nilaiBaru) {
                    if ($soapLama[$field] !== $nilaiBaru) {
                        Audit::catat(
                            'Mengubah ' . $labelSoap[$field],
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

                if (!$sama($diagnosisLama, $diagnosisBaru)) {
                    Audit::catat(
                        'Mengubah diagnosis ICD-10: ['
                            . $this->kodeDiagnosis($diagnosisLama)
                            . '] menjadi ['
                            . $this->kodeDiagnosis($diagnosisBaru) . ']',
                        $modul,
                        $noRm
                    );
                }

                if (!$sama($tindakanLama, $tindakanBaru)) {
                    Audit::catat(
                        'Mengubah tindakan ICD-9-CM: ['
                            . $this->kodeTindakan($tindakanLama)
                            . '] menjadi ['
                            . $this->kodeTindakan($tindakanBaru) . ']',
                        $modul,
                        $noRm
                    );
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'SOAP dan kode ICD berhasil disimpan.',
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