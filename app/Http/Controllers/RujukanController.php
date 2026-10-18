<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RujukanController extends Controller
{
    public function kirim(Request $request)
    {
        $request->validate([
            'no_rm' => 'required|exists:pasien,no_rm',
            'tujuan' => 'required|exists:poli,slug',
            'alasan' => 'required|string',
            'tanggal' => 'nullable|date',
        ]);

        $pasien = Pasien::where('no_rm', $request->no_rm)->firstOrFail();

        $poliTujuan = Poli::where('slug', $request->tujuan)->firstOrFail();

        // Gunakan dokter yang terdaftar pada poli tujuan.
        $dokterTujuan = $poliTujuan->nama_dokter;

        // Ambil pendaftaran pasien sebelumnya untuk menyalin
        // jenis pembayaran tanpa mengubah pendaftaran lama.
        $pendaftaranAsal = Pendaftaran::where('no_rm', $pasien->no_rm)
            ->latest('id_pendaftaran')
            ->firstOrFail();

        DB::transaction(function () use (
            $pasien,
            $poliTujuan,
            $dokterTujuan,
            $pendaftaranAsal,
            $request
        ) {
            Pendaftaran::create([
                'tgl_registrasi' => now(),
                'no_rm' => $pasien->no_rm,
                'id_poli' => $poliTujuan->id_poli,
                'dpjp' => $dokterTujuan,
                'jenis_pembayaran' => $pendaftaranAsal->jenis_pembayaran,
                'status_periksa' => 'periksa',
            ]);
        });

        $routeTujuan = match ($poliTujuan->slug) {
            'saraf' => 'poli.syaraf',
            'obgyn' => 'poli.obgyn',
            'jantung' => 'poli.jantung',
            'jiwa' => 'poli.jiwa',
            'radiologi' => 'radiologi.index',
            default => 'dashboard',
        };

        return redirect()
            ->route($routeTujuan)
            ->with('success', 'Rujukan berhasil dikirim ke ' . $poliTujuan->nama_poli . '.');
    }
}