<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Ttv;
use App\Models\Assesmen;
use App\Models\InformedConsent; // BARU
use Illuminate\Support\Facades\DB;
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

        if (!is_numeric($kode)) {
            return $kode;
        }

        return $petaJenisKelamin[(int) $kode] ?? '-';
    }

    /**
     * Daftar pasien poli: SATU BARIS PER PASIEN.
     *
     * Aturan status:
     * - "periksa"  : pasien punya kunjungan HARI INI yang belum selesai.
     *                Baris yang dipakai adalah kunjungan hari ini tersebut.
     * - "selesai"  : pasien tidak punya kunjungan hari ini, atau
     *                kunjungan hari ini sudah selesai.
     *                Baris yang dipakai adalah kunjungan terbaru.
     *
     * Kunjungan hari-hari sebelumnya tidak menentukan status.
     */
    private function satuBarisPerPasien($pendaftaran)
    {
        return $pendaftaran
            ->filter(function ($item) {
                return $item->pasien !== null;
            })
            ->groupBy('no_rm')
            ->map(function ($kunjungan) {
                $urut = $kunjungan->sortBy([
                    ['tgl_registrasi', 'desc'],
                    ['id_pendaftaran', 'desc'],
                ]);

                $terbaru = $urut->first();

                $menunggu = $urut->first(function ($k) {
                    return Carbon::parse($k->tgl_registrasi)->isToday()
                        && ($k->status_periksa ?? 'periksa') !== 'selesai';
                });

                $dipakai = clone ($menunggu ?? $terbaru);

                $dipakai->setAttribute(
                    'status_periksa',
                    $menunggu ? 'periksa' : 'selesai'
                );

                return $dipakai;
            })
            ->sort(function ($a, $b) {
                // Yang perlu diperiksa tampil paling atas.
                $statusA = $a->status_periksa === 'selesai' ? 1 : 0;
                $statusB = $b->status_periksa === 'selesai' ? 1 : 0;

                if ($statusA !== $statusB) {
                    return $statusA <=> $statusB;
                }

                $waktuA = Carbon::parse($a->tgl_registrasi)->timestamp;
                $waktuB = Carbon::parse($b->tgl_registrasi)->timestamp;

                if ($waktuA !== $waktuB) {
                    return $waktuA <=> $waktuB;
                }

                return $a->id_pendaftaran <=> $b->id_pendaftaran;
            })
            ->values();
    }

    /**
     * Halaman utama Poli Saraf.
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
            ->orderBy('tgl_registrasi', 'asc')
            ->orderBy('id_pendaftaran', 'asc')
            ->get();

        $pasien = $this->satuBarisPerPasien($pendaftaran)
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
                    'status_periksa' => $item->status_periksa,
                ];
            })
            ->values();

        return view('poli.syaraf', compact('poli', 'pasien'));
    }

    /**
     * Halaman riwayat pasien Poli Saraf.
     * Kunjungan Radiologi tidak ditampilkan di riwayat.
     */
    public function riwayatSaraf($no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $riwayat = Pendaftaran::with('poli')
            ->where('no_rm', $no_rm)
            ->whereHas('poli', function ($q) {
                $q->where('slug', '!=', 'radiologi');
            })
            ->orderByDesc('tgl_registrasi')
            ->orderByDesc('id_pendaftaran')
            ->get();

        $jenisKelamin = $this->formatJenisKelamin(
            $pasien->jenis_kelamin
        );

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
     * Membuka kunjungan Poli Saraf berdasarkan ID pendaftaran.
     */
    public function kunjunganSarafDetail($no_rm, $id_pendaftaran)
    {
        $poli = Poli::where('slug', 'saraf')->firstOrFail();

        $pendaftaran = Pendaftaran::where(
            'id_pendaftaran',
            $id_pendaftaran
        )
            ->where('no_rm', $no_rm)
            ->where('id_poli', $poli->id_poli)
            ->firstOrFail();

        return $this->tampilkanKunjunganSaraf($pendaftaran);
    }

    /**
     * Mengambil data pasien, TTV, SOAP, diagnosis,
     * dan tindakan untuk kunjungan Poli Saraf.
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

        $assesmen = Assesmen::where(
            'id_pendaftaran',
            $pendaftaran->id_pendaftaran
        )->first();

        $diagnosisTersimpan = [];
        $tindakanTersimpan = [];

        if ($assesmen) {
            $diagnosisTersimpan = DB::table('detail_diagnosis')
                ->join(
                    'kode_diagnosis',
                    'detail_diagnosis.id_kode10',
                    '=',
                    'kode_diagnosis.id_icd10'
                )
                ->where(
                    'detail_diagnosis.id_assesmen',
                    $assesmen->id_assesmen
                )
                ->select([
                    'kode_diagnosis.id_icd10 as id',
                    'kode_diagnosis.icd_10 as kode',
                    'kode_diagnosis.diagnosa as nama',
                ])
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kode' => $item->kode,
                        'nama' => $item->nama,
                    ];
                })
                ->values()
                ->all();

            $tindakanTersimpan = DB::table('detail_tindakan')
                ->join(
                    'kode_tindakan',
                    'detail_tindakan.id_kode9',
                    '=',
                    'kode_tindakan.id_icd9'
                )
                ->where(
                    'detail_tindakan.id_assesmen',
                    $assesmen->id_assesmen
                )
                ->select([
                    'kode_tindakan.id_icd9 as id',
                    'kode_tindakan.icd_9 as kode',
                    'kode_tindakan.tindakan as nama',
                ])
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'kode' => $item->kode,
                        'nama' => $item->nama,
                    ];
                })
                ->values()
                ->all();
        }

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
        $pasien->jenis_kelamin = $jenisKelamin;
        $pasien->pj_jenis_kelamin = $pjJenisKelamin;

        return view('poli.syaraf-kunjungan-baru', [
            'no_rm' => $pasien->no_rm,
            'pasien' => $pasien,
            'semuaPasien' => collect([$pasien]),
            'pendaftaran' => $pendaftaran,
            'id_pendaftaran' => $pendaftaran->id_pendaftaran,
            'ttv' => $ttv,
            'assesmen' => $assesmen,
            'diagnosisTersimpan' => $diagnosisTersimpan,
            'tindakanTersimpan' => $tindakanTersimpan,
            'jenisKelamin' => $jenisKelamin,
            'pjJenisKelamin' => $pjJenisKelamin,
        ]);
    }

    /**
     * Halaman utama Poli Obgyn.
     */
    public function obgyn(Request $request)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();

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
            ->orderBy('tgl_registrasi', 'asc')
            ->orderBy('id_pendaftaran', 'asc')
            ->get();

        $pasien = $this->satuBarisPerPasien($pendaftaran)
            ->map(function ($item) {
                $data = clone $item->pasien;

                $data->id_pendaftaran = $item->id_pendaftaran;
                $data->tgl_registrasi = $item->tgl_registrasi;
                $data->pembayaran = $item->jenis_pembayaran;
                $data->jenis_pembayaran = $item->jenis_pembayaran;
                $data->poli = $item->poli->nama_poli ?? '-';
                $data->dokter = $item->dpjp ?? '-';
                $data->status_periksa = $item->status_periksa;

                $data->jenis_kelamin_label = $this->formatJenisKelamin(
                    $data->jenis_kelamin
                );

                $data->agama_label = [
                    1 => 'Islam',
                    2 => 'Kristen',
                    3 => 'Katolik',
                    4 => 'Hindu',
                    5 => 'Buddha',
                    6 => 'Konghucu',
                ][$data->agama] ?? '-';

                $data->pendidikan_label = [
                    0 => 'Tidak/belum sekolah',
                    1 => 'SD',
                    2 => 'SMP',
                    3 => 'SMA/SMK',
                    4 => 'Diploma I',
                    5 => 'Diploma II',
                    6 => 'Diploma III',
                    7 => 'Diploma IV/S1',
                    8 => 'S2/S3',
                ][$data->pendidikan] ?? '-';

                $data->pekerjaan_label = [
                    0 => 'Tidak bekerja',
                    1 => 'PNS',
                    2 => 'TNI/Polri',
                    3 => 'Swasta',
                    4 => 'Wiraswasta',
                    5 => 'Lainnya',
                ][$data->pekerjaan] ?? '-';

                $data->pj_jenis_kelamin_label = $this->formatJenisKelamin(
                    $data->pj_jenis_kelamin
                );

                $data->umur = $data->tgl_lahir
                    ? Carbon::parse($data->tgl_lahir)->age . ' tahun'
                    : '-';

                return $data;
            })
            ->values();

        return view('poli.obgyn', compact('poli', 'pasien'));
    }

    /**
     * Halaman riwayat kunjungan pasien Obgyn.
     * Mengambil semua riwayat pendaftaran pasien dari database.
     */
    public function riwayatObgyn($no_rm)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();

        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $riwayat = Pendaftaran::with('poli')
            ->where('no_rm', $no_rm)
            ->orderByDesc('tgl_registrasi')
            ->orderByDesc('id_pendaftaran')
            ->get();

        $jenisKelamin = $this->formatJenisKelamin(
            $pasien->jenis_kelamin
        );

        $umur = $pasien->tgl_lahir
            ? Carbon::parse($pasien->tgl_lahir)->age . ' tahun'
            : '-';

        return view('poli.obgyn-riwayat', compact(
            'poli',
            'pasien',
            'riwayat',
            'no_rm',
            'jenisKelamin',
            'umur'
        ));
    }

    /**
     * Membuka kunjungan pasien di Poli Obgyn.
     */
    public function kunjunganObgyn($no_rm, $id_pendaftaran = null)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();

        $pasien = Pasien::where('no_rm', $no_rm)
            ->firstOrFail();

        // Cari pendaftaran pasien khusus Poli Obgyn.
        $query = Pendaftaran::where('no_rm', $no_rm)
            ->where('id_poli', $poli->id_poli);

        // Jika ID diberikan dari riwayat, buka kunjungan tersebut.
        if ($id_pendaftaran !== null) {
            $query->where('id_pendaftaran', $id_pendaftaran);
        }

        $pendaftaran = $query
            ->orderByDesc('id_pendaftaran')
            ->firstOrFail();

        $id_pendaftaran = $pendaftaran->id_pendaftaran;

        $ttv = Ttv::where('id_pendaftaran', $id_pendaftaran)
            ->first();

        $assesmen = Assesmen::where('id_pendaftaran', $id_pendaftaran)
            ->first();

        $diagnosisTersimpan = collect();
        $tindakanTersimpan = collect();

        if ($assesmen) {
            $diagnosisTersimpan = DB::table('detail_diagnosis')
                ->join(
                    'kode_diagnosis',
                    'detail_diagnosis.id_kode10',
                    '=',
                    'kode_diagnosis.id_icd10'
                )
                ->where(
                    'detail_diagnosis.id_assesmen',
                    $assesmen->id_assesmen
                )
                ->select(
                    'detail_diagnosis.*',
                    'kode_diagnosis.icd_10 as kode',
                    'kode_diagnosis.diagnosa as nama'
                )
                ->get();

            $tindakanTersimpan = DB::table('detail_tindakan')
                ->join(
                    'kode_tindakan',
                    'detail_tindakan.id_kode9',
                    '=',
                    'kode_tindakan.id_icd9'
                )
                ->where(
                    'detail_tindakan.id_assesmen',
                    $assesmen->id_assesmen
                )
                ->select(
                    'detail_tindakan.*',
                    'kode_tindakan.icd_9 as kode',
                    'kode_tindakan.tindakan as nama'
                )
                ->get();
        }

        $semuaPasienObgyn = Pasien::whereIn(
            'no_rm',
            Pendaftaran::where('id_poli', $poli->id_poli)
                ->select('no_rm')
                ->distinct()
        )->get();

        $radiologi = null;

        // BARU: data informed consent yang sudah tersimpan untuk kunjungan ini
        $informedConsent = InformedConsent::where(
            'id_kunjungan',
            $id_pendaftaran
        )->first();

        // BARU: tab Informed Consent hanya terbuka bila ada tindakan KB IUD (ICD-9-CM 69.7)
        $bolehInformed = $tindakanTersimpan->contains(function ($t) {
            return str_starts_with($t->kode, '69.7');
        });

        return view('poli.obgyn-kunjungan-baru', compact(
            'poli',
            'pasien',
            'pendaftaran',
            'no_rm',
            'id_pendaftaran',
            'ttv',
            'assesmen',
            'diagnosisTersimpan',
            'tindakanTersimpan',
            'semuaPasienObgyn',
            'radiologi',
            'informedConsent',
            'bolehInformed'
        ));
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

    /**
     * Memperbarui status pemeriksaan kunjungan Poli Saraf.
     */
    public function updateStatusPeriksa(Request $request, $id)
    {
        $request->validate([
            'status_periksa' => 'required|in:periksa,selesai',
        ]);

        $poli = Poli::where('slug', 'saraf')->firstOrFail();

        $pendaftaran = Pendaftaran::where(
            'id_pendaftaran',
            $id
        )
            ->where('id_poli', $poli->id_poli)
            ->firstOrFail();

        $pendaftaran->status_periksa = $request->status_periksa;
        $pendaftaran->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pemeriksaan berhasil diperbarui.',
            'status_periksa' => $pendaftaran->status_periksa,
        ]);
    }

    /**
     * Memperbarui status pemeriksaan kunjungan Poli Obgyn.
     */
    public function updateStatusPeriksaObgyn($id)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();

        $pendaftaran = Pendaftaran::where('id_pendaftaran', $id)
            ->where('id_poli', $poli->id_poli)
            ->firstOrFail();

        $pendaftaran->status_periksa = 'selesai';
        $pendaftaran->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pemeriksaan berhasil diperbarui.',
            'status_periksa' => $pendaftaran->status_periksa,
        ]);
    }

    /**
     * BARU: Menyimpan informed consent Poli Obgyn.
     * Hanya boleh bila kunjungan memiliki tindakan KB IUD (ICD-9-CM 69.7).
     */
    public function simpanInformedConsent(Request $request)
    {
        $request->validate([
            'no_rm'               => 'required|string|exists:pasien,no_rm',
            'id_kunjungan'        => 'required|integer',
            'tindakan'            => 'nullable|string|max:255',
            'diagnosis'           => 'nullable|string|max:255',
            'penjelasan_tindakan' => 'nullable|string',
            'risiko'              => 'nullable|string',
            'alternatif'          => 'nullable|string',
            'keputusan'           => 'required|in:setuju,menolak',
            'nama_penandatangan'  => 'required|string|max:100',
            'hubungan'            => 'nullable|string|max:50',
            'nama_saksi'          => 'nullable|string|max:100',
        ]);

        // Cek di database: kunjungan harus punya tindakan 69.7 yang sudah tersimpan
        $assesmen = Assesmen::where('id_pendaftaran', $request->id_kunjungan)
            ->first();

        $adaIud = $assesmen && DB::table('detail_tindakan')
            ->join(
                'kode_tindakan',
                'detail_tindakan.id_kode9',
                '=',
                'kode_tindakan.id_icd9'
            )
            ->where('detail_tindakan.id_assesmen', $assesmen->id_assesmen)
            ->where('kode_tindakan.icd_9', 'like', '69.7%')
            ->exists();

        if (!$adaIud) {
            return back()->with(
                'error',
                'Informed consent hanya untuk tindakan KB IUD. '
                . 'Simpan tindakan IUD terlebih dahulu.'
            );
        }

        InformedConsent::updateOrCreate(
            ['id_kunjungan' => $request->id_kunjungan],
            $request->only([
                'no_rm', 'tindakan', 'diagnosis', 'penjelasan_tindakan',
                'risiko', 'alternatif', 'keputusan',
                'nama_penandatangan', 'hubungan', 'nama_saksi',
            ]) + ['tanggal' => now()->toDateString()]
        );

        return back()->with('success', 'Informed consent berhasil disimpan.');
    }

    /**
     * Membuat pendaftaran baru saat pasien dirujuk dari Poli Saraf.
     * Tidak menyimpan data rujukan terpisah.
     */
    public function kirimRujukan(Request $request)
    {
        $validated = $request->validate([
            'no_rm' => 'required|string|exists:pasien,no_rm',
            'tujuan' => 'required|string|in:obgyn,jantung,jiwa,radiologi',
        ]);

        $noRm = $validated['no_rm'];

        // Pastikan pasien memiliki kunjungan di Poli Saraf.
        $poliAsal = Poli::where('slug', 'saraf')->firstOrFail();

        $pendaftaranAsal = Pendaftaran::where('no_rm', $noRm)
            ->where('id_poli', $poliAsal->id_poli)
            ->orderByDesc('id_pendaftaran')
            ->firstOrFail();

        // Cari poli tujuan.
        $poliTujuan = Poli::where('slug', $validated['tujuan'])
            ->firstOrFail();

        // Cegah pendaftaran ganda di poli tujuan pada hari yang sama.
        $sudahTerdaftar = Pendaftaran::where('no_rm', $noRm)
            ->where('id_poli', $poliTujuan->id_poli)
            ->whereDate('tgl_registrasi', today())
            ->exists();

        if ($sudahTerdaftar) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien sudah terdaftar di poli tujuan hari ini.',
            ], 422);
        }

        // Buat pendaftaran baru tanpa mengubah pendaftaran Poli Saraf.
        $pendaftaranBaru = DB::transaction(function () use (
            $pendaftaranAsal,
            $poliTujuan
        ) {
            return Pendaftaran::create([
                'tgl_registrasi' => now(),
                'no_rm' => $pendaftaranAsal->no_rm,
                'id_poli' => $poliTujuan->id_poli,
                'dpjp' => $poliTujuan->nama_dokter ?? '',
                'jenis_pembayaran' => $pendaftaranAsal->jenis_pembayaran,
                'status_periksa' => 'periksa',
            ]);
        });

        // Arahkan pasien langsung ke halaman poli tujuan.
        $redirect = match ($poliTujuan->slug) {
            'obgyn' => route('poli.obgyn'),
            'jantung' => route('poli.jantung'),
            'jiwa' => route('poli.jiwa'),
            'radiologi' => route('radiologi.index'),
            default => route('poli.syaraf'),
        };

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil didaftarkan ke Poli '
                . $poliTujuan->nama_poli . '.',
            'redirect' => $redirect,
        ]);
    }
}