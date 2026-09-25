@extends('layouts.app')

@section('title', 'Pendaftaran - Klinik Utama Merah Putih')
@section('header-icon', '📋')
@section('header-title', 'Pendaftaran')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
@endsection

@section('content')

    <div class="panel">

        <div class="panel-head">
            <a href="#" class="master-data-title">Master Data</a>
            <a href="{{ route('pendaftaran.create') }}" class="btn-input">+ Input</a>
        </div>

        <form method="GET" action="{{ route('pendaftaran.index') }}" class="filter-row">
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
                        <td class="aksi-cell">
                            <a href="{{ route('pendaftaran.show', $item->id) }}" class="btn-aksi" title="Lihat">✔</a>
                            <a href="{{ route('pendaftaran.tambah', $item->id) }}" class="btn-aksi" title="Tambah Kunjungan">+</a>
                            <a href="{{ route('pendaftaran.edit', $item->id) }}" class="btn-aksi" title="Edit">✎</a>
                            <form action="{{ route('pendaftaran.destroy', $item->id) }}" method="POST" class="form-delete"
                                  onsubmit="return confirm('Hapus data pasien ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-aksi" title="Hapus">🗑</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    {{-- Baris kosong sebagai placeholder, sama seperti desain, selama belum ada data --}}
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
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