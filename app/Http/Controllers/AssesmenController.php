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

            // Diagnosis boleh kosong.
            'penyakit_id' => ['sometimes', 'nullable', 'array'],
            'penyakit_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:kode_diagnosis,id_icd10',
            ],

            // Tindakan boleh kosong.
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
                // Jika tidak ada pilihan, kode utama bernilai NULL.
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

                // Perbarui detail diagnosis sesuai pilihan terbaru.
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

                // Perbarui detail tindakan sesuai pilihan terbaru.
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
