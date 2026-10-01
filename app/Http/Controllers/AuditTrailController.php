<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        // DATA SEMENTARA (dummy). Nanti ganti dengan query dari tabel audit_trails.
        $logs = collect([
            ['id' => 2, 'username' => 'admin2', 'role' => 'admin', 'aktivitas' => 'Edit',   'modul' => 'Pendaftaran', 'waktu' => '2026-10-01 09:23:00', 'ip' => '127.0.0.1',
             'no_rm' => 'E123456', 'kolom' => 'Alamat', 'sebelum' => 'Gerih, Geneng, Ngawi', 'sesudah' => 'Gerih RT 05 RW 01 Ds. Geneng Kec. Geneng Kab. Ngawi', 'status' => 'Berhasil'],
            ['id' => 2, 'username' => 'admin2', 'role' => 'admin', 'aktivitas' => 'Tambah', 'modul' => 'Pendaftaran', 'waktu' => '2026-10-01 09:11:00', 'ip' => '127.0.0.1',
             'no_rm' => 'E123457', 'kolom' => '-', 'sebelum' => '-', 'sesudah' => 'Data pasien baru ditambahkan', 'status' => 'Berhasil'],
            ['id' => 2, 'username' => 'admin2', 'role' => 'admin', 'aktivitas' => 'Tambah', 'modul' => 'Pendaftaran', 'waktu' => '2026-10-01 08:57:00', 'ip' => '127.0.0.1',
             'no_rm' => 'E123458', 'kolom' => '-', 'sebelum' => '-', 'sesudah' => 'Data pasien baru ditambahkan', 'status' => 'Berhasil'],
            ['id' => 2, 'username' => 'admin2', 'role' => 'admin', 'aktivitas' => 'Tambah', 'modul' => 'Pendaftaran', 'waktu' => '2026-10-01 08:45:00', 'ip' => '127.0.0.1',
             'no_rm' => 'E123459', 'kolom' => '-', 'sebelum' => '-', 'sesudah' => 'Data pasien baru ditambahkan', 'status' => 'Berhasil'],
            ['id' => 2, 'username' => 'admin2', 'role' => 'admin', 'aktivitas' => 'Login',  'modul' => 'Autentikasi', 'waktu' => '2026-10-01 08:12:00', 'ip' => '127.0.0.1',
             'no_rm' => '-', 'kolom' => '-', 'sebelum' => '-', 'sesudah' => '-', 'status' => 'Berhasil'],
            ['id' => 2, 'username' => 'admin2', 'role' => 'admin', 'aktivitas' => 'Login',  'modul' => 'Autentikasi', 'waktu' => '2026-10-01 08:10:00', 'ip' => '127.0.0.1',
             'no_rm' => '-', 'kolom' => '-', 'sebelum' => '-', 'sesudah' => '-', 'status' => 'Berhasil'],
        ]);

        // Filter keyword
        if ($request->filled('keyword')) {
            $kw = strtolower($request->keyword);
            $logs = $logs->filter(function ($row) use ($kw) {
                return str_contains(strtolower(implode(' ', $row)), $kw);
            });
        }

        // Filter periode
        if ($request->filled('dari')) {
            $dari = Carbon::parse($request->dari)->startOfDay();
            $logs = $logs->filter(fn ($row) => Carbon::parse($row['waktu'])->gte($dari));
        }
        if ($request->filled('sampai')) {
            $sampai = Carbon::parse($request->sampai)->endOfDay();
            $logs = $logs->filter(fn ($row) => Carbon::parse($row['waktu'])->lte($sampai));
        }

        return view('audit-trail.index', ['logs' => $logs->values()]);
    }
}