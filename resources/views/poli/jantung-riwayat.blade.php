@extends('layouts.app')

@section('title', 'Riwayat Kunjungan Poli Jantung - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-stethoscope"></i>
@endsection
@section('header-title', 'Poli Jantung')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jantung-riwayat.css') }}?v={{ @filemtime(public_path('css/jantung-riwayat.css')) }}">
    <style>
        /* Baris riwayat yang bisa diklik */
        .jr-tabel tbody tr.jr-klik { cursor: pointer; }
        .jr-tabel tbody tr.jr-klik:hover { background: #fdecec; }
        .jr-tabel tbody tr.jr-klik a { color: inherit; text-decoration: none; }
    </style>
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai bila controller belum mengirim $pasien dan $riwayat.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien) || !isset($riwayat)) {
            $dummy = [
                'E000031' => ['nama' => 'Hendra Wijaya',    'jk' => 'Laki-laki', 'lahir' => '1970-04-15', 'hp' => '081234560001',
                    'riwayat' => [['20-11-2026','Jantung'],['06-11-2026','Jantung'],['22-10-2026','Saraf'],['09-10-2026','Jantung'],['18-09-2026','Jantung']]],
                'E000032' => ['nama' => 'Lestari Wulandari','jk' => 'Perempuan', 'lahir' => '1968-09-22', 'hp' => '081234560002',
                    'riwayat' => [['24-11-2026','Jantung'],['10-11-2026','Jantung'],['27-10-2026','Jantung'],['29-09-2026','Obgyn']]],
                'E000033' => ['nama' => 'Bambang Hartono',  'jk' => 'Laki-laki', 'lahir' => '1975-11-02', 'hp' => '081234560003',
                    'riwayat' => [['26-11-2026','Jantung'],['12-11-2026','Jantung'],['15-10-2026','Jiwa'],['01-10-2026','Jantung'],['03-09-2026','Jantung'],['13-08-2026','Jantung']]],
                'E000034' => ['nama' => 'Ratna Kusuma',     'jk' => 'Perempuan', 'lahir' => '1982-07-13', 'hp' => '081234560004',
                    'riwayat' => [['25-11-2026','Jantung'],['11-11-2026','Obgyn'],['21-10-2026','Jantung']]],
                'E000035' => ['nama' => 'Yusuf Ramadhan',   'jk' => 'Laki-laki', 'lahir' => '1960-06-08', 'hp' => '081234560005',
                    'riwayat' => [['23-11-2026','Jantung'],['09-11-2026','Jantung'],['19-10-2026','Saraf'],['05-10-2026','Jantung'],['14-09-2026','Jantung'],['24-08-2026','Jantung'],['03-08-2026','Jantung']]],
                'E000036' => ['nama' => 'Mega Puspitasari', 'jk' => 'Perempuan', 'lahir' => '1990-12-24', 'hp' => '081234560006',
                    'riwayat' => [['27-11-2026','Jantung'],['13-11-2026','Jiwa'],['30-10-2026','Jantung'],['16-10-2026','Jantung']]],
            ];

            $d = $dummy[$no_rm] ?? null;

            $pasien = (object) [
                'no_rm'        => $no_rm,
                'nama'         => $d['nama'] ?? '-',
                'jenis_kelamin'=> $d['jk'] ?? '-',
                'tempat_lahir' => 'Ngawi',
                'tgl_lahir'    => $d['lahir'] ?? null,
                'no_hp'        => $d['hp'] ?? '-',
            ];

            $riwayat = collect($d['riwayat'] ?? [])->map(fn ($r) => (object) [
                'tanggal' => $r[0],
                'poli'    => $r[1],
            ]);
        }

        $tglLahirTampil = !empty($pasien->tgl_lahir)
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') : '-';
        $umurTampil = !empty($pasien->tgl_lahir)
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age . ' tahun' : '-';
    @endphp

    <div class="jr-wrap">

        {{-- ===== DATA PASIEN ===== --}}
        <div class="jr-card">
            <div class="jr-card-title">Data Pasien</div>

            <div class="jr-card-body">
                <div class="jr-col">
                    <div class="jr-row">
                        <label>No RM</label>
                        <input type="text" value="{{ $pasien->no_rm }}" readonly>
                    </div>
                    <div class="jr-row">
                        <label>Nama</label>
                        <input type="text" value="{{ $pasien->nama }}" readonly>
                    </div>
                    <div class="jr-row">
                        <label>Jenis Kelamin</label>
                        <input type="text" value="{{ $pasien->jenis_kelamin }}" readonly>
                    </div>
                </div>

                <div class="jr-col">
                    <div class="jr-row">
                        <label>Tempat, Tgl Lahir</label>
                        <input type="text" value="{{ ($pasien->tempat_lahir ?? '-') . ', ' . $tglLahirTampil }}" readonly>
                    </div>
                    <div class="jr-row">
                        <label>Umur</label>
                        <input type="text" value="{{ $umurTampil }}" readonly>
                    </div>
                    <div class="jr-row">
                        <label>No Hp</label>
                        <input type="text" value="{{ $pasien->no_hp }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== RIWAYAT KUNJUNGAN ===== --}}
        <div class="jr-card">
            <div class="jr-card-title">Riwayat Kunjungan</div>

            <div class="jr-card-body jr-tabel-wrap">
                <table class="jr-tabel">
                    <thead>
                        <tr>
                            <th class="jr-no">No</th>
                            <th class="jr-tgl">Tgl. Kunjungan</th>
                            <th>Riwayat Kunjungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $i => $r)
                            @php
                                // Hanya kunjungan Poli Jantung yang membuka halaman kunjungan baru Jantung.
                                $namaPoli = is_object($r->poli) ? ($r->poli->nama_poli ?? '') : (string) $r->poli;
                                $bisaDibuka = stripos($namaPoli, 'jantung') !== false;
                                $urlKunjungan = $bisaDibuka
                                    ? route('poli.jantung.kunjungan.detail', [$pasien->no_rm, $r->id_pendaftaran ?? ($i + 1)])
                                    : null;
                            @endphp
                            <tr @if ($bisaDibuka) class="jr-klik" data-url="{{ $urlKunjungan }}" tabindex="0" @endif>
                                <td class="jr-no">{{ $i + 1 }}</td>
                                <td class="jr-tgl">
                                    @if ($bisaDibuka)
                                        <a href="{{ $urlKunjungan }}">{{ $r->tanggal }}</a>
                                    @else
                                        {{ $r->tanggal }}
                                    @endif
                                </td>
                                <td class="jr-poli">
                                    @if ($bisaDibuka)
                                        <a href="{{ $urlKunjungan }}">{{ $namaPoli }}</a>
                                    @else
                                        {{ $namaPoli }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="jr-kosong">Belum ada riwayat kunjungan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script>
        // Klik di mana saja pada baris -> buka halaman kunjungan Poli Jantung
        document.querySelectorAll('tr.jr-klik').forEach(function (tr) {
            tr.addEventListener('click', function () {
                window.location.href = tr.dataset.url;
            });
            tr.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') window.location.href = tr.dataset.url;
            });
        });
    </script>

@endsection