<?php

namespace App\Http\Controllers;

use App\Helpers\Audit;
use App\Models\Poli;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Ttv;
use App\Models\Assesmen;
use App\Models\InformedConsent;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PoliController extends Controller
{
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
                    'jenis_kelamin' => $this->formatJenisKelamin($p->jenis_kelamin),
                    'alamat' => $p->alamat,
                    'no_hp' => $p->no_hp,
                    'suku' => $p->suku,
                    'bangsa' => $p->bangsa,
                    'bahasa' => $p->bahasa,
                    'umur' => $p->tgl_lahir ? Carbon::parse($p->tgl_lahir)->age . ' Tahun' : '-',
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
                    'pj_jenis_kelamin' => $this->formatJenisKelamin($p->pj_jenis_kelamin),
                    'pj_no_hp' => $p->pj_no_hp,
                    'status_periksa' => $item->status_periksa,
                ];
            })
            ->values();

        return view('poli.syaraf', compact('poli', 'pasien'));
    }

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

        $jenisKelamin = $this->formatJenisKelamin($pasien->jenis_kelamin);
        $pasien->jenis_kelamin = $jenisKelamin;

        return view('poli.syaraf-riwayat', [
            'pasien' => $pasien,
            'riwayat' => $riwayat,
            'jenisKelamin' => $jenisKelamin,
        ]);
    }

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

    public function kunjunganSarafDetail($no_rm, $id_pendaftaran)
    {
        $poli = Poli::where('slug', 'saraf')->firstOrFail();

        $pendaftaran = Pendaftaran::where('id_pendaftaran', $id_pendaftaran)
            ->where('no_rm', $no_rm)
            ->where('id_poli', $poli->id_poli)
            ->firstOrFail();

        return $this->tampilkanKunjunganSaraf($pendaftaran);
    }

    private function tampilkanKunjunganSaraf(Pendaftaran $pendaftaran)
    {
        $pasien = Pasien::where('no_rm', $pendaftaran->no_rm)->firstOrFail();
        $ttv = Ttv::where('id_pendaftaran', $pendaftaran->id_pendaftaran)->first();
        $assesmen = Assesmen::where('id_pendaftaran', $pendaftaran->id_pendaftaran)->first();

        $diagnosisTersimpan = [];
        $tindakanTersimpan = [];

        if ($assesmen) {
            $diagnosisTersimpan = DB::table('detail_diagnosis')
                ->join('kode_diagnosis', 'detail_diagnosis.id_kode10', '=', 'kode_diagnosis.id_icd10')
                ->where('detail_diagnosis.id_assesmen', $assesmen->id_assesmen)
                ->select([
                    'kode_diagnosis.id_icd10 as id',
                    'kode_diagnosis.icd_10 as kode',
                    'kode_diagnosis.diagnosa as nama',
                ])
                ->get()
                ->map(fn ($item) => ['id' => $item->id, 'kode' => $item->kode, 'nama' => $item->nama])
                ->values()
                ->all();

            $tindakanTersimpan = DB::table('detail_tindakan')
                ->join('kode_tindakan', 'detail_tindakan.id_kode9', '=', 'kode_tindakan.id_icd9')
                ->where('detail_tindakan.id_assesmen', $assesmen->id_assesmen)
                ->select([
                    'kode_tindakan.id_icd9 as id',
                    'kode_tindakan.icd_9 as kode',
                    'kode_tindakan.tindakan as nama',
                ])
                ->get()
                ->map(fn ($item) => ['id' => $item->id, 'kode' => $item->kode, 'nama' => $item->nama])
                ->values()
                ->all();
        }

        $jenisKelamin = $this->formatJenisKelamin($pasien->jenis_kelamin);
        $pjJenisKelamin = $this->formatJenisKelamin($pasien->pj_jenis_kelamin);
        $umur = $pasien->tgl_lahir ? Carbon::parse($pasien->tgl_lahir)->age . ' Tahun' : '-';

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

    public function obgyn(Request $request)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();

        $query = Pendaftaran::with(['pasien', 'poli'])
            ->where('id_poli', $poli->id_poli);

        if ($request->filled('dari')) {
            $query->whereDate('tgl_registrasi', '>=', $request->input('dari'));
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tgl_registrasi', '<=', $request->input('sampai'));
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
                $data->jenis_kelamin_label = $this->formatJenisKelamin($data->jenis_kelamin);
                $data->umur = $data->tgl_lahir ? Carbon::parse($data->tgl_lahir)->age . ' tahun' : '-';
                return $data;
            })
            ->values();

        return view('poli.obgyn', compact('poli', 'pasien'));
    }

    public function riwayatObgyn($no_rm)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();
        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $riwayat = Pendaftaran::with('poli')
            ->where('no_rm', $no_rm)
            ->orderByDesc('tgl_registrasi')
            ->orderByDesc('id_pendaftaran')
            ->get();

        $jenisKelamin = $this->formatJenisKelamin($pasien->jenis_kelamin);
        $umur = $pasien->tgl_lahir ? Carbon::parse($pasien->tgl_lahir)->age . ' tahun' : '-';

        return view('poli.obgyn-riwayat', compact('poli', 'pasien', 'riwayat', 'no_rm', 'jenisKelamin', 'umur'));
    }

    public function kunjunganObgyn($no_rm, $id_pendaftaran = null)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();
        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $query = Pendaftaran::where('no_rm', $no_rm)->where('id_poli', $poli->id_poli);

        if ($id_pendaftaran !== null) {
            $query->where('id_pendaftaran', $id_pendaftaran);
        }

        $pendaftaran = $query->orderByDesc('id_pendaftaran')->firstOrFail();
        $id_pendaftaran = $pendaftaran->id_pendaftaran;

        $ttv = Ttv::where('id_pendaftaran', $id_pendaftaran)->first();
        $assesmen = Assesmen::where('id_pendaftaran', $id_pendaftaran)->first();

        $diagnosisTersimpan = collect();
        $tindakanTersimpan = collect();

        if ($assesmen) {
            $diagnosisTersimpan = DB::table('detail_diagnosis')
                ->join('kode_diagnosis', 'detail_diagnosis.id_kode10', '=', 'kode_diagnosis.id_icd10')
                ->where('detail_diagnosis.id_assesmen', $assesmen->id_assesmen)
                ->select('detail_diagnosis.*', 'kode_diagnosis.icd_10 as kode', 'kode_diagnosis.diagnosa as nama')
                ->get();

            $tindakanTersimpan = DB::table('detail_tindakan')
                ->join('kode_tindakan', 'detail_tindakan.id_kode9', '=', 'kode_tindakan.id_icd9')
                ->where('detail_tindakan.id_assesmen', $assesmen->id_assesmen)
                ->select('detail_tindakan.*', 'kode_tindakan.icd_9 as kode', 'kode_tindakan.tindakan as nama')
                ->get();
        }

        $semuaPasienObgyn = Pasien::whereIn(
            'no_rm',
            Pendaftaran::where('id_poli', $poli->id_poli)->select('no_rm')->distinct()
        )->get();

        $radiologi = null;
        $informedConsent = InformedConsent::where('id_kunjungan', $id_pendaftaran)->first();
        $bolehInformed = $tindakanTersimpan->contains(fn ($t) => str_starts_with($t->kode, '69.7'));

        return view('poli.obgyn-kunjungan-baru', compact(
            'poli', 'pasien', 'pendaftaran', 'no_rm', 'id_pendaftaran',
            'ttv', 'assesmen', 'diagnosisTersimpan', 'tindakanTersimpan',
            'semuaPasienObgyn', 'radiologi', 'informedConsent', 'bolehInformed'
        ));
    }

    public function jantung()
    {
        $poli = Poli::where('slug', 'jantung')->firstOrFail();
        return view('poli.jantung', compact('poli'));
    }

    public function jiwa()
    {
        $poli = Poli::where('slug', 'jiwa')->firstOrFail();
        return view('poli.jiwa', compact('poli'));
    }

    public function updateStatusPeriksa(Request $request, $id)
    {
        $request->validate(['status_periksa' => 'required|in:periksa,selesai']);

        $poli = Poli::where('slug', 'saraf')->firstOrFail();
        $pendaftaran = Pendaftaran::where('id_pendaftaran', $id)->where('id_poli', $poli->id_poli)->firstOrFail();

        $statusLama = $pendaftaran->status_periksa ?? 'periksa';
        $pendaftaran->status_periksa = $request->status_periksa;
        $pendaftaran->save();

        Audit::catat(
            "Mengubah status pemeriksaan: '{$statusLama}' menjadi '{$pendaftaran->status_periksa}'",
            'Poli Syaraf',
            $pendaftaran->no_rm
        );

        return response()->json([
            'success' => true,
            'message' => 'Status pemeriksaan berhasil diperbarui.',
            'status_periksa' => $pendaftaran->status_periksa,
        ]);
    }

    public function updateStatusPeriksaObgyn($id)
    {
        $poli = Poli::where('slug', 'obgyn')->firstOrFail();
        $pendaftaran = Pendaftaran::where('id_pendaftaran', $id)->where('id_poli', $poli->id_poli)->firstOrFail();

        $statusLama = $pendaftaran->status_periksa ?? 'periksa';
        $pendaftaran->status_periksa = 'selesai';
        $pendaftaran->save();

        Audit::catat(
            "Mengubah status pemeriksaan: '{$statusLama}' menjadi 'selesai'",
            'Poli Obgyn',
            $pendaftaran->no_rm
        );

        return response()->json([
            'success' => true,
            'message' => 'Status pemeriksaan berhasil diperbarui.',
            'status_periksa' => $pendaftaran->status_periksa,
        ]);
    }

    public function simpanInformedConsent(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'no_rm' => 'required|string|exists:pasien,no_rm',
            'id_kunjungan' => 'required|integer|exists:pendaftaran,id_pendaftaran',
            'keputusan' => 'required|in:setuju,menolak',
            'checklist' => 'nullable|array',
            'checklist.*' => 'string',
            'isi_pertimbangan' => 'nullable|string',
            'isi_lain' => 'nullable|string',
            'nama_dokter' => 'required|string|max:100',
            'nama_penandatangan' => 'required|string|max:100',
            'nama_saksi' => 'nullable|string|max:100',
            'tanggal' => 'required|date',
            'pukul' => 'required|date_format:H:i',
            'ttd_dokter' => 'required|string',
            'ttd_pasien' => 'required|string',
            'ttd_saksi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data Informed Consent belum lengkap atau tidak valid.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        try {
            $poliObgyn = Poli::where('slug', 'obgyn')->first();
            if (!$poliObgyn) {
                return response()->json(['success' => false, 'message' => 'Data Poli Obgyn tidak ditemukan.'], 422);
            }

            $pendaftaran = Pendaftaran::where('id_pendaftaran', $data['id_kunjungan'])
                ->where('no_rm', $data['no_rm'])
                ->where('id_poli', $poliObgyn->id_poli)
                ->first();

            if (!$pendaftaran) {
                return response()->json(['success' => false, 'message' => 'Kunjungan tidak sesuai atau bukan Poli Obgyn.'], 422);
            }

            $isiBebas = [
                'pertimbangan' => $data['isi_pertimbangan'] ?? '',
                'lain' => $data['isi_lain'] ?? '',
            ];

            $informedConsent = InformedConsent::updateOrCreate(
                ['id_kunjungan' => $data['id_kunjungan']],
                [
                    'no_rm' => $data['no_rm'],
                    'keputusan' => $data['keputusan'],
                    'checklist' => json_encode($data['checklist'] ?? [], JSON_UNESCAPED_UNICODE),
                    'isi_bebas' => json_encode($isiBebas, JSON_UNESCAPED_UNICODE),
                    'nama_dokter' => $data['nama_dokter'],
                    'nama_penandatangan' => $data['nama_penandatangan'],
                    'nama_pasien' => $data['nama_penandatangan'],
                    'nama_saksi' => $data['nama_saksi'] ?? null,
                    'tanggal' => $data['tanggal'],
                    'tanggal_tindakan' => $data['tanggal'],
                    'pukul' => $data['pukul'],
                    'ttd_dokter' => $data['ttd_dokter'],
                    'ttd_pasien' => $data['ttd_pasien'],
                    'ttd_saksi' => $data['ttd_saksi'] ?? null,
                ]
            );

            Audit::catat(
                ($informedConsent->wasRecentlyCreated ? 'Menyimpan' : 'Memperbarui') . ' Informed Consent KB IUD (keputusan: ' . $data['keputusan'] . ')',
                'Poli Obgyn',
                $data['no_rm']
            );

            return response()->json([
                'success' => true,
                'message' => 'Informed Consent berhasil disimpan.',
                'keputusan' => $data['keputusan'],
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Informed Consent gagal disimpan.',
            ], 500);
        }
    }

    public function kirimRujukan(Request $request)
    {
        $validated = $request->validate([
            'no_rm' => 'required|string|exists:pasien,no_rm',
            'tujuan' => 'required|string|in:obgyn,jantung,jiwa,radiologi',
        ]);

        $noRm = $validated['no_rm'];
        $poliAsal = Poli::where('slug', 'saraf')->firstOrFail();
        $pendaftaranAsal = Pendaftaran::where('no_rm', $noRm)->where('id_poli', $poliAsal->id_poli)->orderByDesc('id_pendaftaran')->firstOrFail();
        $poliTujuan = Poli::where('slug', $validated['tujuan'])->firstOrFail();

        $sudahTerdaftar = Pendaftaran::where('no_rm', $noRm)
            ->where('id_poli', $poliTujuan->id_poli)
            ->whereDate('tgl_registrasi', today())
            ->exists();

        if ($sudahTerdaftar) {
            return response()->json(['success' => false, 'message' => 'Pasien sudah terdaftar di poli tujuan hari ini.'], 422);
        }

        DB::transaction(function () use ($pendaftaranAsal, $poliTujuan) {
            Pendaftaran::create([
                'tgl_registrasi' => now(),
                'no_rm' => $pendaftaranAsal->no_rm,
                'id_poli' => $poliTujuan->id_poli,
                'dpjp' => $poliTujuan->nama_dokter ?? '',
                'jenis_pembayaran' => $pendaftaranAsal->jenis_pembayaran,
                'status_periksa' => 'periksa',
            ]);
        });

        Audit::catat(
            'Merujuk pasien dari Poli Saraf ke Poli ' . $poliTujuan->nama_poli,
            'Poli Syaraf',
            $noRm
        );

        $redirect = match ($poliTujuan->slug) {
            'obgyn' => route('poli.obgyn'),
            'jantung' => route('poli.jantung'),
            'jiwa' => route('poli.jiwa'),
            'radiologi' => route('radiologi.index'),
            default => route('poli.syaraf'),
        };

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil didaftarkan ke Poli ' . $poliTujuan->nama_poli . '.',
            'redirect' => $redirect,
        ]);
    }

    public function kirimRujukanObgyn(Request $request)
    {
        $validated = $request->validate([
            'no_rm' => 'required|string|exists:pasien,no_rm',
            'tujuan' => 'required|string|in:saraf,jantung,jiwa,radiologi',
        ]);

        $noRm = $validated['no_rm'];
        $poliAsal = Poli::where('slug', 'obgyn')->firstOrFail();
        $pendaftaranAsal = Pendaftaran::where('no_rm', $noRm)->where('id_poli', $poliAsal->id_poli)->orderByDesc('id_pendaftaran')->firstOrFail();
        $poliTujuan = Poli::where('slug', $validated['tujuan'])->firstOrFail();

        $sudahTerdaftar = Pendaftaran::where('no_rm', $noRm)
            ->where('id_poli', $poliTujuan->id_poli)
            ->whereDate('tgl_registrasi', today())
            ->exists();

        if ($sudahTerdaftar) {
            return response()->json(['success' => false, 'message' => 'Pasien sudah terdaftar di poli tujuan hari ini.'], 422);
        }

        DB::transaction(function () use ($pendaftaranAsal, $poliTujuan) {
            Pendaftaran::create([
                'tgl_registrasi' => now(),
                'no_rm' => $pendaftaranAsal->no_rm,
                'id_poli' => $poliTujuan->id_poli,
                'dpjp' => $poliTujuan->nama_dokter ?? '',
                'jenis_pembayaran' => $pendaftaranAsal->jenis_pembayaran,
                'status_periksa' => 'periksa',
            ]);
        });

        Audit::catat(
            'Merujuk pasien dari Poli Obgyn ke Poli ' . $poliTujuan->nama_poli,
            'Poli Obgyn',
            $noRm
        );

        $redirect = match ($poliTujuan->slug) {
            'saraf' => route('poli.syaraf'),
            'jantung' => route('poli.jantung'),
            'jiwa' => route('poli.jiwa'),
            'radiologi' => route('radiologi.index'),
            default => route('poli.obgyn'),
        };

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil didaftarkan ke Poli ' . $poliTujuan->nama_poli . '.',
            'redirect' => $redirect,
        ]);
    }
}