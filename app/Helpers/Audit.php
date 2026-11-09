<?php

namespace App\Helpers;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class Audit
{
    // Zona waktu klinik (WIB)
    private const ZONA_WAKTU = 'Asia/Jakarta';

    // ---------- Keterangan kode data pasien ----------
    public const JENIS_KELAMIN = [
        '0' => 'Tidak diketahui',
        '1' => 'Laki-laki',
        '2' => 'Perempuan',
        '3' => 'Tidak dapat ditentukan',
        '4' => 'Tidak mengisi',
    ];

    public const AGAMA = [
        '1' => 'Islam',
        '2' => 'Kristen (Protestan)',
        '3' => 'Katolik',
        '4' => 'Hindu',
        '5' => 'Budha',
        '6' => 'Konghucu',
        '7' => 'Penghayat',
        '8' => 'Lain-lain',
    ];

    public const PEKERJAAN = [
        '0' => 'Tidak bekerja',
        '1' => 'PNS',
        '2' => 'TNI/POLRI',
        '3' => 'BUMN',
        '4' => 'Pegawai Swasta/Wirausaha',
        '5' => 'Lain-lain',
    ];

    public const PENDIDIKAN = [
        '0' => 'Tidak sekolah',
        '1' => 'SD',
        '2' => 'SLTP sederajat',
        '3' => 'SLTA sederajat',
        '4' => 'D1-D3 sederajat',
        '5' => 'D4',
        '6' => 'S1',
        '7' => 'S2',
        '8' => 'S3',
    ];

    /**
     * Pemeta bawaan per nama field. Dipakai otomatis oleh catatPerubahan()
     * bila field-nya bernama jenis_kelamin / agama / pekerjaan / pendidikan
     * dan tidak diberi keterangan khusus. Nilai di luar daftar (mis. isian
     * teks bebas "Lain-lain") ditampilkan apa adanya.
     */
    public static function pemetaBawaan(): array
    {
        return [
            'jenis_kelamin' => self::JENIS_KELAMIN,
            'agama'         => self::AGAMA,
            'pekerjaan'     => self::PEKERJAAN,
            'pendidikan'    => self::PENDIDIKAN,
        ];
    }

    /**
     * Simpan satu baris audit trail dengan waktu terkini (WIB).
     * forceFill dipakai supaya created_at / updated_at tetap terisi
     * walaupun tidak tercantum di $fillable model AuditTrail.
     */
    private static function simpan(array $data): void
    {
        $sekarang = now(self::ZONA_WAKTU);

        $log = new AuditTrail();
        $log->forceFill($data + [
            'created_at' => $sekarang,
            'updated_at' => $sekarang,
        ])->save();
    }

    public static function catat(
        $aktivitas,
        $modul,
        $noRm = null,
        $kolom = null,
        $sebelum = null,
        $sesudah = null,
        $status = 'berhasil'
    ) {
        $user = Auth::user();

        self::simpan([
            'user_id'        => Auth::id(),
            'username'       => $user->username ?? '-',
            'role'           => $user->role ?? '-',
            'aktivitas'      => $aktivitas,
            'modul'          => $modul,
            'no_rm'          => $noRm,
            'bidang_kolom'   => $kolom,
            'sebelum'        => $sebelum,
            'sesudah'        => $sesudah,
            'status'         => $status,
            'ip_address'     => request()->ip(),
            'nama_perangkat' => gethostname() ?: request()->ip(),
        ]);
    }

    public static function catatGagal($aktivitas, $modul, $usernameDiinput = '-')
    {
        self::simpan([
            'user_id'        => null,
            'username'       => $usernameDiinput ?: '-',
            'role'           => '-',
            'aktivitas'      => $aktivitas,
            'modul'          => $modul,
            'no_rm'          => null,
            'status'         => 'gagal',
            'ip_address'     => request()->ip(),
            'nama_perangkat' => gethostname() ?: request()->ip(),
        ]);
    }

    /**
     * Ubah nilai mentah menjadi teks yang mudah dibaca.
     *
     * $pemeta untuk satu field boleh berupa:
     *  - array   : [nilai => keterangan], mis. ['1' => 'Laki-laki', '2' => 'Perempuan']
     *  - callable: fn ($nilai) => 'keterangan', mis. fn ($id) => Poli::find($id)?->nama_poli
     *  - null    : nilai dipakai apa adanya
     *
     * Nilai berupa array (mis. daftar ID diagnosa) diurai satu per satu
     * lalu digabung dengan koma.
     */
    private static function keTeks($nilai, $pemeta = null): string
    {
        if (is_array($nilai)) {
            $bagian = array_map(fn ($n) => self::keTeks($n, $pemeta), $nilai);
            $bagian = array_filter($bagian, fn ($t) => $t !== '-');

            return $bagian ? implode(', ', $bagian) : '-';
        }

        if ($nilai === null || trim((string) $nilai) === '') {
            return '-';
        }

        $ket = null;

        if (is_array($pemeta)) {
            $ket = $pemeta[(string) $nilai] ?? null;
        } elseif (is_callable($pemeta)) {
            $ket = $pemeta($nilai);
        }

        // Ada keterangan: tampilkan keterangannya. Tidak ada: tampilkan nilai aslinya.
        return ($ket !== null && trim((string) $ket) !== '')
            ? (string) $ket
            : (string) $nilai;
    }

    /**
     * Catat setiap field yang berubah, lengkap dengan keterangannya.
     *
     * @param array $label      [field => 'Nama kolom yang mudah dibaca']
     * @param array $keterangan [field => array pemeta | callable]  (opsional)
     *
     * Contoh hasil:
     *   Mengubah Jenis Kelamin: 'Laki-laki' menjadi 'Perempuan'
     *   Mengubah Diagnosa Utama: 'I10 - Hipertensi' menjadi 'M51 - HNP'
     */
    public static function catatPerubahan(
        $modul,
        $noRm,
        array $lama,
        array $baru,
        array $label = [],
        array $keterangan = []
    ) {
        foreach ($baru as $field => $nilaiBaru) {
            $nilaiLama = $lama[$field] ?? null;

            $pemeta   = $keterangan[$field] ?? (self::pemetaBawaan()[$field] ?? null);
            $teksLama = self::keTeks($nilaiLama, $pemeta);
            $teksBaru = self::keTeks($nilaiBaru, $pemeta);

            // Bandingkan teks akhirnya, supaya tidak tercatat bila sebenarnya sama.
            if ($teksLama === $teksBaru) {
                continue;
            }

            $namaKolom = $label[$field] ?? $field;

            self::catat(
                "Mengubah {$namaKolom}: '{$teksLama}' menjadi '{$teksBaru}'",
                $modul,
                $noRm,
                $namaKolom,
                $teksLama,
                $teksBaru,
                'berhasil'
            );
        }
    }
}