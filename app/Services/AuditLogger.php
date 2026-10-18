<?php

namespace App\Services;

use App\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    /**
     * Catat satu aktivitas (Login, Tambah, Hapus, dsb).
     */
    public static function catat(
        string $aktivitas,
        string $modul,
        ?string $noRm = null,
        ?string $bidang = null,
        $sebelum = null,
        $sesudah = null,
        string $status = 'Berhasil'
    ): void {
        try {
            [$userId, $username, $role] = self::pengguna();
            $ip = request()->ip();

            AuditTrail::create([
                'user_id'        => $userId,
                'username'       => $username,
                'role'           => $role,
                'aktivitas'      => $aktivitas,
                'modul'          => $modul,
                'no_rm'          => $noRm,
                'bidang_kolom'   => $bidang,
                'sebelum'        => $sebelum,
                'sesudah'        => $sesudah,
                'status'         => $status,
                'ip_address'     => $ip,
                'nama_perangkat' => self::namaPerangkat($ip),
            ]);
        } catch (\Throwable $e) {
            // Kegagalan audit tidak boleh menggagalkan proses utama.
            report($e);
        }
    }

    /**
     * Bandingkan data lama dan baru. Setiap field yang berubah
     * dicatat sebagai satu baris log (2 perubahan = 2 log).
     *
     * @param array $label  [nama_kolom => 'Label Tampilan']
     */
    public static function catatPerubahan(
        string $modul,
        ?string $noRm,
        array $lama,
        array $baru,
        array $label = []
    ): void {
        foreach ($baru as $kolom => $nilaiBaru) {
            $nilaiLama = $lama[$kolom] ?? null;

            if ((string) $nilaiLama === (string) $nilaiBaru) {
                continue;
            }

            self::catat(
                'Edit',
                $modul,
                $noRm,
                $label[$kolom] ?? $kolom,
                $nilaiLama,
                $nilaiBaru
            );
        }
    }

    private static function pengguna(): array
    {
        $user = Auth::user();

        if ($user) {
            return [
                $user->getAuthIdentifier(),
                $user->username ?? $user->name ?? null,
                $user->role ?? null,
            ];
        }

        // Cadangan jika login memakai session biasa
        return [
            session('user_id'),
            session('username'),
            session('role'),
        ];
    }

    private static function namaPerangkat(?string $ip): ?string
    {
        if (!$ip) {
            return null;
        }

        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return gethostname() ?: 'localhost';
        }

        $nama = @gethostbyaddr($ip);

        return ($nama && $nama !== $ip) ? $nama : $ip;
    }
}