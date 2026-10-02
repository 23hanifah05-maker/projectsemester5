@extends('layouts.app')

@section('title', 'Kunjungan Poli Syaraf - Klinik Utama Merah Putih')
@section('header-icon', '🩺')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/syaraf-riwayat.css') }}">
@endsection

@section('content')

    {{-- Data dummy pasien Poli Syaraf --}}
    @php
        $semuaPasien = $semuaPasien ?? [
            (object)['no_rm'=>'RM-0001','nama_pasien'=>'Budi Santoso','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1980-01-01','umur'=>'46 Tahun','no_hp'=>'081234567890'],
            (object)['no_rm'=>'RM-0003','nama_pasien'=>'Andi Pratama','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Malang','tgl_lahir'=>'1990-03-15','umur'=>'36 Tahun','no_hp'=>'082112223333'],
            (object)['no_rm'=>'RM-0005','nama_pasien'=>'Rudi Hartono','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Mojokerto','tgl_lahir'=>'1975-06-12','umur'=>'51 Tahun','no_hp'=>'081377889900'],
            (object)['no_rm'=>'RM-0007','nama_pasien'=>'Fajar Ramadhan','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1998-01-18','umur'=>'28 Tahun','no_hp'=>'083811223344'],
            (object)['no_rm'=>'RM-0009','nama_pasien'=>'Agus Setiawan','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Surabaya','tgl_lahir'=>'1970-06-22','umur'=>'56 Tahun','no_hp'=>'081245678901'],
        ];

        $noRmAktif = $no_rm ?? request('no_rm');
        $pasien = collect($semuaPasien)->firstWhere('no_rm', $noRmAktif);

        $riwayatPerPasien = [
            'RM-0001' => [
                ['tanggal' => '2026-09-12', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-06-03', 'poli' => 'Poli Jantung'],
                ['tanggal' => '2026-02-18', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2025-10-25', 'poli' => 'Poli Radiologi'],
            ],
            'RM-0003' => [
                ['tanggal' => '2026-08-20', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-03-11', 'poli' => 'Poli Jiwa'],
            ],
            'RM-0005' => [
                ['tanggal' => '2026-09-01', 'poli' => 'Poli Jantung'],
                ['tanggal' => '2026-07-15', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-04-02', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2025-12-09', 'poli' => 'Poli Radiologi'],
                ['tanggal' => '2025-08-22', 'poli' => 'Poli Jantung'],
            ],
            'RM-0007' => [
                ['tanggal' => '2026-05-30', 'poli' => 'Poli Saraf'],
            ],
            'RM-0009' => [
                ['tanggal' => '2026-09-05', 'poli' => 'Poli Jantung'],
                ['tanggal' => '2026-06-27', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-01-14', 'poli' => 'Poli Jantung'],
            ],
        ];

        $riwayat = $riwayatPerPasien[$noRmAktif] ?? [];
    @endphp

    <div class="kj-card">

        <!-- KOTAK DATA PASIEN -->
        <div class="kj-datapasien-box">
            <div class="kj-section-title">Data Pasien</div>

            @if ($pasien)
                <div class="kj-fields">
                    <div class="kj-field">
                        <span class="kj-label">No RM</span>
                        <span class="kj-value">{{ $pasien->no_rm }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Tempat, Tgl Lahir</span>
                        <span class="kj-value">{{ $pasien->tempat_lahir }}, {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Nama</span>
                        <span class="kj-value">{{ $pasien->nama_pasien }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Umur</span>
                        <span class="kj-value">{{ $pasien->umur }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Jenis Kelamin</span>
                        <span class="kj-value">{{ $pasien->jenis_kelamin }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">No Hp</span>
                        <span class="kj-value">{{ $pasien->no_hp }}</span>
                    </div>
                </div>
            @else
                <p class="kj-kosong">Data pasien tidak ditemukan.</p>
            @endif
        </div>

        <!-- TABEL RIWAYAT KUNJUNGAN -->
        <div class="kj-riwayat-box">
            <div class="kj-riwayat-title">Riwayat Kunjungan</div>

            <table class="kj-table">
                <thead>
                    <tr><th>No</th><th>Tgl. Kunjungan</th><th>Riwayat Kunjungan</th></tr>
                </thead>
                <tbody>
                    @forelse ($riwayat as $i => $item)
                        @php
                            $urlDetail = route('poli.syaraf.kunjungan.detail', [
                                'no_rm'   => $noRmAktif,
                                'tanggal' => $item['tanggal'],
                            ]);
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <a href="{{ $urlDetail }}" class="kj-link">
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $urlDetail }}" class="kj-link">
                                    {{ $item['poli'] }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="kj-kosong">Belum ada riwayat kunjungan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection