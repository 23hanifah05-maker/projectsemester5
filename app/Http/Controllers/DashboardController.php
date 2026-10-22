<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------
        | KARTU STATISTIK (tidak terpengaruh filter periode)
        | Sumber: tabel pendaftaran
        |   - tgl_registrasi  : tanggal pasien mendaftar
        |   - status_periksa  : 'periksa' (belum dilayani) / 'selesai'
        |--------------------------------------------------------------
        */
        $totalPasienHariIni = DB::table('pendaftaran')
            ->whereDate('tgl_registrasi', today())
            ->count();

        $totalPasienBulanIni = DB::table('pendaftaran')
            ->whereMonth('tgl_registrasi', now()->month)
            ->whereYear('tgl_registrasi', now()->year)
            ->count();

        $pasienBelumDilayani = DB::table('pendaftaran')
            ->whereDate('tgl_registrasi', today())
            ->where('status_periksa', 'periksa')
            ->count();

        $pasienSelesai = DB::table('pendaftaran')
            ->whereDate('tgl_registrasi', today())
            ->where('status_periksa', 'selesai')
            ->count();

        /*
        |--------------------------------------------------------------
        | FILTER PERIODE (hanya untuk 2 grafik)
        |--------------------------------------------------------------
        */
        $awal  = $request->input('tanggal_awal');
        $akhir = $request->input('tanggal_akhir');

        // Validasi di server (jaga-jaga kalau URL diubah manual)
        if ($awal && $akhir && $akhir < $awal) {
            return redirect()
                ->route('dashboard')
                ->with(
                    'periode_error',
                    'Rentang tanggal tidak valid: tanggal akhir tidak boleh lebih awal dari tanggal mulai.'
                );
        }

        /*
        |--------------------------------------------------------------
        | 10 BESAR PENYAKIT
        | pendaftaran -> assesmen -> detail_diagnosis -> kode_diagnosis
        |--------------------------------------------------------------
        */
        $penyakit = DB::table('detail_diagnosis as dd')
            ->join('assesmen as a', 'a.id_assesmen', '=', 'dd.id_assesmen')
            ->join('pendaftaran as p', 'p.id_pendaftaran', '=', 'a.id_pendaftaran')
            ->join('kode_diagnosis as k', 'k.id_icd10', '=', 'dd.id_kode10')
            ->when($awal, fn ($q) => $q->whereDate('p.tgl_registrasi', '>=', $awal))
            ->when($akhir, fn ($q) => $q->whereDate('p.tgl_registrasi', '<=', $akhir))
            ->select('k.diagnosa as nama', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('k.id_icd10', 'k.diagnosa')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get()
            ->map(fn ($row) => [
                'nama'   => $row->nama,
                'jumlah' => (int) $row->jumlah,
            ])
            ->all();

        /*
        |--------------------------------------------------------------
        | KUNJUNGAN PER POLI
        | Semua poli tampil (walau 0 kunjungan), urutan:
        | Umum, Gigi, Saraf, Obgyn, Jantung, Jiwa, lalu poli lain.
        |--------------------------------------------------------------
        */
        $kunjunganPoli = DB::table('poli as po')
            // Radiologi adalah unit penunjang, bukan poli, jadi tidak ikut grafik
            ->whereRaw("LOWER(COALESCE(po.slug, '')) <> 'radiologi'")
            ->whereRaw("LOWER(po.nama_poli) <> 'radiologi'")
            ->leftJoin('pendaftaran as p', function ($join) use ($awal, $akhir) {
                $join->on('p.id_poli', '=', 'po.id_poli');

                if ($awal) {
                    $join->whereDate('p.tgl_registrasi', '>=', $awal);
                }

                if ($akhir) {
                    $join->whereDate('p.tgl_registrasi', '<=', $akhir);
                }
            })
            ->select('po.nama_poli', DB::raw('COUNT(p.id_pendaftaran) as jumlah'))
            ->groupBy('po.id_poli', 'po.nama_poli')
            ->orderByRaw("
                CASE LOWER(po.slug)
                    WHEN 'umum'    THEN 1
                    WHEN 'gigi'    THEN 2
                    WHEN 'saraf'   THEN 3
                    WHEN 'syaraf'  THEN 3
                    WHEN 'obgyn'   THEN 4
                    WHEN 'jantung' THEN 5
                    WHEN 'jiwa'    THEN 6
                    ELSE 7
                END
            ")
            ->orderByDesc('jumlah')
            ->pluck('jumlah', 'nama_poli')
            ->map(fn ($jumlah) => (int) $jumlah)
            ->all();

        return view('dashboard', compact(
            'totalPasienHariIni',
            'totalPasienBulanIni',
            'pasienBelumDilayani',
            'pasienSelesai',
            'penyakit',
            'kunjunganPoli'
        ));
    }
}