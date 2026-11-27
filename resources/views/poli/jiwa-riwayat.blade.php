@extends('layouts.app')

@section('title', 'Riwayat Kunjungan Poli Jiwa - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-brain"></i>
@endsection
@section('header-title', 'Poli Jiwa')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jiwa-riwayat.css') }}?v={{ @filemtime(public_path('css/jiwa-riwayat.css')) }}">
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai bila controller belum mengirim $pasien dan $riwayat.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien) || !isset($riwayat)) {
            $dummy = [
                'E000041' => ['nama' => 'Sri Wahyuni',     'jk' => 'Perempuan', 'lahir' => '1985-03-18', 'hp' => '081345670001',
                    'riwayat' => [['20-11-2026','Jiwa'],['06-11-2026','Jiwa'],['22-10-2026','Saraf'],['09-10-2026','Jiwa'],['18-09-2026','Jiwa']]],
                'E000042' => ['nama' => 'Agus Setiawan',   'jk' => 'Laki-laki', 'lahir' => '1992-08-09', 'hp' => '081345670002',
                    'riwayat' => [['24-11-2026','Jiwa'],['10-11-2026','Jiwa'],['27-10-2026','Jiwa'],['29-09-2026','Jantung']]],
                'E000043' => ['nama' => 'Dewi Anggraini', 'jk' => 'Perempuan', 'lahir' => '1998-01-27', 'hp' => '081345670003',
                    'riwayat' => [['26-11-2026','Jiwa'],['12-11-2026','Jiwa'],['15-10-2026','Obgyn'],['01-10-2026','Jiwa'],['03-09-2026','Jiwa'],['13-08-2026','Jiwa']]],
                'E000044' => ['nama' => 'Rudi Hermawan',   'jk' => 'Laki-laki', 'lahir' => '1979-10-30', 'hp' => '081345670004',
                    'riwayat' => [['25-11-2026','Jiwa'],['11-11-2026','Saraf'],['21-10-2026','Jiwa']]],
                'E000045' => ['nama' => 'Nur Aini',        'jk' => 'Perempuan', 'lahir' => '2001-05-14', 'hp' => '081345670005',
                    'riwayat' => [['23-11-2026','Jiwa'],['09-11-2026','Jiwa'],['19-10-2026','Obgyn'],['05-10-2026','Jiwa'],['14-09-2026','Jiwa'],['24-08-2026','Jiwa'],['03-08-2026','Jiwa']]],
                'E000046' => ['nama' => 'Fajar Nugroho',   'jk' => 'Laki-laki', 'lahir' => '1988-12-02', 'hp' => '081345670006',
                    'riwayat' => [['27-11-2026','Jiwa'],['13-11-2026','Jantung'],['30-10-2026','Jiwa'],['16-10-2026','Jiwa']]],
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
                            <tr>
                                <td class="jr-no">{{ $i + 1 }}</td>
                                <td class="jr-tgl">{{ $r->tanggal }}</td>
                                <td class="jr-poli">{{ $r->poli }}</td>
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

@endsection