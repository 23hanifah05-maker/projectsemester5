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
}