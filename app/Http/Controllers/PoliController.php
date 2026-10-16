<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Ttv;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    /**
     * Mengubah kode jenis kelamin menjadi label.
     */
    private function formatJenisKelamin($kode)
    {
        $petaJenisKelamin = [
            0 => 'Tidak diketahui',
            1 => 'Laki-laki',
            2 => 'Perempuan',
            3 => 'Tidak dapat ditentukan',
            4 => 'Tidak dikonfirmasi',
        ];

        if ($kode === null || $kode === '') {
            return '-';
        }

        // Jika nilainya sudah berupa teks, jangan dikonversi lagi.
        if (!is_numeric($kode)) {
            return $kode;
        }

        return $petaJenisKelamin[(int) $kode] ?? '-';
    }

    /**
     * Halaman utama Poli Saraf.
     * Data pendaftaran dan pasien diambil dari database.
     */
    public function saraf(Request $request)
    {
        $poli = Poli::where('slug', 'saraf')->firstOrFail();

        $query = Pendaftaran::with(['pasien', 'poli'])
            ->where('id_poli', $poli->id_poli);

        if ($request->filled('dari')) {
            $query->whereDate(
                'tgl_registrasi',
                '>=',
                $request->input('dari')
            );
        }

        if ($request->filled('sampai')) {
            $query->whereDate(
                'tgl_registrasi',
                '<=',
                $request->input('sampai')
            );
        }

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));

            $query->whereHas('pasien', function ($q) use ($keyword) {
                $q->where('nama_pasien', 'like', "%{$keyword}%")
                    ->orWhere('nik', 'like', "%{$keyword}%")
                    ->orWhere('no_rm', 'like', "%{$keyword}%");
            });
        }

        $pendaftaran = $query
            ->orderByDesc('tgl_registrasi')
            ->orderByDesc('id_pendaftaran')
            ->get();

        $pasien = $pendaftaran
            ->filter(function ($item) {
                return $item->pasien !== null;
            })
            ->map(function ($item) {
                $p = $item->pasien;

                $petaAgama = [
                    1 => 'Islam',
                    2 => 'Kristen',
                    3 => 'Katolik',
                    4 => 'Hindu',
                    5 => 'Buddha',
                    6 => 'Konghucu',
                ];

                $petaPendidikan = [
                    0 => 'Tidak diketahui',
                    1 => 'Tidak sekolah',
                    2 => 'SD',
                    3 => 'SMP',
                    4 => 'SMA',
                    5 => 'D1/D2',
                    6 => 'D3',
                    7 => 'S1',
                    8 => 'S2/S3',
                ];

                $petaPekerjaan = [
                    0 => 'Tidak diketahui',
                    1 => 'Tidak bekerja',
                    2 => 'PNS',
                    3 => 'TNI/Polri',
                    4 => 'Swasta',
                    5 => 'Lainnya',
                ];

                return (object) [
                    'id_pendaftaran' => $item->id_pendaftaran,
                    'tgl_registrasi' => $item->tgl_registrasi,
                    'no_rm' => $p->no_rm,
                    'nama_pasien' => $p->nama_pasien,
                    'nik' => $p->nik,
                    'tgl_lahir' => $p->tgl_lahir,
                    'tempat_lahir' => $p->tempat_lahir,

                    'jenis_kelamin' => $this->formatJenisKelamin(
                        $p->jenis_kelamin
                    ),

                    'alamat' => $p->alamat,
                    'no_hp' => $p->no_hp,
                    'suku' => $p->suku,
                    'bangsa' => $p->bangsa,
                    'bahasa' => $p->bahasa,

                    'umur' => $p->tgl_lahir
                        ? Carbon::parse($p->tgl_lahir)->age . ' Tahun'
                        : '-',

                    'kelurahan' => $p->kelurahan,

                    'agama' => $petaAgama[(int) $p->agama] ?? '-',
                    'kecamatan' => $p->kecamatan,
                    'pendidikan' => $petaPendidikan[(int) $p->pendidikan] ?? '-',
                    'kabupaten' => $p->kabupaten,
                    'pekerjaan' => $petaPekerjaan[(int) $p->pekerjaan] ?? '-',

                    'provinsi' => $p->provinsi,
                    'kode_pos' => $p->kode_pos,
                    'pembayaran' => $item->jenis_pembayaran,
                    'poli' => $item->poli->nama_poli ?? 'Poli Saraf',
                    'dokter' => $item->dpjp ?? '',
                    'pj_nama' => $p->pj_nama,
                    'pj_hubungan' => $p->pj_hubungan,

                    'pj_jenis_kelamin' => $this->formatJenisKelamin(
                        $p->pj_jenis_kelamin
                    ),

                    'pj_no_hp' => $p->pj_no_hp,
                    'status_periksa' => 'periksa',
                ];
            })
            ->values();

        return view('poli.syaraf', compact('poli', 'pasien'));
    }

    /**
     * Halaman riwayat pasien Poli Saraf.
     */
    public function riwayatSaraf($no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $riwayat = Pendaftaran::with('poli')
            ->where('no_rm', $no_rm)
            ->orderByDesc('tgl_registrasi')
            ->orderByDesc('id_pendaftaran')
            ->get();

        $jenisKelamin = $this->formatJenisKelamin(
            $pasien->jenis_kelamin
        );

        // Sediakan juga nilai yang sudah dikonversi pada objek pasien.
        $pasien->jenis_kelamin = $jenisKelamin;

        return view('poli.syaraf-riwayat', [
            'pasien' => $pasien,
            'riwayat' => $riwayat,
            'jenisKelamin' => $jenisKelamin,
        ]);
    }

    /**
     * Membuka kunjungan terbaru pasien di Poli Saraf.
     */
    public function kunjunganSaraf($no_rm)
    {
        $poli = Poli::where('slug', 'saraf')->firstOrFail();

        $pendaftaran = Pendaftaran::where('no_rm', $no_rm)
            ->where('id_poli', $poli->id_poli)
            ->orderByDesc('tgl_registrasi')
            ->orderByDesc('id_pendaftaran')
            ->firstOrFail();

        return $this->tampilkanKunjunganSaraf($pendaftaran);
    }

    /**
     * Membuka kunjungan berdasarkan nomor RM dan tanggal.
     */
    public function kunjunganSarafDetail($no_rm, $tanggal)
    {
        $poli = Poli::where('slug', 'saraf')->firstOrFail();

        $pendaftaran = Pendaftaran::where('no_rm', $no_rm)
            ->where('id_poli', $poli->id_poli)
            ->whereDate('tgl_registrasi', $tanggal)
            ->orderByDesc('id_pendaftaran')
            ->firstOrFail();

        return $this->tampilkanKunjunganSaraf($pendaftaran);
    }

    /**
     * Mengambil data pasien, pendaftaran, dan TTV
     * untuk halaman formulir kunjungan.
     */
    private function tampilkanKunjunganSaraf(
        Pendaftaran $pendaftaran
    ) {
        $pasien = Pasien::where(
            'no_rm',
            $pendaftaran->no_rm
        )->firstOrFail();

        $ttv = Ttv::where(
            'id_pendaftaran',
            $pendaftaran->id_pendaftaran
        )->first();

        $jenisKelamin = $this->formatJenisKelamin(
            $pasien->jenis_kelamin
        );

        $pjJenisKelamin = $this->formatJenisKelamin(
            $pasien->pj_jenis_kelamin
        );

        $umur = $pasien->tgl_lahir
    ? Carbon::parse($pasien->tgl_lahir)->age . ' Tahun'
    : '-';

$pasien->umur = $umur;

        // Pastikan Blade yang memakai $pasien juga menerima label teks.
        $pasien->jenis_kelamin = $jenisKelamin;
        $pasien->pj_jenis_kelamin = $pjJenisKelamin;

        return view('poli.syaraf-kunjungan-baru', [
            'no_rm' => $pasien->no_rm,
            'pasien' => $pasien,
            'semuaPasien' => collect([$pasien]),
            'pendaftaran' => $pendaftaran,
            'id_pendaftaran' => $pendaftaran->id_pendaftaran,
            'ttv' => $ttv,
            'jenisKelamin' => $jenisKelamin,
            'pjJenisKelamin' => $pjJenisKelamin,
        ]);
    }

    /**
     * Halaman Poli Obgyn.
     */
    public function obgyn()
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();

        return view('poli.obgyn', compact('poli'));
    }

    /**
     * Halaman Poli Jantung.
     */
    public function jantung()
    {
        $poli = Poli::where('slug', 'jantung')->firstOrFail();

        return view('poli.jantung', compact('poli'));
    }

    /**
     * Halaman Poli Jiwa.
     */
    public function jiwa()
    {
        $poli = Poli::where('slug', 'jiwa')->firstOrFail();

        return view('poli.jiwa', compact('poli'));
    }
}
