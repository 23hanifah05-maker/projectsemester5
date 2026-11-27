@extends('layouts.app')

@section('title', 'Kunjungan Poli Jiwa - Klinik Utama Merah Putih')
@section('header-title', 'Poli Jiwa')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jiwa-riwayat.css') }}?v={{ @filemtime(public_path('css/jiwa-riwayat.css')) }}">
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai bila controller belum mengirim $pasien dan $riwayat.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien) || !isset($riwayat)) {
            $dummy = [
                '000041' => ['nama_pasien'=>'Sri Wahyuni','jk'=>'Perempuan','tempat_lahir'=>'Ngawi','tgl_lahir'=>'1985-03-18','no_hp'=>'081345670001',
                    'riwayat'=>[['2026-11-27','jiwa'],['2026-11-20','saraf'],['2026-11-06','jiwa'],['2026-10-09','jiwa']]],
                '000042' => ['nama_pasien'=>'Agus Setiawan','jk'=>'Laki-laki','tempat_lahir'=>'Ngawi','tgl_lahir'=>'1992-08-09','no_hp'=>'081345670002',
                    'riwayat'=>[['2026-11-27','jiwa'],['2026-11-10','jiwa'],['2026-09-29','jantung']]],
                '000043' => ['nama_pasien'=>'Dewi Anggraini','jk'=>'Perempuan','tempat_lahir'=>'Ngawi','tgl_lahir'=>'1998-01-27','no_hp'=>'081345670003',
                    'riwayat'=>[['2026-11-27','jiwa'],['2026-11-12','jiwa'],['2026-10-15','obgyn'],['2026-09-03','jiwa']]],
                '000044' => ['nama_pasien'=>'Rudi Hermawan','jk'=>'Laki-laki','tempat_lahir'=>'Ngawi','tgl_lahir'=>'1979-10-30','no_hp'=>'081345670004',
                    'riwayat'=>[['2026-11-26','jiwa'],['2026-11-11','saraf']]],
            ];

            $namaPoliDummy = ['jantung' => 'Jantung', 'jiwa' => 'Jiwa', 'saraf' => 'Saraf', 'obgyn' => 'Obgyn'];

            $kunciRm = isset($no_rm) && isset($dummy[$no_rm]) ? $no_rm : array_key_first($dummy);
            $d = $dummy[$kunciRm];

            $pasien = (object) [
                'no_rm'         => $kunciRm,
                'nama_pasien'   => $d['nama_pasien'],
                'jenis_kelamin' => $d['jk'],
                'tempat_lahir'  => $d['tempat_lahir'],
                'tgl_lahir'     => $d['tgl_lahir'],
                'no_hp'         => $d['no_hp'],
            ];

            $riwayat = collect($d['riwayat'])->map(fn ($r, $k) => (object) [
                'id_pendaftaran' => $k + 1,
                'tgl_registrasi' => $r[0],
                'poli'           => (object) ['slug' => $r[1], 'nama_poli' => $namaPoliDummy[$r[1]] ?? $r[1]],
            ]);
        }

        $noRmAktif = $pasien->no_rm;

        $umurPasien = $umur ?? (
            !empty($pasien->tgl_lahir)
                ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age . ' tahun'
                : '-'
        );

        $jenisKelaminPasien = $jenisKelamin
            ?? (is_numeric($pasien->jenis_kelamin)
                ? match ((string) $pasien->jenis_kelamin) {
                    '1' => 'Laki-laki',
                    '2' => 'Perempuan',
                    default => '-',
                }
                : $pasien->jenis_kelamin);

        // slug poli -> nama route halaman detail kunjungan
        $routeDetail = [
            'obgyn'   => 'poli.obgyn.kunjungan.detail',
            'saraf'   => 'poli.syaraf.kunjungan.detail',
            'jantung' => 'poli.jantung.kunjungan.detail',
            'jiwa'    => 'poli.jiwa.kunjungan.detail',
        ];
    @endphp

    <div class="obr-card">

        {{-- DATA PASIEN --}}
        <div class="obr-datapasien-box">
            <div class="obr-section-title">Data Pasien</div>

            <div class="obr-fields">

                <div class="obr-field">
                    <span class="obr-label">No RM</span>
                    <span class="obr-value">{{ $pasien->no_rm }}</span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Tempat, Tgl Lahir</span>
                    <span class="obr-value">
                        {{ $pasien->tempat_lahir ?? '-' }},
                        {{ !empty($pasien->tgl_lahir)
                            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y')
                            : '-' }}
                    </span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Nama</span>
                    <span class="obr-value">{{ $pasien->nama_pasien ?? $pasien->nama ?? '-' }}</span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Umur</span>
                    <span class="obr-value">{{ $umurPasien }}</span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Jenis Kelamin</span>
                    <span class="obr-value">{{ $jenisKelaminPasien }}</span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">No Hp</span>
                    <span class="obr-value">{{ $pasien->no_hp ?? '-' }}</span>
                </div>

            </div>
        </div>

        {{-- RIWAYAT KUNJUNGAN --}}
        <div class="obr-riwayat-box">
            <div class="obr-riwayat-title">Riwayat Kunjungan</div>

            <table class="obr-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl. Kunjungan</th>
                        <th>Riwayat Kunjungan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($riwayat as $i => $item)

                        @php
                            $tglItem = $item->tgl_registrasi ?? $item->tanggal ?? null;
                            $tanggalKunjungan = $tglItem ? \Carbon\Carbon::parse($tglItem) : null;

                            $poliItem = $item->poli ?? null;
                            $namaPoli = is_object($poliItem)
                                ? ($poliItem->nama_poli ?? 'Poli tidak diketahui')
                                : ((string) $poliItem ?: 'Poli tidak diketahui');

                            // slug: dari data poli bila ada, kalau tidak dari nama poli
                            $slugPoli = is_object($poliItem) && !empty($poliItem->slug)
                                ? $poliItem->slug
                                : strtolower(trim(preg_replace('/^poli\s+/i', '', $namaPoli)));
                            if ($slugPoli === 'syaraf') $slugPoli = 'saraf';

                            $namaRoute = $routeDetail[$slugPoli] ?? null;
                            $urlDetail = ($namaRoute && \Illuminate\Support\Facades\Route::has($namaRoute))
                                ? route($namaRoute, [
                                    'no_rm' => $noRmAktif,
                                    'id_pendaftaran' => $item->id_pendaftaran ?? ($i + 1),
                                ])
                                : null;
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td>
                                @if ($urlDetail)
                                    <a href="{{ $urlDetail }}" class="obr-link">
                                        {{ $tanggalKunjungan ? $tanggalKunjungan->format('d-m-Y') : '-' }}
                                    </a>
                                @else
                                    {{ $tanggalKunjungan ? $tanggalKunjungan->format('d-m-Y') : '-' }}
                                @endif
                            </td>

                            <td>
                                @if ($urlDetail)
                                    <a href="{{ $urlDetail }}" class="obr-link">{{ $namaPoli }}</a>
                                @else
                                    {{ $namaPoli }}
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="3" class="obr-kosong">Belum ada riwayat kunjungan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection