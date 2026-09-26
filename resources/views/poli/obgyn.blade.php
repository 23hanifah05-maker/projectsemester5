@extends('layouts.app')

@section('title', 'Poli Obgyn - Klinik Utama Merah Putih')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    {{-- Pakai CSS yang sama dengan halaman Pendaftaran karena struktur tabelnya sama --}}
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
@endsection

@section('content')

    {{-- =====================================================
         DATA DUMMY PASIEN POLI OBGYN
         (5 pasien perempuan dari Master Data Pendaftaran:
         RM-0002, RM-0004, RM-0006, RM-0008, RM-0010.
         Obgyn hanya untuk pasien perempuan, jadi jenis kelamin
         disesuaikan agar logis)
    ===================================================== --}}
    @php
        $pasien = $pasien ?? [
            (object) [
                'no_rm' => 'RM-0002',
                'nama_pasien' => 'Siti Aminah',
                'nik' => '3515025205850002',
                'tgl_lahir' => '1985-05-12',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Diponegoro No. 25',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0004',
                'nama_pasien' => 'Dewi Lestari',
                'nik' => '3515044507920004',
                'tgl_lahir' => '1992-07-05',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Ahmad Yani No. 15',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0006',
                'nama_pasien' => 'Lina Marlina',
                'nik' => '3515065508800006',
                'tgl_lahir' => '1980-08-15',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Pahlawan No. 32',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0008',
                'nama_pasien' => 'Nur Aisyah',
                'nik' => '3515084203840008',
                'tgl_lahir' => '1984-03-02',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Hasanudin No. 18',
                'status' => 'Sudah Terdaftar',
            ],
            (object) [
                'no_rm' => 'RM-0010',
                'nama_pasien' => 'Sri Wahyuni',
                'nik' => '3515105807750010',
                'tgl_lahir' => '1975-07-18',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Mawar No. 12',
                'status' => 'Sudah Terdaftar',
            ],
        ];
    @endphp

    <div class="panel">

        <div class="panel-head">
            <a href="#" class="master-data-title">Daftar Pasien</a>
        </div>

        <form method="GET" action="{{ route('poli.obgyn') }}" class="filter-row">
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