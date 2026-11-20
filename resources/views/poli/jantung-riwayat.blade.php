@extends('layouts.app')

@section('title', 'Riwayat Kunjungan - Poli Jantung')
@section('header-icon')
    <i class="fa-solid fa-stethoscope"></i>
@endsection
@section('header-title', 'Poli Jantung')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/jantung-riwayat.css') }}?v={{ @filemtime(public_path('css/jantung-riwayat.css')) }}">
@endsection

@section('content')

    @php
        // Link ke detail kunjungan sesuai poli. Tanpa id kunjungan / route, tampil teks biasa.
        $urlKunjungan = function ($slug, $noRm, $idPendaftaran) {
            if (!$idPendaftaran) {
                return null;
            }

            $namaRoute = match ($slug) {
                'saraf', 'syaraf' => 'poli.syaraf.kunjungan.detail',
                'obgyn'           => 'poli.obgyn.kunjungan.detail',
                'jantung'         => 'poli.jantung.kunjungan.detail',
                default           => null,
            };

            return ($namaRoute && \Illuminate\Support\Facades\Route::has($namaRoute))
                ? route($namaRoute, ['no_rm' => $noRm, 'id_pendaftaran' => $idPendaftaran])
                : null;
        };

        $riwayat = $riwayat ?? collect();
        $umur = !empty($pasien->tgl_lahir) ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age . ' tahun' : '-';
        $ttl = ($pasien->tempat_lahir ?? '-') . ', ' .
               (!empty($pasien->tgl_lahir) ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') : '-');
    @endphp

    <div class="jr-card">

        {{-- DATA PASIEN --}}
        <div class="jr-box">
            <div class="jr-box-head">Data Pasien</div>

            <div class="jr-box-body">
                @if ($pasien)
                    <div class="jr-fields">
                        <div class="jr-field"><span class="jr-label">No RM</span><span class="jr-value">{{ $pasien->no_rm ?? '-' }}</span></div>
                        <div class="jr-field"><span class="jr-label">Tempat, Tgl Lahir</span><span class="jr-value">{{ $ttl }}</span></div>
                        <div class="jr-field"><span class="jr-label">Nama</span><span class="jr-value">{{ $pasien->nama_pasien ?? '-' }}</span></div>
                        <div class="jr-field"><span class="jr-label">Umur</span><span class="jr-value">{{ $umur }}</span></div>
                        <div class="jr-field"><span class="jr-label">Jenis Kelamin</span><span class="jr-value">{{ $jenisKelamin ?? ($pasien->jenis_kelamin ?? '-') }}</span></div>
                        <div class="jr-field"><span class="jr-label">No Hp</span><span class="jr-value">{{ $pasien->no_hp ?? '-' }}</span></div>
                    </div>
                @else
                    <p class="jr-kosong">Data pasien tidak ditemukan.</p>
                @endif
            </div>
        </div>

        {{-- RIWAYAT KUNJUNGAN --}}
        <div class="jr-box">
            <div class="jr-box-head tanpa-garis">Riwayat Kunjungan</div>

            <div class="jr-table-wrap">
                <table class="jr-table">
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
                                $slugPoli = $item->poli->slug ?? '';
                                $tanggal = $item->tgl_registrasi
                                    ? \Carbon\Carbon::parse($item->tgl_registrasi)->format('d-m-Y')
                                    : '-';
                                $namaPoli = $item->poli->nama_poli ?? 'Poli tidak diketahui';
                                $url = $urlKunjungan($slugPoli, $pasien->no_rm, $item->id_pendaftaran ?? null);
                            @endphp

                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if ($url)
                                        <a href="{{ $url }}" class="jr-link">{{ $tanggal }}</a>
                                    @else
                                        {{ $tanggal }}
                                    @endif
                                </td>
                                <td>
                                    @if ($url)
                                        <a href="{{ $url }}" class="jr-link">{{ $namaPoli }}</a>
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

@endsection