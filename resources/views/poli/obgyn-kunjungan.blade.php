@extends('layouts.app')

@section('title', 'Kunjungan Poli Obgyn - Klinik Utama Merah Putih')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/obgyn-riwayat.css') }}">
    <style>
        .obr-link {
            color: #b81d24;
            font-weight: 600;
            text-decoration: none;
        }
        .obr-link:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')

    {{-- Data dummy pasien Poli Obgyn — SAMA PERSIS dengan obgyn.blade.php --}}
    @php
        $semuaPasienObgyn = $semuaPasienObgyn ?? [
            (object)['no_rm'=>'RM-0002','nama_pasien'=>'Siti Aminah','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Surabaya','tgl_lahir'=>'1985-05-12','umur'=>'41 Tahun','no_hp'=>'081355667788'],
            (object)['no_rm'=>'RM-0004','nama_pasien'=>'Dewi Lestari','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Surabaya','tgl_lahir'=>'1992-07-05','umur'=>'34 Tahun','no_hp'=>'082233445566'],
            (object)['no_rm'=>'RM-0006','nama_pasien'=>'Lina Marlina','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1980-08-15','umur'=>'46 Tahun','no_hp'=>'081366778899'],
            (object)['no_rm'=>'RM-0008','nama_pasien'=>'Nur Aisyah','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Gresik','tgl_lahir'=>'1984-03-02','umur'=>'42 Tahun','no_hp'=>'083855667788'],
            (object)['no_rm'=>'RM-0010','nama_pasien'=>'Sri Wahyuni','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1975-07-18','umur'=>'51 Tahun','no_hp'=>'081298112233'],
        ];

        $noRmAktif = $no_rm ?? request('no_rm');

        $pasien = $pasien ?? collect($semuaPasienObgyn)
            ->firstWhere('no_rm', $noRmAktif);

        // Dummy riwayat kunjungan — beda per pasien
        $riwayatPerPasienObgyn = [
            'RM-0002' => [
                ['tanggal' => '2026-09-14', 'poli' => 'Poli Obgyn'],
                ['tanggal' => '2026-06-20', 'poli' => 'Poli Obgyn'],
                ['tanggal' => '2026-02-08', 'poli' => 'Poli Radiologi'],
            ],

            'RM-0004' => [
                ['tanggal' => '2026-09-02', 'poli' => 'Poli Obgyn'],
                ['tanggal' => '2026-04-11', 'poli' => 'Poli Obgyn'],
            ],

            'RM-0006' => [
                ['tanggal' => '2026-08-25', 'poli' => 'Poli Obgyn'],
                ['tanggal' => '2026-05-19', 'poli' => 'Poli Radiologi'],
                ['tanggal' => '2026-01-30', 'poli' => 'Poli Obgyn'],
                ['tanggal' => '2025-11-06', 'poli' => 'Poli Jantung'],
            ],

            'RM-0008' => [
                ['tanggal' => '2026-07-09', 'poli' => 'Poli Obgyn'],
            ],

            'RM-0010' => [],
        ];

        $riwayat = $riwayatPerPasienObgyn[$noRmAktif] ?? [];
    @endphp

    <div class="obr-card">

        <div class="obr-datapasien-box">
            <div class="obr-section-title">Data Pasien</div>

            @if ($pasien)
                <div class="obr-fields">

                    <div class="obr-field">
                        <span class="obr-label">No RM</span>
                        <span class="obr-value">{{ $pasien->no_rm }}</span>
                    </div>

                    <div class="obr-field">
                        <span class="obr-label">Tempat, Tgl Lahir</span>
                        <span class="obr-value">
                            {{ $pasien->tempat_lahir }},
                            {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') }}
                        </span>
                    </div>

                    <div class="obr-field">
                        <span class="obr-label">Nama</span>
                        <span class="obr-value">{{ $pasien->nama_pasien }}</span>
                    </div>

                    <div class="obr-field">
                        <span class="obr-label">Umur</span>
                        <span class="obr-value">{{ $pasien->umur }}</span>
                    </div>

                    <div class="obr-field">
                        <span class="obr-label">Jenis Kelamin</span>
                        <span class="obr-value">{{ $pasien->jenis_kelamin }}</span>
                    </div>

                    <div class="obr-field">
                        <span class="obr-label">No Hp</span>
                        <span class="obr-value">{{ $pasien->no_hp }}</span>
                    </div>

                </div>
            @else
                <p style="color:#888;">Data pasien tidak ditemukan.</p>
            @endif
        </div>

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
                            $urlDetail = route('poli.obgyn.kunjungan.detail', [
                                'no_rm'   => $noRmAktif,
                                'tanggal' => $item['tanggal'],
                            ]);
                        @endphp
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <a href="{{ $urlDetail }}" class="obr-link">
                                    {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $urlDetail }}" class="obr-link">
                                    {{ $item['poli'] }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; color:#888;">
                                Belum ada riwayat kunjungan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection