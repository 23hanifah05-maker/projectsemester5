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

            'penyakit_id' => ['required', 'array', 'min:1'],
            'penyakit_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:kode_diagnosis,id_icd10',
            ],

            'tindakan_id' => ['required', 'array', 'min:1'],
            'tindakan_id.*' => [
                'required',
                'integer',
                'distinct',
                'exists:kode_tindakan,id_icd9',
            ],
        ], [
            'id_pendaftaran.required' => 'ID pendaftaran tidak ditemukan.',
            'id_pendaftaran.exists' => 'Data pendaftaran tidak ditemukan.',
            'penyakit_id.required' => 'Pilih minimal satu diagnosis ICD-10.',
            'penyakit_id.min' => 'Pilih minimal satu diagnosis ICD-10.',
            'penyakit_id.*.exists' => 'Salah satu diagnosis ICD-10 tidak ditemukan.',
            'tindakan_id.required' => 'Pilih minimal satu tindakan ICD-9-CM.',
            'tindakan_id.min' => 'Pilih minimal satu tindakan ICD-9-CM.',
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
                $diagnosisIds = array_map(
                    'intval',
                    $data['penyakit_id']
                );
                $tindakanIds = array_map(
                    'intval',
                    $data['tindakan_id']
                );

                // Kolom id_kode10 dan id_kode9 wajib diisi.
                // Gunakan kode pertama sebagai kode utama.
                $assesmen = Assesmen::updateOrCreate(
                    ['id_pendaftaran' => $idPendaftaran],
                    [
                        'subjek' => $data['subjective'] ?? '',
                        'objek' => $data['objective'] ?? '',
                        'assesmen' => $data['assessment'] ?? '',
                        'plan' => $data['plan'] ?? '',
                        'id_kode10' => $diagnosisIds[0],
                        'id_kode9' => $tindakanIds[0],
                    ]
                );

                // Ganti detail ICD dengan pilihan terbaru.
                DB::table('detail_diagnosis')
                    ->where('id_assesmen', $assesmen->id_assesmen)
                    ->delete();

                $detailDiagnosis = [];

                foreach (array_unique($diagnosisIds) as $idKode10) {
                    $detailDiagnosis[] = [
                        'id_assesmen' => $assesmen->id_assesmen,
                        'id_kode10' => $idKode10,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('detail_diagnosis')
                    ->insert($detailDiagnosis);

                DB::table('detail_tindakan')
                    ->where('id_assesmen', $assesmen->id_assesmen)
                    ->delete();

                $detailTindakan = [];

                foreach (array_unique($tindakanIds) as $idKode9) {
                    $detailTindakan[] = [
                        'id_assesmen' => $assesmen->id_assesmen,
                        'id_kode9' => $idKode9,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('detail_tindakan')
                    ->insert($detailTindakan);

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
