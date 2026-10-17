@extends('layouts.app')

@section('title', 'Kunjungan Poli Obgyn - Klinik Utama Merah Putih')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/obgyn-riwayat.css') }}">
@endsection

@section('content')

    @php
        $noRmAktif = $pasien->no_rm;

        $umurPasien = $umur ?? (
            $pasien->tgl_lahir
                ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age . ' tahun'
                : '-'
        );

        $jenisKelaminPasien = $jenisKelamin
            ?? match ((string) $pasien->jenis_kelamin) {
                '0' => 'Tidak diketahui',
                '1' => 'Laki-laki',
                '2' => 'Perempuan',
                '3' => 'Tidak dapat ditentukan',
                '4' => 'Tidak dikonfirmasi',
                default => '-',
            };
    @endphp

    <div class="obr-card">

        {{-- DATA PASIEN --}}
        <div class="obr-datapasien-box">
            <div class="obr-section-title">Data Pasien</div>

            <div class="obr-fields">

                <div class="obr-field">
                    <span class="obr-label">No RM</span>
                    <span class="obr-value">
                        {{ $pasien->no_rm }}
                    </span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Tempat, Tgl Lahir</span>
                    <span class="obr-value">
                        {{ $pasien->tempat_lahir ?: '-' }},
                        {{ $pasien->tgl_lahir
                            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y')
                            : '-' }}
                    </span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Nama</span>
                    <span class="obr-value">
                        {{ $pasien->nama_pasien }}
                    </span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Umur</span>
                    <span class="obr-value">
                        {{ $umurPasien }}
                    </span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">Jenis Kelamin</span>
                    <span class="obr-value">
                        {{ $jenisKelaminPasien }}
                    </span>
                </div>

                <div class="obr-field">
                    <span class="obr-label">No Hp</span>
                    <span class="obr-value">
                        {{ $pasien->no_hp ?: '-' }}
                    </span>
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
                            $tanggalKunjungan = $item->tgl_registrasi
                                ? \Carbon\Carbon::parse($item->tgl_registrasi)
                                : null;

                            $tanggalUrl = $tanggalKunjungan
                                ? $tanggalKunjungan->format('Y-m-d')
                                : null;

                            $slugPoli = optional($item->poli)->slug;
                            $namaPoli = optional($item->poli)->nama_poli
                                ?? 'Poli tidak diketahui';

                            $urlDetail = null;

                            if ($tanggalUrl && $slugPoli === 'obgyn') {
                                $urlDetail = route(
                                    'poli.obgyn.kunjungan.detail',
                                    [
                                        'no_rm' => $noRmAktif,
                                        'tanggal' => $tanggalUrl,
                                    ]
                                );
                            } elseif ($tanggalUrl && $slugPoli === 'saraf') {
                                $urlDetail = route(
                                    'poli.syaraf.kunjungan.detail',
                                    [
                                        'no_rm' => $noRmAktif,
                                        'tanggal' => $tanggalUrl,
                                    ]
                                );
                            }
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>

                            <td>
                                @if ($urlDetail)
                                    <a href="{{ $urlDetail }}" class="obr-link">
                                        {{ $tanggalKunjungan->format('d-m-Y') }}
                                    </a>
                                @else
                                    {{ $tanggalKunjungan
                                        ? $tanggalKunjungan->format('d-m-Y')
                                        : '-' }}
                                @endif
                            </td>

                            <td>
                                @if ($urlDetail)
                                    <a href="{{ $urlDetail }}" class="obr-link">
                                        {{ $namaPoli }}
                                    </a>
                                @else
                                    {{ $namaPoli }}
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="3" class="obr-kosong">
                                Belum ada riwayat kunjungan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
