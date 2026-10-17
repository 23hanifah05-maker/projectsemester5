<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan master data pasien dari database.
     */
    public function index(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        // Mengambil ID pendaftaran terakhir untuk setiap pasien.
        $pendaftaranTerakhir = DB::table('pendaftaran')
            ->select(
                'no_rm',
                DB::raw('MAX(id_pendaftaran) as id_pendaftaran')
            )
            ->groupBy('no_rm');

        $query = Pasien::query()
            ->leftJoinSub(
                $pendaftaranTerakhir,
                'pt',
                function ($join) {
                    $join->on(
                        'pasien.no_rm',
                        '=',
                        'pt.no_rm'
                    );
                }
            )
            ->leftJoin(
                'pendaftaran as p',
                'p.id_pendaftaran',
                '=',
                'pt.id_pendaftaran'
            )
            ->leftJoin(
                'poli as po',
                'po.id_poli',
                '=',
                'p.id_poli'
            )
            ->select(
                'pasien.*',
                'p.tgl_registrasi',
                'p.jenis_pembayaran as pembayaran',
                'p.dpjp as dokter',
                'po.nama_poli as nama_poli'
            );

        // Filter tanggal berdasarkan tanggal registrasi.
        if ($dari) {
            $query->whereDate(
                'p.tgl_registrasi',
                '>=',
                $dari
            );
        }

        if ($sampai) {
            $query->whereDate(
                'p.tgl_registrasi',
                '<=',
                $sampai
            );
        }

        // Pencarian berdasarkan nama, nomor RM, NIK, atau HP.
        if ($keyword !== '') {
            $query->where(function ($q) use ($keyword) {
                $q->where(
                    'pasien.nama_pasien',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'pasien.no_rm',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'pasien.nik',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'pasien.no_hp',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'pasien.alamat',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'pasien.kelurahan',
                    'like',
                    "%{$keyword}%"
                )
                ->orWhere(
                    'pasien.kecamatan',
                    'like',
                    "%{$keyword}%"
                );
            });
        }

        $dataPasien = $query
            ->orderByDesc('p.tgl_registrasi')
            ->orderByDesc('pasien.no_rm')
            ->get();

        return view('pendaftaran.index', compact(
            'dataPasien',
            'keyword',
            'dari',
            'sampai'
        ));
    }

    /**
     * Menampilkan formulir pendaftaran.
     */
    public function create()
    {
        $noRmBaru = $this->generateNoRm();
        $poli = Poli::all();

        return view('pendaftaran.create', compact(
            'noRmBaru',
            'poli'
        ));
    }

    /**
     * Menyimpan data pasien dan pendaftaran.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // No RM: 6 digit angka (contoh 000015)
            'no_rm' => [
                'required',
                'digits:6',
                'unique:pasien,no_rm',
            ],

            'nama_pasien' => 'required|string|max:100',
            'nik' => 'required|string|size:16',
            'tempat_lahir' => 'required|string|max:50',
            'tgl_lahir' => 'required|date',

            'jenis_kelamin' => 'required|integer|between:0,4',
            'agama' => 'required|integer|between:1,8',
            'pendidikan' => 'required|integer|between:0,8',
            'pekerjaan' => 'required|integer|between:0,5',

            'no_hp' => 'required|string|max:13',
            'suku' => 'required|string|max:30',
            'bangsa' => 'required|string|max:30',
            'bahasa' => 'required|string|max:30',

            'alamat' => 'required|string',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string',
            'kabupaten' => 'required|string',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|digits_between:1,10',

            'pj_nama' => 'required|string|max:100',
            'pj_hubungan' => 'required|string|max:20',
            'pj_jenis_kelamin' => 'required|integer|between:0,4',
            'pj_no_hp' => 'required|string|max:13',

            'poli' => 'required|string|exists:poli,slug',

            'dokter' => 'required|string|max:100',

            'pembayaran' => [
                'required',
                Rule::in(['umum', 'asuransi', 'bpjs']),
            ],

            // Data General Consent (dari modal)
            'general_consent'       => 'required|in:1',
            'gc_anggota_keluarga'   => 'nullable|json',
            'gc_ttd_pemberi'        => 'required|string',
            'gc_nama_pemberi'       => 'required|string|max:100',
            'gc_ttd_pasien'         => 'required|string',
            'gc_nama_penandatangan' => 'required|string|max:100',
        ], [
            'general_consent.in' => 'General Consent belum disetujui.',
        ]);

        // Pastikan poli yang dipilih tersedia di database.
        $dataPoli = Poli::where(
            'slug',
            $validated['poli']
        )->firstOrFail();

        // Pemetaan jenis pembayaran sesuai pilihan pada formulir.
        $jenisPembayaran = match ($validated['pembayaran']) {
            'umum' => 'Umum',
            'asuransi' => 'Asuransi Swasta',
            'bpjs' => 'BPJS',
        };

        $noHp = $this->normalisasiNomorHp(
            $validated['no_hp']
        );

        $pjNoHp = $this->normalisasiNomorHp(
            $validated['pj_no_hp']
        );

        if (strlen($noHp) > 13 || strlen($pjNoHp) > 13) {
            return back()
                ->withErrors([
                    'no_hp' => 'Nomor HP maksimal 13 karakter, termasuk +62.',
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $validated,
            $dataPoli,
            $jenisPembayaran,
            $noHp,
            $pjNoHp
        ) {
            // Simpan identitas pasien, termasuk provinsi.
            Pasien::create([
                'no_rm' => $validated['no_rm'],
                'nik' => $validated['nik'],
                'nama_pasien' => $validated['nama_pasien'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tgl_lahir' => $validated['tgl_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'agama' => $validated['agama'],
                'pendidikan' => $validated['pendidikan'],
                'pekerjaan' => $validated['pekerjaan'],
                'no_hp' => $noHp,
                'suku' => $validated['suku'],
                'bangsa' => $validated['bangsa'],
                'bahasa' => $validated['bahasa'],
                'alamat' => $validated['alamat'],
                'kelurahan' => $validated['kelurahan'],
                'kabupaten' => $validated['kabupaten'],
                'provinsi' => $validated['provinsi'],
                'kecamatan' => $validated['kecamatan'],
                'kode_pos' => $validated['kode_pos'],
                'pj_nama' => $validated['pj_nama'],
                'pj_hubungan' => $validated['pj_hubungan'],
                'pj_jenis_kelamin' => $validated['pj_jenis_kelamin'],
                'pj_no_hp' => $pjNoHp,
            ]);

            // Simpan pendaftaran poli.
            Pendaftaran::create([
                'tgl_registrasi' => now(),
                'no_rm' => $validated['no_rm'],
                'id_poli' => $dataPoli->id_poli,
                'dpjp' => $validated['dokter'],
                'jenis_pembayaran' => $jenisPembayaran,
            ]);
        });

        // Simpan berkas General Consent (tanda tangan + nama terang + anggota keluarga).
        $this->simpanGeneralConsent($validated);

        return redirect()
            ->route('pendaftaran.index')
            ->with(
                'success',
                'Data pasien dan pendaftaran berhasil disimpan.'
            );
    }

    /**
     * Menyimpan kunjungan baru untuk pasien yang sudah terdaftar.
     * Identitas pasien tidak berubah; hanya penanggung jawab diperbarui.
     */
    public function storeKunjungan(Request $request, $no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->firstOrFail();

        $validated = $request->validate([
            'poli' => 'required|string|exists:poli,slug',
            'dokter' => 'required|string|max:100',
            'pembayaran' => ['required', Rule::in(['umum', 'asuransi', 'bpjs'])],
            'pj_nama' => 'required|string|max:100',
            'pj_hubungan' => 'required|string|max:20',
            'pj_jenis_kelamin' => 'required|integer|between:0,4',
            'pj_no_hp' => 'required|string|max:13',
        ]);

        $dataPoli = Poli::where('slug', $validated['poli'])->firstOrFail();

        $jenisPembayaran = match ($validated['pembayaran']) {
            'umum' => 'Umum',
            'asuransi' => 'Asuransi Swasta',
            'bpjs' => 'BPJS',
        };

        $pjNoHp = $this->normalisasiNomorHp($validated['pj_no_hp']);

        if (strlen($pjNoHp) > 13) {
            return back()
                ->withErrors(['pj_no_hp' => 'Nomor HP maksimal 13 karakter, termasuk +62.'])
                ->withInput();
        }

        DB::transaction(function () use ($pasien, $validated, $dataPoli, $jenisPembayaran, $pjNoHp) {
            Pasien::where('no_rm', $pasien->no_rm)->update([
                'pj_nama' => $validated['pj_nama'],
                'pj_hubungan' => $validated['pj_hubungan'],
                'pj_jenis_kelamin' => $validated['pj_jenis_kelamin'],
                'pj_no_hp' => $pjNoHp,
            ]);

            Pendaftaran::create([
                'tgl_registrasi' => now(),
                'no_rm' => $pasien->no_rm,
                'id_poli' => $dataPoli->id_poli,
                'dpjp' => $validated['dokter'],
                'jenis_pembayaran' => $jenisPembayaran,
            ]);
        });

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Kunjungan baru berhasil disimpan.');
    }

    /**
     * Membuat nomor rekam medis berikutnya (6 digit angka).
     * Data lama berformat "E000014" tetap dibaca: angkanya (14)
     * dipakai sebagai acuan, sehingga nomor berikutnya 000015.
     */
    private function generateNoRm()
    {
        $angkaTerbesar = Pasien::pluck('no_rm')
            ->map(fn ($noRm) => (int) preg_replace('/\D/', '', (string) $noRm))
            ->max() ?? 0;

        $angkaBerikutnya = $angkaTerbesar + 1;

        do {
            $noRm = str_pad(
                $angkaBerikutnya,
                6,
                '0',
                STR_PAD_LEFT
            );

            $angkaBerikutnya++;
        } while (Pasien::where('no_rm', $noRm)->exists());

        return $noRm;
    }

    /**
     * Menyimpan data General Consent ke storage/app/public/general_consent/{no_rm}/
     */
    private function simpanGeneralConsent(array $validated): void
    {
        $folder = 'general_consent/' . $validated['no_rm'];

        $this->simpanGambarBase64($validated['gc_ttd_pemberi'], $folder . '/ttd_pemberi.png');
        $this->simpanGambarBase64($validated['gc_ttd_pasien'], $folder . '/ttd_pasien.png');

        $anggota = json_decode($validated['gc_anggota_keluarga'] ?? '[]', true);
        $anggota = is_array($anggota)
            ? array_values(array_filter(array_map(
                fn ($n) => is_string($n) ? trim($n) : '',
                $anggota
            )))
            : [];

        Storage::disk('public')->put(
            $folder . '/data.json',
            json_encode([
                'no_rm'              => $validated['no_rm'],
                'nama_pemberi'       => $validated['gc_nama_pemberi'],
                'nama_penandatangan' => $validated['gc_nama_penandatangan'],
                'anggota_keluarga'   => $anggota,
                'disetujui_pada'     => now()->toDateTimeString(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function simpanGambarBase64(?string $data, string $path): void
    {
        $awalan = 'data:image/png;base64,';

        if (!$data || !str_starts_with($data, $awalan)) {
            return;
        }

        $biner = base64_decode(substr($data, strlen($awalan)), true);

        if ($biner === false) {
            return;
        }

        Storage::disk('public')->put($path, $biner);
    }

    /**
     * Menyamakan format nomor HP.
     */
    private function normalisasiNomorHp($nomor)
    {
        $nomor = trim($nomor);

        if (str_starts_with($nomor, '+62')) {
            return '+62' . preg_replace(
                '/\D/',
                '',
                substr($nomor, 3)
            );
        }

        if (str_starts_with($nomor, '62')) {
            return '+62' . preg_replace(
                '/\D/',
                '',
                substr($nomor, 2)
            );
        }

        if (str_starts_with($nomor, '0')) {
            return '+62' . preg_replace(
                '/\D/',
                '',
                substr($nomor, 1)
            );
        }

        return '+62' . preg_replace('/\D/', '', $nomor);
    }

    public function show($id)
    {
        return redirect()->route('pendaftaran.index');
    }

    public function edit($id)
    {
        return redirect()->route('pendaftaran.index');
    }

    public function update(Request $request, $id)
    {
        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Fitur edit belum diaktifkan.');
    }

    public function destroy($id)
    {
        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Fitur hapus belum diaktifkan.');
    }

    public function tambah($id)
    {
        return redirect()->route('pendaftaran.index');
    }
}