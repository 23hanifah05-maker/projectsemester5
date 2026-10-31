<?php

namespace App\Helpers;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class Audit
{
    public static function catat($aktivitas, $modul, $noRm = null)
    {
        $user = Auth::user();

        AuditTrail::create([
            'user_id'        => Auth::id(),
            'username'       => $user->username ?? '-',
            'role'           => $user->role ?? '-',
            'aktivitas'      => $aktivitas,
            'modul'          => $modul,
            'no_rm'          => $noRm,
            'status'         => 'berhasil',
            'ip_address'     => request()->ip(),
            'nama_perangkat' => gethostname(),
        ]);
    }

    public static function catatPerubahan($modul, $noRm, array $lama, array $baru, array $label = [])
    {
        foreach ($baru as $field => $nilaiBaru) {
            $nilaiLama = $lama[$field] ?? null;

            if ((string) $nilaiLama !== (string) $nilaiBaru) {
                $nama = $label[$field] ?? $field;

                self::catat(
                    "Mengubah {$nama}: '{$nilaiLama}' menjadi '{$nilaiBaru}'",
                    $modul,
                    $noRm
                );
            }
        }
    }
}