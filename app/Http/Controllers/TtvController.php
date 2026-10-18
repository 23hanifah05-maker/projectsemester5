<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Ttv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TtvController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_pendaftaran' => [
                'required',
                'integer',
                'exists:pendaftaran,id_pendaftaran',
            ],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'suhu' => ['nullable', 'numeric', 'min:20', 'max:50'],
            'heart_rate' => ['nullable', 'integer', 'min:0', 'max:300'],
            'respiratory_rate' => ['nullable', 'integer', 'min:0', 'max:100'],
            'spo2' => ['nullable', 'integer', 'min:0', 'max:100'],
            'alergi' => ['nullable', 'string', 'max:255'],

            // Khusus input tambahan Poli Obgyn
            'hpht' => ['nullable', 'date', 'before_or_equal:today'],
            'usia_kehamilan' => ['nullable', 'integer', 'min:0', 'max:50'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data TTV belum valid. Periksa kembali isian.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $pendaftaran = Pendaftaran::where(
            'id_pendaftaran',
            $validated['id_pendaftaran']
        )
            ->whereHas('poli', function ($query) {
                $query->whereIn('slug', ['saraf', 'obgyn']);
            })
            ->first();

        if (!$pendaftaran) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran Poli Syaraf atau Obgyn tidak ditemukan.',
                'errors' => [
                    'id_pendaftaran' => [
                        'Pendaftaran tidak ditemukan atau bukan berasal dari poli yang diizinkan.',
                    ],
                ],
            ], 404);
        }

        try {
            $dataTtv = [
                'tekanan_darah' => $validated['tekanan_darah'] ?? null,
                'suhu' => $validated['suhu'] ?? null,
                'heart_rate' => $validated['heart_rate'] ?? null,
                'respiratory_rate' => $validated['respiratory_rate'] ?? null,
                'spo2' => $validated['spo2'] ?? null,
                'alergi' => $validated['alergi'] ?? null,
            ];

            // Tambahkan data kehamilan hanya jika dikirim oleh form.
            // Jika form Syaraf tidak mengirimnya, nilai sebelumnya
            // tidak diubah.
            if ($request->exists('hpht')) {
                $dataTtv['hpht'] = $validated['hpht'] ?? null;
            }

            if ($request->exists('usia_kehamilan')) {
                $dataTtv['usia_kehamilan'] =
                    $validated['usia_kehamilan'] ?? null;
            }

            $ttv = Ttv::updateOrCreate(
                [
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                ],
                $dataTtv
            );

            return response()->json([
                'success' => true,
                'message' => 'Data TTV berhasil disimpan.',
                'data' => $ttv,
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data TTV.',
            ], 500);
        }
    }
}
