<?php

namespace App\Helpers;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class Audit
{
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

        AuditTrail::create([
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
        AuditTrail::create([
            'user_id'        => null,
            'username'       => $usernameDiinput ?: '-',
            'role'           => '-',
            'aktivitas'      => $aktivitas,
            'modul'          => $modul,
            'no_rm'          => null,
            'status'         => 'gagal',
            'ip_address'     => request()->ip(),
            'nama_perangkat' => gethostname(),
        ]);
    }

    public static function catatPerubahan($modul, $noRm, array $lama, array $baru, array $label = [])
    {
        foreach ($baru as $field => $nilaiBaru) {
            $nilaiLama = $lama[$field] ?? null;

            if ((string) $nilaiLama !== (string) $nilaiBaru) {
                $namaKolom = $label[$field] ?? $field;

                self::catat(
                    "Mengubah {$namaKolom}: '{$nilaiLama}' menjadi '{$nilaiBaru}'",
                    $modul,
                    $noRm,
                    $namaKolom,
                    (string) $nilaiLama,
                    (string) $nilaiBaru,
                    'berhasil'
                );
            }
        }
    }
}