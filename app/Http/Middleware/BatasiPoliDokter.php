<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class BatasiPoliDokter
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role === 'dokter') {

            // slug poli milik dokter yang sedang login (username = nama_dokter)
            $poliSaya = DB::table('poli')
                ->where('nama_dokter', $user->username)
                ->value('slug');

            // dipakai di view: $poliDokterSaya
            View::share('poliDokterSaya', $poliSaya);

            // pembatasan hanya untuk URL berawalan /poli/...
            if ($request->segment(1) === 'poli') {

                $slug = $request->segment(2);

                // /poli/show/{slug}: slug ada di segmen ke-3
                if ($slug === 'show') {
                    $slug = $request->segment(3);
                }

                // di URL tertulis "syaraf", di tabel poli tertulis "saraf"
                if ($slug === 'syaraf') {
                    $slug = 'saraf';
                }

                if ($slug) {
                    $poli = DB::table('poli')->where('slug', $slug)->first();

                    if ($poli && $poli->nama_dokter !== $user->username) {

                        // kembalikan ke halaman poli milik dokter itu sendiri
                        $rute = [
                            'saraf'   => 'poli.syaraf',
                            'obgyn'   => 'poli.obgyn',
                            'jantung' => 'poli.jantung',
                            'jiwa'    => 'poli.jiwa',
                        ];

                        return redirect()->route($rute[$poliSaya] ?? 'dashboard');
                    }
                }
            }
        }

        return $next($request);
    }
}