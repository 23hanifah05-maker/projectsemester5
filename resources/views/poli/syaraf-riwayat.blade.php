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

    <div class="kj-card">

        {{-- DATA PASIEN --}}
        <div class="kj-datapasien-box">
            <div class="kj-section-title">Data Pasien</div>

            @if ($pasien)
                <div class="kj-fields">

                    <div class="kj-field">
                        <span class="kj-label">No RM</span>
                        <span class="kj-value">
                            {{ $pasien->no_rm ?? '-' }}
                        </span>
                    </div>

                    <div class="kj-field">
                        <span class="kj-label">Tempat, Tgl Lahir</span>
                        <span class="kj-value">
                            {{ $pasien->tempat_lahir ?? '-' }},
                            {{ $pasien->tgl_lahir
                                ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y')
                                : '-' }}
                        </span>
                    </div>

                    <div class="kj-field">
                        <span class="kj-label">Nama</span>
                        <span class="kj-value">
                            {{ $pasien->nama_pasien ?? '-' }}
                        </span>
                    </div>

                    <div class="kj-field">
                        <span class="kj-label">Umur</span>
                        <span class="kj-value">
                            {{ $pasien->tgl_lahir
                                ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age . ' Tahun'
                                : '-' }}
                        </span>
                    </div>

                    <div class="kj-field">
                        <span class="kj-label">Jenis Kelamin</span>
                        <span class="kj-value">
                            {{ $jenisKelamin ?? ($pasien->jenis_kelamin ?? '-') }}
                        </span>
                    </div>

                    <div class="kj-field">
                        <span class="kj-label">No HP</span>
                        <span class="kj-value">
                            {{ $pasien->no_hp ?? '-' }}
                        </span>
                    </div>

                </div>
            @else
                <p class="kj-kosong">Data pasien tidak ditemukan.</p>
            @endif
        </div>

        {{-- RIWAYAT KUNJUNGAN --}}
        <div class="kj-riwayat-box">
            <div class="kj-riwayat-title">Riwayat Kunjungan</div>

            <table class="kj-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl. Kunjungan</th>
                        <th>Riwayat Kunjungan</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($riwayat as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>

                            {{-- TANGGAL KUNJUNGAN --}}
                            <td>
                                @if ($item->tgl_registrasi)
                                    <a
                                        href="{{ route('poli.syaraf.kunjungan.detail', [
                                            'no_rm' => $pasien->no_rm,
                                            'tanggal' => \Carbon\Carbon::parse($item->tgl_registrasi)->format('Y-m-d')
                                        ]) }}"
                                        class="kj-link"
                                    >
                                        {{ \Carbon\Carbon::parse($item->tgl_registrasi)->format('d-m-Y') }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>

                            {{-- NAMA POLI --}}
                            <td>
                                @if ($item->tgl_registrasi)
                                    <a
                                        href="{{ route('poli.syaraf.kunjungan.detail', [
                                            'no_rm' => $pasien->no_rm,
                                            'tanggal' => \Carbon\Carbon::parse($item->tgl_registrasi)->format('Y-m-d')
                                        ]) }}"
                                        class="kj-link"
                                    >
                                        {{ $item->poli->nama_poli ?? 'Poli tidak diketahui' }}
                                    </a>
                                @else
                                    {{ $item->poli->nama_poli ?? 'Poli tidak diketahui' }}
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="kj-kosong">
                                Belum ada riwayat kunjungan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

@endsection
