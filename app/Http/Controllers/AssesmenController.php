<?php

namespace App\Http\Controllers;

use App\Models\Assesmen;
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
}
