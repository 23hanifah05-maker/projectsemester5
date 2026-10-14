<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\Poli;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    // Ambil ID pendaftaran terakhir untuk setiap pasien.
    $pendaftaranTerakhir = DB::table('pendaftaran')
        ->select('no_rm', DB::raw('MAX(id_pendaftaran) as id_pendaftaran'))
        ->groupBy('no_rm');

    $query = Pasien::query()
        ->leftJoinSub(
            $pendaftaranTerakhir,
            'pt',
            function ($join) {
                $join->on('pasien.no_rm', '=', 'pt.no_rm');
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

    // Filter berdasarkan tanggal registrasi.
    if ($dari) {
        $query->whereDate('p.tgl_registrasi', '>=', $dari);
    }

    if ($sampai) {
        $query->whereDate('p.tgl_registrasi', '<=', $sampai);
    }

    // Pencarian otomatis berdasarkan keyword.
    if ($keyword !== '') {
        $query->where(function ($q) use ($keyword) {
            $q->where('pasien.nama_pasien', 'like', "%{$keyword}%")
                ->orWhere('pasien.no_rm', 'like', "%{$keyword}%")
                ->orWhere('pasien.nik', 'like', "%{$keyword}%")
                ->orWhere('pasien.no_hp', 'like', "%{$keyword}%");
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
            'no_rm' => [
                'required',
                'string',
                'max:20',
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

            'pembayaran' => [
                'required',
                Rule::in(['umum', 'asuransi', 'bpjs']),
            ],
        ]);

        $dataPoli = Poli::where(
            'slug',
            $validated['poli']
        )->firstOrFail();

        /*
         * Mempertahankan pemetaan pembayaran yang digunakan
         * oleh struktur kolom jenis_pembayaran saat ini.
         */
        $jenisPembayaran = $validated['pembayaran'] === 'umum'
            ? 'umum'
            : 'asuransi swasta';

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

            Pendaftaran::create([
                'tgl_registrasi' => now(),
                'no_rm' => $validated['no_rm'],
                'id_poli' => $dataPoli->id_poli,
                'dpjp' => $dataPoli->nama_dokter,
                'jenis_pembayaran' => $jenisPembayaran,
            ]);
        });

        return redirect()
            ->route('pendaftaran.index')
            ->with(
                'success',
                'Data pasien dan pendaftaran berhasil disimpan.'
            );
    }

    /**
     * Membuat nomor rekam medis berikutnya.
     */
    private function generateNoRm()
    {
        $nomorTerakhir = Pasien::where('no_rm', 'like', 'E%')
            ->orderByRaw(
                'CAST(SUBSTRING(no_rm, 2) AS UNSIGNED) DESC'
            )
            ->value('no_rm');

        $angkaBerikutnya = $nomorTerakhir
            ? ((int) substr($nomorTerakhir, 1)) + 1
            : 1;

        do {
            $noRm = 'E' . str_pad(
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
