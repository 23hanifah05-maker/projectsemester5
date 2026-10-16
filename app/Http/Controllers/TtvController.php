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
            'id_pendaftaran' => ['required', 'integer'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'suhu' => ['nullable', 'numeric', 'min:20', 'max:50'],
            'heart_rate' => ['nullable', 'integer', 'min:0', 'max:300'],
            'respiratory_rate' => ['nullable', 'integer', 'min:0', 'max:100'],
            'spo2' => ['nullable', 'integer', 'min:0', 'max:100'],
            'alergi' => ['nullable', 'string', 'max:255'],
        ]);

        // Validasi data
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data TTV belum valid. Periksa kembali isian.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        // Pastikan pendaftaran benar-benar berasal dari Poli Saraf
        $pendaftaran = Pendaftaran::where(
            'id_pendaftaran',
            $validated['id_pendaftaran']
        )
            ->whereHas('poli', function ($query) {
                $query->where('slug', 'saraf');
            })
            ->first();

        if (!$pendaftaran) {
            $message = 'Data pendaftaran Poli Saraf tidak ditemukan.';

            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => [
                    'id_pendaftaran' => [$message],
                ],
            ], 404);
        }

        try {
            // Simpan atau perbarui data TTV untuk pendaftaran ini
            $ttv = Ttv::updateOrCreate(
                [
                    'id_pendaftaran' => $pendaftaran->id_pendaftaran,
                ],
                [
                    'tekanan_darah' => $validated['tekanan_darah'] ?? null,
                    'suhu' => $validated['suhu'] ?? null,
                    'heart_rate' => $validated['heart_rate'] ?? null,
                    'respiratory_rate' => $validated['respiratory_rate'] ?? null,
                    'spo2' => $validated['spo2'] ?? null,
                    'alergi' => $validated['alergi'] ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Data TTV berhasil disimpan.',
                'data' => $ttv,
            ], 200);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data TTV.',
            ], 500);
        }
    }
}
