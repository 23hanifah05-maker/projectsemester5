<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class BatasiPoliDokter
{
    private const DAFTAR_POLI = ['saraf', 'obgyn', 'jantung', 'jiwa'];

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'dokter') {
            return $next($request);
        }

        // slug poli milik dokter yang sedang login (username = nama_dokter)
        $poliSaya = DB::table('poli')
            ->where('nama_dokter', $user->username)
            ->value('slug');

        // 1) poli menurut URL
        $slugUrl = null;

        if ($request->segment(1) === 'poli') {
            $slugUrl = $request->segment(2);

            // /poli/show/{slug}: slug ada di segmen ke-3
            if ($slugUrl === 'show') {
                $slugUrl = $request->segment(3);
            }

            // di URL tertulis "syaraf", di tabel poli tertulis "saraf"
            if ($slugUrl === 'syaraf') {
                $slugUrl = 'saraf';
            }
        }

        $hanyaLihat = $slugUrl
            && in_array($slugUrl, self::DAFTAR_POLI, true)
            && $slugUrl !== $poliSaya;

        // 2) poli menurut data kunjungan yang dikirim (untuk simpan/ubah)
        if (!$request->isMethodSafe()) {
            $idKunjungan = $request->input('id_pendaftaran')
                ?? $request->input('id_kunjungan')
                ?? $request->route('id');

            if ($idKunjungan) {
                $slugKunjungan = DB::table('pendaftaran')
                    ->join('poli', 'pendaftaran.id_poli', '=', 'poli.id_poli')
                    ->where('pendaftaran.id_pendaftaran', $idKunjungan)
                    ->value('poli.slug');

                if ($slugKunjungan && $slugKunjungan !== $poliSaya) {
                    $hanyaLihat = true;
                }
            }
        }

        // dipakai di view: $poliDokterSaya dan $hanyaLihat
        View::share('poliDokterSaya', $poliSaya);
        View::share('hanyaLihat', $hanyaLihat);

        // di poli lain, semua aksi selain melihat ditolak server
        if ($hanyaLihat && !$request->isMethodSafe()) {
            $pesan = 'Anda hanya dapat melihat data poli ini, tidak dapat mengubahnya.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $pesan,
                ], 403);
            }

            return back()->with('error', $pesan);
        }

        $response = $next($request);

        // di poli lain, kunci kolom isian dan tombol aksi di tampilan
        if ($hanyaLihat
            && $request->isMethodSafe()
            && !$response->isRedirection()
            && str_contains((string) $response->headers->get('Content-Type'), 'text/html')
            && is_string($response->getContent())
        ) {
            $script = <<<'JS'
<script>
document.addEventListener('DOMContentLoaded', function () {
    var luar = 'aside, nav, header, .sidebar, #sidebar, .topbar, .navbar';

    document.querySelectorAll('textarea, input, select').forEach(function (el) {
        if (el.closest(luar)) return;
        var t = (el.type || '').toLowerCase();
        if (t === 'hidden' || t === 'search') return;
        if (el.name === 'keyword' || el.name === '_token') return;
        el.disabled = true;
    });

    document.querySelectorAll('button, input[type=submit]').forEach(function (el) {
        if (el.closest(luar)) return;
        var teks = (el.textContent || el.value || '').trim().toLowerCase();
        if (/(simpan|rujuk|selesai|kirim|hapus|edit|ubah|tambah)/.test(teks)) {
            el.disabled = true;
            el.style.opacity = '0.45';
            el.style.cursor = 'not-allowed';
        }
    });
});
</script>
JS;

            $response->setContent(
                str_ireplace('</body>', $script . '</body>', $response->getContent())
            );
        }

        return $response;
    }
}