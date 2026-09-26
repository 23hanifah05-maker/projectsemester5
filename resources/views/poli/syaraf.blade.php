@extends('layouts.app')

@section('title', 'Poli Syaraf - Klinik Utama Merah Putih')
@section('header-icon', '🧠')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    {{-- Pakai CSS yang sama dengan halaman Pendaftaran karena struktur tabelnya sama --}}
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
@endsection

@section('content')

    {{-- =====================================================
         DATA DUMMY PASIEN POLI SYARAF
         (5 pasien laki-laki dari Master Data Pendaftaran:
         RM-0001, RM-0003, RM-0005, RM-0007, RM-0009.
         Poli syaraf tidak terikat jenis kelamin, jadi dipilih
         pasien laki-laki agar sisanya pas untuk Poli Obgyn)
    ===================================================== --}}
    @php
        $pasien = $pasien ?? [
            (object) [
                'no_rm' => 'RM-0001',
                'nama_pasien' => 'Budi Santoso',
                'nik' => '3515010101800001',
                'tgl_lahir' => '1980-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Merdeka No. 10',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0003',
                'nama_pasien' => 'Andi Pratama',
                'nik' => '3515031503900003',
                'tgl_lahir' => '1990-03-15',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Kartini No. 8',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0005',
                'nama_pasien' => 'Rudi Hartono',
                'nik' => '3515051206750005',
                'tgl_lahir' => '1975-06-12',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Gajah Mada No. 21',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0007',
                'nama_pasien' => 'Fajar Ramadhan',
                'nik' => '3515071801980007',
                'tgl_lahir' => '1998-01-18',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Sunan Ampel No. 7',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0009',
                'nama_pasien' => 'Agus Setiawan',
                'nik' => '3515092206700009',
                'tgl_lahir' => '1970-06-22',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Diponegoro No. 44',
                'status' => 'Sudah Terdaftar',
            ],
        ];
    @endphp

    <div class="panel">

        <div class="panel-head">
            <a href="#" class="master-data-title">Daftar Pasien</a>
        </div>

        <form method="GET" action="{{ route('poli.syaraf') }}" class="filter-row">
            <div class="filter-item">
                <label>Periode</label>
                <input type="date" name="dari" value="{{ request('dari') }}">
                <span>s.d</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}">
            </div>

            <div class="filter-item filter-keyword">
                <label>Keyword</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama, NIK, atau No. RM">
            </div>
        </form>

        <table class="table-pendaftaran">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>NIK</th>
                    <th>Tgl. Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse (($pasien ?? []) as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->no_rm }}</td>
                        <td>{{ $item->nama_pasien }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->status }}</td>
                        <td class="aksi-cell">
                            <a href="#" class="btn-aksi" title="Lihat">✔</a>
                            <a href="#" class="btn-aksi" title="Tambah">+</a>
                            <a href="#" class="btn-aksi" title="Edit">✎</a>
                            <form action="#" method="POST" class="form-delete"
                                  onsubmit="return confirm('Hapus data pasien ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-aksi" title="Hapus">🗑</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- Baris kosong sebagai placeholder, selama belum ada data --}}
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="aksi-cell">
                                <span class="btn-aksi">✔</span>
                                <span class="btn-aksi">+</span>
                                <span class="btn-aksi">✎</span>
                                <span class="btn-aksi">🗑</span>
                            </td>
                        </tr>
                    @endfor
                @endforelse
            </tbody>
        </table>

    </div>

@endsection