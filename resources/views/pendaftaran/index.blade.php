@extends('layouts.app')

@section('title', 'Pendaftaran - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-clipboard-list"></i>
@endsection
@section('header-title', 'Pendaftaran')


{{-- =====================================================
     CSS EKSTERNAL
===================================================== --}}
@section('extra-css')

<link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
<link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">

@endsection


@section('content')


{{-- =====================================================
     DATA DUMMY 10 PASIEN
===================================================== --}}

@php

$dataPasien = [

    (object) [
        'no_rm' => 'RM-0001',
        'nama_pasien' => 'Budi Santoso',
        'nik' => '3515010101800001',
        'tgl_lahir' => '1980-01-01',
        'jenis_kelamin' => 'Laki-laki',
        'alamat' => 'Jl. Merdeka No. 10',
        'no_hp' => '081234567890',
        'tempat_lahir' => 'Sidoarjo',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '46 Tahun',
        'kelurahan' => 'Sidokare',
        'agama' => 'Islam',
        'kecamatan' => 'Sidoarjo',
        'pendidikan' => 'SMA',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Wiraswasta',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61215',
        'pembayaran' => 'Umum',
        'poli' => 'Poli Syaraf',
        'dokter' => '',
        'pj_nama' => 'Siti Aminah',
        'pj_hubungan' => 'Istri',
        'pj_jenis_kelamin' => 'Perempuan',
        'pj_no_hp' => '081298765432',
    ],

    (object) [
        'no_rm' => 'RM-0002',
        'nama_pasien' => 'Siti Aminah',
        'nik' => '3515025205850002',
        'tgl_lahir' => '1985-05-12',
        'jenis_kelamin' => 'Perempuan',
        'alamat' => 'Jl. Diponegoro No. 25',
        'no_hp' => '081355667788',
        'tempat_lahir' => 'Surabaya',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '41 Tahun',
        'kelurahan' => 'Lemahputro',
        'agama' => 'Islam',
        'kecamatan' => 'Sidoarjo',
        'pendidikan' => 'S1',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Guru',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61213',
        'pembayaran' => 'BPJS',
        'poli' => 'Poli Obgyn',
        'dokter' => '',
        'pj_nama' => 'Budi Santoso',
        'pj_hubungan' => 'Suami',
        'pj_jenis_kelamin' => 'Laki-laki',
        'pj_no_hp' => '081234567890',
    ],

    (object) [
        'no_rm' => 'RM-0003',
        'nama_pasien' => 'Andi Pratama',
        'nik' => '3515031503900003',
        'tgl_lahir' => '1990-03-15',
        'jenis_kelamin' => 'Laki-laki',
        'alamat' => 'Jl. Kartini No. 8',
        'no_hp' => '082112223333',
        'tempat_lahir' => 'Malang',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '36 Tahun',
        'kelurahan' => 'Pucang',
        'agama' => 'Islam',
        'kecamatan' => 'Sidoarjo',
        'pendidikan' => 'D3',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Karyawan',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61219',
        'pembayaran' => 'Umum',
        'poli' => 'Poli Syaraf',
        'dokter' => '',
        'pj_nama' => 'Dewi Lestari',
        'pj_hubungan' => 'Istri',
        'pj_jenis_kelamin' => 'Perempuan',
        'pj_no_hp' => '082233445566',
    ],

    (object) [
        'no_rm' => 'RM-0004',
        'nama_pasien' => 'Dewi Lestari',
        'nik' => '3515044507920004',
        'tgl_lahir' => '1992-07-05',
        'jenis_kelamin' => 'Perempuan',
        'alamat' => 'Jl. Ahmad Yani No. 15',
        'no_hp' => '082233445566',
        'tempat_lahir' => 'Surabaya',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '34 Tahun',
        'kelurahan' => 'Krian',
        'agama' => 'Islam',
        'kecamatan' => 'Krian',
        'pendidikan' => 'S1',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Pegawai Swasta',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61262',
        'pembayaran' => 'BPJS',
        'poli' => 'Poli Obgyn',
        'dokter' => '',
        'pj_nama' => 'Andi Pratama',
        'pj_hubungan' => 'Suami',
        'pj_jenis_kelamin' => 'Laki-laki',
        'pj_no_hp' => '082112223333',
    ],

    (object) [
        'no_rm' => 'RM-0005',
        'nama_pasien' => 'Rudi Hartono',
        'nik' => '3515051206750005',
        'tgl_lahir' => '1975-06-12',
        'jenis_kelamin' => 'Laki-laki',
        'alamat' => 'Jl. Gajah Mada No. 21',
        'no_hp' => '081377889900',
        'tempat_lahir' => 'Mojokerto',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '51 Tahun',
        'kelurahan' => 'Taman',
        'agama' => 'Islam',
        'kecamatan' => 'Taman',
        'pendidikan' => 'SMA',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Pedagang',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61257',
        'pembayaran' => 'Umum',
        'poli' => 'Poli Syaraf',
        'dokter' => '',
        'pj_nama' => 'Lina Marlina',
        'pj_hubungan' => 'Istri',
        'pj_jenis_kelamin' => 'Perempuan',
        'pj_no_hp' => '081366778899',
    ],

    (object) [
        'no_rm' => 'RM-0006',
        'nama_pasien' => 'Lina Marlina',
        'nik' => '3515065508800006',
        'tgl_lahir' => '1980-08-15',
        'jenis_kelamin' => 'Perempuan',
        'alamat' => 'Jl. Pahlawan No. 32',
        'no_hp' => '081366778899',
        'tempat_lahir' => 'Sidoarjo',
        'suku' => 'Madura',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '46 Tahun',
        'kelurahan' => 'Buduran',
        'agama' => 'Islam',
        'kecamatan' => 'Buduran',
        'pendidikan' => 'SMA',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Ibu Rumah Tangga',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61252',
        'pembayaran' => 'Umum',
        'poli' => 'Poli Obgyn',
        'dokter' => '',
        'pj_nama' => 'Rudi Hartono',
        'pj_hubungan' => 'Suami',
        'pj_jenis_kelamin' => 'Laki-laki',
        'pj_no_hp' => '081377889900',
    ],

    (object) [
        'no_rm' => 'RM-0007',
        'nama_pasien' => 'Fajar Ramadhan',
        'nik' => '3515071801980007',
        'tgl_lahir' => '1998-01-18',
        'jenis_kelamin' => 'Laki-laki',
        'alamat' => 'Jl. Sunan Ampel No. 7',
        'no_hp' => '083811223344',
        'tempat_lahir' => 'Sidoarjo',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '28 Tahun',
        'kelurahan' => 'Waru',
        'agama' => 'Islam',
        'kecamatan' => 'Waru',
        'pendidikan' => 'S1',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Teknisi',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61256',
        'pembayaran' => 'BPJS',
        'poli' => 'Poli Syaraf',
        'dokter' => '',
        'pj_nama' => 'Nur Aisyah',
        'pj_hubungan' => 'Ibu',
        'pj_jenis_kelamin' => 'Perempuan',
        'pj_no_hp' => '083855667788',
    ],

    (object) [
        'no_rm' => 'RM-0008',
        'nama_pasien' => 'Nur Aisyah',
        'nik' => '3515084203840008',
        'tgl_lahir' => '1984-03-02',
        'jenis_kelamin' => 'Perempuan',
        'alamat' => 'Jl. Hasanudin No. 18',
        'no_hp' => '083855667788',
        'tempat_lahir' => 'Gresik',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '42 Tahun',
        'kelurahan' => 'Gedangan',
        'agama' => 'Islam',
        'kecamatan' => 'Gedangan',
        'pendidikan' => 'SMA',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Penjahit',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61254',
        'pembayaran' => 'Umum',
        'poli' => 'Poli Obgyn',
        'dokter' => '',
        'pj_nama' => 'Fajar Ramadhan',
        'pj_hubungan' => 'Anak',
        'pj_jenis_kelamin' => 'Laki-laki',
        'pj_no_hp' => '083811223344',
    ],

    (object) [
        'no_rm' => 'RM-0009',
        'nama_pasien' => 'Agus Setiawan',
        'nik' => '3515092206700009',
        'tgl_lahir' => '1970-06-22',
        'jenis_kelamin' => 'Laki-laki',
        'alamat' => 'Jl. Diponegoro No. 44',
        'no_hp' => '081245678901',
        'tempat_lahir' => 'Surabaya',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '56 Tahun',
        'kelurahan' => 'Porong',
        'agama' => 'Islam',
        'kecamatan' => 'Porong',
        'pendidikan' => 'SMA',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Pensiunan',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61274',
        'pembayaran' => 'BPJS',
        'poli' => 'Poli Syaraf',
        'dokter' => '',
        'pj_nama' => 'Sri Wahyuni',
        'pj_hubungan' => 'Istri',
        'pj_jenis_kelamin' => 'Perempuan',
        'pj_no_hp' => '081298112233',
    ],

    (object) [
        'no_rm' => 'RM-0010',
        'nama_pasien' => 'Sri Wahyuni',
        'nik' => '3515105807750010',
        'tgl_lahir' => '1975-07-18',
        'jenis_kelamin' => 'Perempuan',
        'alamat' => 'Jl. Mawar No. 12',
        'no_hp' => '081298112233',
        'tempat_lahir' => 'Sidoarjo',
        'suku' => 'Jawa',
        'bangsa' => 'Indonesia',
        'bahasa' => 'Indonesia',
        'umur' => '51 Tahun',
        'kelurahan' => 'Candi',
        'agama' => 'Islam',
        'kecamatan' => 'Candi',
        'pendidikan' => 'SMA',
        'kabupaten' => 'Sidoarjo',
        'pekerjaan' => 'Wiraswasta',
        'provinsi' => 'Jawa Timur',
        'kode_pos' => '61271',
        'pembayaran' => 'Umum',
        'poli' => 'Poli Obgyn',
        'dokter' => '',
        'pj_nama' => 'Agus Setiawan',
        'pj_hubungan' => 'Suami',
        'pj_jenis_kelamin' => 'Laki-laki',
        'pj_no_hp' => '081245678901',
    ],

];


/* =====================================================
   FILTER KEYWORD
===================================================== */

$keyword = trim(request('keyword', ''));

if ($keyword !== '') {

    $keywordLower = strtolower($keyword);

    $dataPasien = collect($dataPasien)
        ->filter(function ($pasien) use ($keywordLower) {

            return
                str_contains(
                    strtolower($pasien->nama_pasien ?? ''),
                    $keywordLower
                )
                ||
                str_contains(
                    strtolower($pasien->nik ?? ''),
                    $keywordLower
                )
                ||
                str_contains(
                    strtolower($pasien->no_rm ?? ''),
                    $keywordLower
                )
                ||
                str_contains(
                    strtolower($pasien->no_hp ?? ''),
                    $keywordLower
                );

        })
        ->values();

}


/* =====================================================
   FILTER PERIODE
   Menggunakan tanggal lahir sebagai data tanggal dummy.
   Jika belum ingin menggunakan periode, bagian ini bisa
   dihapus.
===================================================== */

$dari = request('dari');
$sampai = request('sampai');

if ($dari && $sampai) {

    $dataPasien = collect($dataPasien)
        ->filter(function ($pasien) use ($dari, $sampai) {

            return
                $pasien->tgl_lahir >= $dari &&
                $pasien->tgl_lahir <= $sampai;

        })
        ->values();

}

@endphp


{{-- =====================================================
     MASTER DATA
===================================================== --}}

<div class="panel">

    <div class="panel-head">

        <a href="#" class="master-data-title">
            Master Data
        </a>

        <a
            href="{{ route('pendaftaran.create') }}"
            class="btn-input"
        >
            + Input
        </a>

    </div>


    {{-- =================================================
         FILTER
    ================================================= --}}

    <form
        method="GET"
        action="{{ route('pendaftaran.index') }}"
        class="filter-row"
        id="filterForm"
    >

        <div class="filter-item">

            <label>Periode</label>

            <input
                type="date"
                name="dari"
                value="{{ request('dari') }}"
            >

            <span>s.d</span>

            <input
                type="date"
                name="sampai"
                value="{{ request('sampai') }}"
            >

        </div>


        <div class="filter-item filter-keyword">

            <label>Keyword</label>

            <input
                type="text"
                name="keyword"
                id="keywordInput"
                value="{{ request('keyword') }}"
                placeholder="Cari nama, NIK, atau No. RM"
                autocomplete="off"
            >

        </div>

    </form>


    {{-- =================================================
         TABEL
    ================================================= --}}

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

        @forelse ($dataPasien as $index => $item)

            <tr
                class="row-pasien"
                data-pasien='@json($item)'
                title="Klik dua kali untuk melihat detail"
            >

                <td>
                    {{ $index + 1 }}
                </td>

                <td>
                    {{ $item->no_rm }}
                </td>

                <td>
                    {{ $item->nama_pasien }}
                </td>

                <td>
                    {{ $item->nik }}
                </td>

                <td>
                    {{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}
                </td>

                <td>
                    {{ $item->jenis_kelamin }}
                </td>

                <td>
                    {{ $item->alamat }}
                </td>

                <td class="aksi-cell">

                    {{-- DETAIL --}}

                    <button
                        type="button"
                        class="btn-aksi"
                        title="Lihat Detail"
                        onclick="event.stopPropagation(); bukaDetail(this)"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>


                    {{-- TAMBAH KUNJUNGAN --}}

                    <button
                        type="button"
                        class="btn-aksi"
                        title="Tambah Kunjungan"
                        onclick="event.stopPropagation(); bukaKunjungan(this)"
                    >
                        +
                    </button>


                    {{-- EDIT --}}

                    <button
                        type="button"
                        class="btn-aksi"
                        title="Edit Data"
                        onclick="event.stopPropagation(); bukaEdit(this)"
                    >
                        ✎
                    </button>


                    {{-- HAPUS --}}

                    <button
                        type="button"
                        class="btn-aksi"
                        title="Hapus Data"
                        onclick="event.stopPropagation(); hapusPasien(this)"
                    >
                        🗑
                    </button>

                </td>

            </tr>

        @empty

            <tr>

                <td
                    colspan="8"
                    class="td-kosong"
                >
                    Data pasien tidak ditemukan.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>


{{-- =====================================================
     MODAL DETAIL PASIEN
===================================================== --}}

<div
    class="modal-overlay"
    id="pasienModal"
>

    <div class="modal-box">

        <div class="modal-header">

            <h3>Data Pasien</h3>

            <button
                type="button"
                class="modal-close"
                onclick="closePasienModal()"
            >
                &times;
            </button>

        </div>


        <div class="modal-section-title">
            Identitas Pasien
        </div>


        <div class="modal-grid">

            <div class="modal-row">
                <span class="modal-label">No. Rekam Medis</span>
                <span>:</span>
                <span id="m-no-rm"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">No. HP</span>
                <span>:</span>
                <span id="m-hp"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Nama Lengkap</span>
                <span>:</span>
                <span id="m-nama"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Suku</span>
                <span>:</span>
                <span id="m-suku"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">NIK</span>
                <span>:</span>
                <span id="m-nik"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Bangsa</span>
                <span>:</span>
                <span id="m-bangsa"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Tempat/Tgl Lahir</span>
                <span>:</span>
                <span id="m-ttl"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Bahasa</span>
                <span>:</span>
                <span id="m-bahasa"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Umur</span>
                <span>:</span>
                <span id="m-umur"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Alamat</span>
                <span>:</span>
                <span id="m-alamat"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Jenis Kelamin</span>
                <span>:</span>
                <span id="m-jk"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Kelurahan</span>
                <span>:</span>
                <span id="m-kelurahan"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Agama</span>
                <span>:</span>
                <span id="m-agama"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Kecamatan</span>
                <span>:</span>
                <span id="m-kecamatan"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Pendidikan</span>
                <span>:</span>
                <span id="m-pendidikan"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Kota/Kabupaten</span>
                <span>:</span>
                <span id="m-kabupaten"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Pekerjaan</span>
                <span>:</span>
                <span id="m-pekerjaan"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Provinsi</span>
                <span>:</span>
                <span id="m-provinsi"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Kode Pos</span>
                <span>:</span>
                <span id="m-kodepos"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Pembayaran</span>
                <span>:</span>
                <span id="m-pembayaran"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Poli</span>
                <span>:</span>
                <span id="m-poli"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Dokter</span>
                <span>:</span>
                <span id="m-dokter"></span>
            </div>

        </div>


        <div class="modal-section-title">
            Penanggung Jawab
        </div>


        <div class="modal-grid">

            <div class="modal-row">
                <span class="modal-label">Nama</span>
                <span>:</span>
                <span id="m-pj-nama"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Hubungan</span>
                <span>:</span>
                <span id="m-pj-hubungan"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">Jenis Kelamin</span>
                <span>:</span>
                <span id="m-pj-jk"></span>
            </div>

            <div class="modal-row">
                <span class="modal-label">No HP</span>
                <span>:</span>
                <span id="m-pj-hp"></span>
            </div>

        </div>

    </div>

</div>


{{-- =====================================================
     MODAL TAMBAH KUNJUNGAN
===================================================== --}}

<div
    class="custom-modal-overlay"
    id="kunjunganModal"
>

    <div class="custom-modal-box">

        <div class="custom-modal-header">

            <h3>Tambah Kunjungan</h3>

            <button
                type="button"
                class="custom-modal-close"
                onclick="tutupKunjungan()"
            >
                &times;
            </button>

        </div>


        <div class="custom-modal-body">

            <div class="form-group">

                <label>No. Rekam Medis</label>

                <input
                    type="text"
                    id="kunjunganRm"
                    readonly
                >

            </div>


            <div class="form-group">

                <label>Nama Pasien</label>

                <input
                    type="text"
                    id="kunjunganNama"
                    readonly
                >

            </div>


            <div class="form-group">

                <label>Tanggal Kunjungan</label>

                <input
                    type="date"
                    id="kunjunganTanggal"
                >

            </div>


            <div class="form-group">

                <label>Poli</label>

                <select id="kunjunganPoli">

                    <option value="">
                        -- Pilih Poli --
                    </option>

                    <option value="Poli Umum">
                        Poli Umum
                    </option>

                    <option value="Poli Syaraf">
                        Poli Syaraf
                    </option>

                    <option value="Poli Anak">
                        Poli Anak
                    </option>

                    <option value="Poli Gigi">
                        Poli Gigi
                    </option>

                    <option value="Poli Mata">
                        Poli Mata
                    </option>

                </select>

            </div>


            <div class="form-group">

                <label>Keluhan</label>

                <textarea
                    id="kunjunganKeluhan"
                    placeholder="Masukkan keluhan pasien..."
                ></textarea>

            </div>

        </div>


        <div class="custom-modal-footer">

            <button
                type="button"
                class="btn-modal btn-modal-secondary"
                onclick="tutupKunjungan()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-modal btn-modal-primary"
                onclick="simpanKunjungan()"
            >
                Simpan Kunjungan
            </button>

        </div>

    </div>

</div>


{{-- =====================================================
     MODAL EDIT
===================================================== --}}

<div
    class="custom-modal-overlay"
    id="editModal"
>

    <div class="custom-modal-box">

        <div class="custom-modal-header">

            <h3>Edit Data Pasien</h3>

            <button
                type="button"
                class="custom-modal-close"
                onclick="tutupEdit()"
            >
                &times;
            </button>

        </div>


        <div class="custom-modal-body">

            <input
                type="hidden"
                id="editRow"
            >


            <div class="form-group">

                <label>No. Rekam Medis</label>

                <input
                    type="text"
                    id="editRm"
                    class="input-terkunci"
                    readonly
                >

            </div>


            <div class="form-group">

                <label>Nama Pasien</label>

                <input
                    type="text"
                    id="editNama"
                    readonly
                    class="input-locked input-terkunci"
                >

            </div>


            <div class="form-group">

                <label>NIK</label>

                <input
                    type="text"
                    id="editNik"
                    inputmode="numeric"
                    readonly
                    class="input-locked input-terkunci"
                >

            </div>


            <div class="form-group">

                <label>No. HP</label>

                <input
                    type="text"
                    id="editHp"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >

            </div>


            <div class="form-group">

                <label>Alamat</label>

                <textarea id="editAlamat"></textarea>

            </div>

        </div>


        <div class="custom-modal-footer">

            <button
                type="button"
                class="btn-modal btn-modal-secondary"
                onclick="tutupEdit()"
            >
                Batal
            </button>

            <button
                type="button"
                class="btn-modal btn-modal-primary"
                onclick="simpanEdit()"
            >
                Simpan Perubahan
            </button>

        </div>

    </div>

</div>


@endsection


{{-- =====================================================
     JAVASCRIPT
===================================================== --}}

@section('extra-js')

<script>


/* =====================================================
   AMBIL DATA BARIS
===================================================== */

function ambilDataBaris(button)
{
    const row = button.closest('.row-pasien');

    if (!row) {
        return null;
    }

    try {

        return {
            row: row,
            data: JSON.parse(
                row.getAttribute('data-pasien')
            )
        };

    } catch (error) {

        console.error(error);

        return null;
    }
}


/* =====================================================
   FORMAT TANGGAL
===================================================== */

function formatTanggal(tanggal)
{
    if (!tanggal) {
        return '-';
    }

    const d = new Date(tanggal);

    if (isNaN(d.getTime())) {
        return tanggal;
    }

    return d.toLocaleDateString(
        'id-ID',
        {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }
    );
}


/* =====================================================
   ISI DETAIL PASIEN
===================================================== */

function isiDetail(data)
{
    document.getElementById('m-no-rm').textContent =
        data.no_rm ?? '-';

    document.getElementById('m-hp').textContent =
        data.no_hp ?? '-';

    document.getElementById('m-nama').textContent =
        data.nama_pasien ?? '-';

    document.getElementById('m-suku').textContent =
        data.suku ?? '-';

    document.getElementById('m-nik').textContent =
        data.nik ?? '-';

    document.getElementById('m-bangsa').textContent =
        data.bangsa ?? '-';

    document.getElementById('m-ttl').textContent =
        (data.tempat_lahir ?? '-') +
        ' / ' +
        formatTanggal(data.tgl_lahir);

    document.getElementById('m-bahasa').textContent =
        data.bahasa ?? '-';

    document.getElementById('m-umur').textContent =
        data.umur ?? '-';

    document.getElementById('m-alamat').textContent =
        data.alamat ?? '-';

    document.getElementById('m-jk').textContent =
        data.jenis_kelamin ?? '-';

    document.getElementById('m-kelurahan').textContent =
        data.kelurahan ?? '-';

    document.getElementById('m-agama').textContent =
        data.agama ?? '-';

    document.getElementById('m-kecamatan').textContent =
        data.kecamatan ?? '-';

    document.getElementById('m-pendidikan').textContent =
        data.pendidikan ?? '-';

    document.getElementById('m-kabupaten').textContent =
        data.kabupaten ?? '-';

    document.getElementById('m-pekerjaan').textContent =
        data.pekerjaan ?? '-';

    document.getElementById('m-provinsi').textContent =
        data.provinsi ?? '-';

    document.getElementById('m-kodepos').textContent =
        data.kode_pos ?? '-';

    document.getElementById('m-pembayaran').textContent =
        data.pembayaran ?? '-';

    document.getElementById('m-poli').textContent =
        data.poli ?? '-';

    document.getElementById('m-dokter').textContent =
        data.dokter && data.dokter.length > 0
            ? data.dokter
            : '-';

    document.getElementById('m-pj-nama').textContent =
        data.pj_nama ?? '-';

    document.getElementById('m-pj-hubungan').textContent =
        data.pj_hubungan ?? '-';

    document.getElementById('m-pj-jk').textContent =
        data.pj_jenis_kelamin ?? '-';

    document.getElementById('m-pj-hp').textContent =
        data.pj_no_hp ?? '-';
}


/* =====================================================
   DETAIL
===================================================== */

function bukaDetail(button)
{
    const hasil = ambilDataBaris(button);

    if (!hasil) {
        return;
    }

    isiDetail(hasil.data);

    document
        .getElementById('pasienModal')
        .classList
        .add('show');
}


function closePasienModal()
{
    document
        .getElementById('pasienModal')
        .classList
        .remove('show');
}


/* =====================================================
   TAMBAH KUNJUNGAN
===================================================== */

function bukaKunjungan(button)
{
    const hasil = ambilDataBaris(button);

    if (!hasil) {
        return;
    }

    const data = hasil.data;

    document.getElementById('kunjunganRm').value =
        data.no_rm ?? '';

    document.getElementById('kunjunganNama').value =
        data.nama_pasien ?? '';

    const today = new Date();

    const tanggal =
        today.getFullYear() +
        '-' +
        String(today.getMonth() + 1).padStart(2, '0') +
        '-' +
        String(today.getDate()).padStart(2, '0');

    document.getElementById('kunjunganTanggal').value =
        tanggal;

    document.getElementById('kunjunganPoli').value =
        data.poli ?? '';

    document.getElementById('kunjunganKeluhan').value =
        '';

    document
        .getElementById('kunjunganModal')
        .classList
        .add('show');
}


function tutupKunjungan()
{
    document
        .getElementById('kunjunganModal')
        .classList
        .remove('show');
}


function simpanKunjungan()
{
    const rm =
        document.getElementById('kunjunganRm').value;

    const nama =
        document.getElementById('kunjunganNama').value;

    const tanggal =
        document.getElementById('kunjunganTanggal').value;

    const poli =
        document.getElementById('kunjunganPoli').value;

    const keluhan =
        document.getElementById('kunjunganKeluhan').value;

    if (!tanggal) {
        alert('Tanggal kunjungan wajib diisi.');
        return;
    }

    if (!poli) {
        alert('Silakan pilih poli.');
        return;
    }

    alert(
        'Kunjungan berhasil dibuat.\n\n' +
        'No. RM : ' + rm + '\n' +
        'Nama   : ' + nama + '\n' +
        'Tanggal: ' + formatTanggal(tanggal) + '\n' +
        'Poli   : ' + poli + '\n' +
        'Keluhan: ' + (keluhan || '-')
    );

    tutupKunjungan();
}


/* =====================================================
   EDIT
===================================================== */

function bukaEdit(button)
{
    const hasil = ambilDataBaris(button);

    if (!hasil) {
        return;
    }

    const data = hasil.data;
    const row = hasil.row;

    document.getElementById('editRow').value =
        Array.from(
            document.querySelectorAll('.row-pasien')
        ).indexOf(row);

    document.getElementById('editRm').value =
        data.no_rm ?? '';

    document.getElementById('editNama').value =
        data.nama_pasien ?? '';

    document.getElementById('editNik').value =
        data.nik ?? '';

    document.getElementById('editHp').value =
               data.no_hp ?? '';

    document.getElementById('editAlamat').value =
        data.alamat ?? '';

    document
        .getElementById('editModal')
        .classList
        .add('show');
}


function tutupEdit()
{
    document
        .getElementById('editModal')
        .classList
        .remove('show');
}


function simpanEdit()
{
    const index =
        parseInt(
            document.getElementById('editRow').value
        );

    const row =
        document.querySelectorAll('.row-pasien')[index];

    if (!row) {
        return;
    }

    const dataString =
        row.getAttribute('data-pasien');

    let data;

    try {
        data = JSON.parse(dataString);
    } catch (error) {
        console.error(error);
        return;
    }

    data.no_hp =
        document.getElementById('editHp').value;

    data.alamat =
        document.getElementById('editAlamat').value;

    row.setAttribute(
        'data-pasien',
        JSON.stringify(data)
    );

    const cells = row.querySelectorAll('td');

    if (cells[6]) {
        cells[6].textContent =
            data.alamat ?? '';
    }

    alert('Data pasien berhasil diperbarui.');

    tutupEdit();
}


/* =====================================================
   HAPUS PASIEN
===================================================== */

function hapusPasien(button)
{
    const hasil = ambilDataBaris(button);

    if (!hasil) {
        return;
    }

    const data = hasil.data;
    const row = hasil.row;

    const konfirmasi = confirm(
        'Apakah Anda yakin ingin menghapus pasien "' +
        (data.nama_pasien ?? '') +
        '"?'
    );

    if (!konfirmasi) {
        return;
    }

    row.remove();

    perbaruiNomor();

    cekDataKosong();

    alert('Data pasien berhasil dihapus.');
}


/* =====================================================
   UPDATE NOMOR
===================================================== */

function perbaruiNomor()
{
    const rows =
        document.querySelectorAll(
            '.row-pasien'
        );

    let nomor = 1;

    rows.forEach(function (row) {

        if (row.style.display === 'none') {
            return;
        }

        const cell =
            row.querySelector('td:first-child');

        if (cell) {
            cell.textContent = nomor++;
        }

    });
}


/* =====================================================
   CEK DATA KOSONG
===================================================== */

function cekDataKosong()
{
    const tbody =
        document.querySelector(
            '.table-pendaftaran tbody'
        );

    if (!tbody) {
        return;
    }

    const rows =
        tbody.querySelectorAll('.row-pasien');

    const rowsTampil =
        Array.from(rows).filter(function (row) {
            return row.style.display !== 'none';
        });

    const pesan =
        document.getElementById(
            'keywordEmptyRow'
        );

    if (rowsTampil.length === 0) {

        if (!pesan) {

            const rowKosong =
                document.createElement('tr');

            rowKosong.id =
                'keywordEmptyRow';

            rowKosong.innerHTML = `
                <td
                    colspan="8"
                    class="td-kosong"
                >
                    Data pasien tidak ditemukan.
                </td>
            `;

            tbody.appendChild(rowKosong);
        }

    } else {

        if (pesan) {
            pesan.remove();
        }
    }
}


/* =====================================================
   DOUBLE CLICK DETAIL
===================================================== */

document.addEventListener(
    'dblclick',
    function (event) {

        const row =
            event.target.closest(
                '.row-pasien'
            );

        if (!row) {
            return;
        }

        const tombol =
            row.querySelector(
                '.btn-aksi'
            );

        if (tombol) {
            bukaDetail(tombol);
        }

    }
);


/* =====================================================
   KLIK DI LUAR MODAL
===================================================== */

document.addEventListener(
    'click',
    function (event) {

        const pasienModal =
            document.getElementById(
                'pasienModal'
            );

        const kunjunganModal =
            document.getElementById(
                'kunjunganModal'
            );

        const editModal =
            document.getElementById(
                'editModal'
            );


        if (
            event.target === pasienModal
        ) {
            closePasienModal();
        }


        if (
            event.target === kunjunganModal
        ) {
            tutupKunjungan();
        }


        if (
            event.target === editModal
        ) {
            tutupEdit();
        }

    }
);


/* =====================================================
   ESC UNTUK TUTUP MODAL
===================================================== */

document.addEventListener(
    'keydown',
    function (event) {

        if (event.key !== 'Escape') {
            return;
        }

        closePasienModal();
        tutupKunjungan();
        tutupEdit();

    }
);


/* =====================================================
   SEARCH KEYWORD
===================================================== */

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const keywordInput =
            document.getElementById('keywordInput');

        if (!keywordInput) {
            return;
        }

        let keywordSebelumnya = '';

        keywordInput.addEventListener(
            'input',
            function () {

                const keyword =
                    this.value
                        .trim()
                        .toLowerCase();

                const rows =
                    document.querySelectorAll('.row-pasien');

                let nomor = 1;
                let ditemukan = 0;

                /*
                 * =================================================
                 * JIKA KEYWORD DIHAPUS
                 * =================================================
                 */

                if (keyword === '') {

                    rows.forEach(function (row) {

                        row.style.display = '';

                        const nomorCell =
                            row.querySelector('td:first-child');

                        if (nomorCell) {
                            nomorCell.textContent = nomor++;
                        }

                    });

                    const pesanLama =
                        document.getElementById('keywordEmptyRow');

                    if (pesanLama) {
                        pesanLama.remove();
                    }

                    keywordSebelumnya = '';

                    return;
                }


                /*
                 * =================================================
                 * CEK DATA PASIEN
                 * =================================================
                 */

                const hasilPasien = [];

                rows.forEach(function (row) {

                    const dataString =
                        row.getAttribute('data-pasien');

                    if (!dataString) {
                        return;
                    }

                    let data;

                    try {

                        data = JSON.parse(dataString);

                    } catch (error) {

                        console.error(error);
                        return;

                    }


                    const nama =
                        String(
                            data.nama_pasien || ''
                        ).toLowerCase();

                    const nik =
                        String(
                            data.nik || ''
                        ).toLowerCase();

                    const noRm =
                        String(
                            data.no_rm || ''
                        ).toLowerCase();

                    const noHp =
                        String(
                            data.no_hp || ''
                        ).toLowerCase();


                    const cocok =
                        nama.includes(keyword) ||
                        nik.includes(keyword) ||
                        noRm.includes(keyword) ||
                        noHp.includes(keyword);


                    if (cocok) {

                        hasilPasien.push({
                            row: row,
                            data: data,
                            nama: nama
                        });

                    }

                });


                /*
                 * =================================================
                 * KETIKA USER MENGHAPUS KARAKTER
                 *
                 * Contoh:
                 * siti → sit → si → s
                 *
                 * Jika hasil tersebut hanya pasien yang sama
                 * dengan pencarian sebelumnya, tampilkan kembali
                 * semua pasien.
                 * =================================================
                 */

                const sedangMenghapus =
                    keyword.length < keywordSebelumnya.length;


                if (
                    sedangMenghapus &&
                    hasilPasien.length === 1 &&
                    keywordSebelumnya !== ''
                ) {

                    const pasienHasil =
                        hasilPasien[0];

                    const namaPasien =
                        pasienHasil.nama;


                    /*
                     * Cek apakah keyword baru hanya merupakan
                     * awalan dari pasien yang sebelumnya dicari.
                     */

                    const hanyaPasienSebelumnya =
                        namaPasien.startsWith(keyword);


                    /*
                     * Kalau iya, berarti user sedang menghapus
                     * nama pasien yang tadi dicari.
                     *
                     * Maka jangan tetap menampilkan pasien tersebut.
                     * Kembalikan semua data pasien.
                     */

                    if (hanyaPasienSebelumnya) {

                        rows.forEach(function (row) {

                            row.style.display = '';

                            const nomorCell =
                                row.querySelector(
                                    'td:first-child'
                                );

                            if (nomorCell) {
                                nomorCell.textContent =
                                    nomor++;
                            }

                        });


                        const pesanLama =
                            document.getElementById(
                                'keywordEmptyRow'
                            );

                        if (pesanLama) {
                            pesanLama.remove();
                        }


                        keywordSebelumnya = keyword;

                        return;
                    }

                }


                /*
                 * =================================================
                 * TAMPILKAN HASIL PENCARIAN
                 * =================================================
                 */

                rows.forEach(function (row) {

                    const ditemukanData =
                        hasilPasien.some(function (item) {

                            return item.row === row;

                        });


                    if (ditemukanData) {

                        row.style.display = '';

                        const nomorCell =
                            row.querySelector(
                                'td:first-child'
                            );

                        if (nomorCell) {
                            nomorCell.textContent =
                                nomor++;
                        }

                        ditemukan++;

                    } else {

                        row.style.display = 'none';

                    }

                });


                /*
                 * =================================================
                 * PESAN DATA TIDAK DITEMUKAN
                 * =================================================
                 */

                const tbody =
                    document.querySelector(
                        '.table-pendaftaran tbody'
                    );

                if (!tbody) {
                    return;
                }


                const pesanLama =
                    document.getElementById(
                        'keywordEmptyRow'
                    );


                if (pesanLama) {
                    pesanLama.remove();
                }


                if (ditemukan === 0) {

                    const rowKosong =
                        document.createElement('tr');

                    rowKosong.id =
                        'keywordEmptyRow';


                    rowKosong.innerHTML = `
                        <td
                            colspan="8"
                            class="td-kosong"
                        >
                            Data pasien tidak ditemukan.
                        </td>
                    `;


                    tbody.appendChild(rowKosong);

                }


                keywordSebelumnya = keyword;

            }
        );


        /*
         * =================================================
         * JALANKAN SEARCH SAAT HALAMAN DIBUKA
         * =================================================
         */

        keywordInput.dispatchEvent(
            new Event('input')
        );

    }
);

</script>

@endsection