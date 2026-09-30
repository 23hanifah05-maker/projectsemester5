<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PencarianKodeController extends Controller
{
    /**
     * Cari diagnosis (ICD-10) atau tindakan (ICD-9-CM) berdasarkan kode atau nama.
     * Dipanggil lewat: GET /cari-kode/{penyakit|tindakan}?q=kata
     * Mengembalikan JSON: [{ id, kode, nama }, ...] maksimal 15 baris.
     */
    public function cari(Request $request, string $jenis)
    {
        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([]);
        }

        // escape karakter wildcard agar "%" atau "_" yang diketik tidak ikut menjadi pola
        $q = addcslashes($q, '%_\\');

        if ($jenis === 'penyakit') {
            $query = DB::table('kode_diagnosis')
                ->select('id_icd10 as id', 'icd_10 as kode', 'diagnosa as nama')
                ->where(function ($w) use ($q) {
                    $w->where('icd_10', 'like', $q . '%')
                      ->orWhere('diagnosa', 'like', '%' . $q . '%');
                })
                ->orderBy('icd_10');
        } else {
            $query = DB::table('kode_tindakan')
                ->select('id_icd9 as id', 'icd_9 as kode', 'tindakan as nama')
                ->where(function ($w) use ($q) {
                    $w->where('icd_9', 'like', $q . '%')
                      ->orWhere('tindakan', 'like', '%' . $q . '%');
                })
                ->orderBy('icd_9');
        }

        return response()->json($query->limit(15)->get());
    }
}