<?php

namespace App\Helpers;

class Mask
{
    /**
     * Sembunyikan NIK: hanya beberapa digit awal yang tampil.
     * Contoh: 3521130298744440 -> 3521XXXXXXXXXXXX
     *
     * @param string|null $nik    NIK lengkap
     * @param int         $tampil jumlah digit awal yang ditampilkan
     * @param string      $simbol karakter penutup
     */
    public static function nik(?string $nik, int $tampil = 4, string $simbol = 'X'): string
    {
        $nik = trim((string) $nik);

        if ($nik === '') {
            return '-';
        }

        if (strlen($nik) <= $tampil) {
            return $nik;
        }

        return substr($nik, 0, $tampil) . str_repeat($simbol, strlen($nik) - $tampil);
    }
}