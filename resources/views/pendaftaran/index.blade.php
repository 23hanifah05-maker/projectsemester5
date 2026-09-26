@extends('layouts.app')

@section('title', 'Pendaftaran - Klinik Utama Merah Putih')
@section('header-icon', '📋')
@section('header-title', 'Pendaftaran')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
@endsection


@section('content')

{{-- =========================================================
     DATA DUMMY
========================================================= --}}

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
            'pj_nama' => 'Dewi Lestari',
            'pj_hubungan' => 'Istri',
            'pj_jenis_kelamin' => 'Perempuan',
            'pj_no_hp' => '082233445566',
        ],

    ];

@endphp


{{-- =========================================================
     MASTER DATA
========================================================= --}}

<div class="panel">

    {{-- HEADER --}}
    <div class="panel-head">

        <a href="#" class="master-data-title">
            Master Data
        </a>

        <a href="{{ route('pendaftaran.create') }}" class="btn-input">
            + Input
        </a>

    </div>


    {{-- =====================================================
         FILTER
    ====================================================== --}}

    <form
        method="GET"
        action="{{ route('pendaftaran.index') }}"
        class="filter-row"
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
                value="{{ request('keyword') }}"
                placeholder="Cari nama, NIK, atau No. RM"
            >

        </div>

    </form>


    {{-- =====================================================
         TABEL MASTER DATA
    ====================================================== --}}

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

            @foreach ($dataPasien as $index => $item)

                <tr
                    class="row-pasien"
                    data-pasien='@json($item)'
                    title="Klik dua kali untuk melihat detail pasien"
                >

                    {{-- NO --}}
                    <td>
                        {{ $index + 1 }}
                    </td>


                    {{-- NO RM --}}
                    <td>
                        {{ $item->no_rm }}
                    </td>


                    {{-- NAMA --}}
                    <td>
                        {{ $item->nama_pasien }}
                    </td>


                    {{-- NIK --}}
                    <td>
                        {{ $item->nik }}
                    </td>


                    {{-- TANGGAL LAHIR --}}
                    <td>

                        {{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}

                    </td>


                    {{-- JENIS KELAMIN --}}
                    <td>
                        {{ $item->jenis_kelamin }}
                    </td>


                    {{-- ALAMAT --}}
                    <td>
                        {{ $item->alamat }}
                    </td>


                    {{-- AKSI --}}
                    <td class="aksi-cell">

                        {{-- DETAIL --}}
                        <button
                            type="button"
                            class="btn-aksi btn-detail"
                            title="Lihat Detail"
                            onclick="event.stopPropagation(); bukaDetail(this)"
                        >
                            ✔
                        </button>


                        {{-- TAMBAH KUNJUNGAN --}}
                        <a
                            href="{{ route('pendaftaran.tambah', $index) }}"
                            class="btn-aksi"
                            title="Tambah Kunjungan"
                            onclick="event.stopPropagation()"
                        >
                            +
                        </a>


                        {{-- EDIT --}}
                        <a
                            href="{{ route('pendaftaran.edit', $index) }}"
                            class="btn-aksi"
                            title="Edit"
                            onclick="event.stopPropagation()"
                        >
                            ✎
                        </a>


                        {{-- HAPUS --}}
                        <button
                            type="button"
                            class="btn-aksi"
                            title="Hapus"
                            onclick="event.stopPropagation(); hapusDummy('{{ $item->nama_pasien }}')"
                        >
                            🗑
                        </button>

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>



{{-- =========================================================
     POPUP DATA PASIEN
========================================================= --}}

<div
    class="modal-overlay"
    id="pasienModal"
>

    <div class="modal-box">


        {{-- HEADER MODAL --}}
        <div class="modal-header">

            <h3>
                Data Pasien
            </h3>

            <button
                type="button"
                class="modal-close"
                onclick="closePasienModal()"
            >
                &times;
            </button>

        </div>



        {{-- =================================================
             IDENTITAS PASIEN
        ================================================== --}}

        <div class="modal-section-title">
            Identitas Pasien
        </div>


        <div class="modal-grid">


            {{-- NO RM --}}
            <div class="modal-row">
                <span class="modal-label">
                    No. Rekam Medis
                </span>

                <span>:</span>

                <span id="m-no-rm"></span>
            </div>


            {{-- HP --}}
            <div class="modal-row">
                <span class="modal-label">
                    No. HP
                </span>

                <span>:</span>

                <span id="m-hp"></span>
            </div>


            {{-- NAMA --}}
            <div class="modal-row">
                <span class="modal-label">
                    Nama Lengkap
                </span>

                <span>:</span>

                <span id="m-nama"></span>
            </div>


            {{-- SUKU --}}
            <div class="modal-row">
                <span class="modal-label">
                    Suku
                </span>

                <span>:</span>

                <span id="m-suku"></span>
            </div>


            {{-- NIK --}}
            <div class="modal-row">
                <span class="modal-label">
                    NIK
                </span>

                <span>:</span>

                <span id="m-nik"></span>
            </div>


            {{-- BANGSA --}}
            <div class="modal-row">
                <span class="modal-label">
                    Bangsa
                </span>

                <span>:</span>

                <span id="m-bangsa"></span>
            </div>


            {{-- TTL --}}
            <div class="modal-row">
                <span class="modal-label">
                    Tempat/Tgl Lahir
                </span>

                <span>:</span>

                <span id="m-ttl"></span>
            </div>


            {{-- BAHASA --}}
            <div class="modal-row">
                <span class="modal-label">
                    Bahasa
                </span>

                <span>:</span>

                <span id="m-bahasa"></span>
            </div>


            {{-- UMUR --}}
            <div class="modal-row">
                <span class="modal-label">
                    Umur
                </span>

                <span>:</span>

                <span id="m-umur"></span>
            </div>


            {{-- ALAMAT --}}
            <div class="modal-row">
                <span class="modal-label">
                    Alamat
                </span>

                <span>:</span>

                <span id="m-alamat"></span>
            </div>


            {{-- JENIS KELAMIN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Jenis Kelamin
                </span>

                <span>:</span>

                <span id="m-jk"></span>
            </div>


            {{-- KELURAHAN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Kelurahan
                </span>

                <span>:</span>

                <span id="m-kelurahan"></span>
            </div>


            {{-- AGAMA --}}
            <div class="modal-row">
                <span class="modal-label">
                    Agama
                </span>

                <span>:</span>

                <span id="m-agama"></span>
            </div>


            {{-- KECAMATAN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Kecamatan
                </span>

                <span>:</span>

                <span id="m-kecamatan"></span>
            </div>


            {{-- PENDIDIKAN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Pendidikan
                </span>

                <span>:</span>

                <span id="m-pendidikan"></span>
            </div>


            {{-- KABUPATEN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Kota/Kabupaten
                </span>

                <span>:</span>

                <span id="m-kabupaten"></span>
            </div>


            {{-- PEKERJAAN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Pekerjaan
                </span>

                <span>:</span>

                <span id="m-pekerjaan"></span>
            </div>


            {{-- PROVINSI --}}
            <div class="modal-row">
                <span class="modal-label">
                    Provinsi
                </span>

                <span>:</span>

                <span id="m-provinsi"></span>
            </div>


            {{-- KODE POS --}}
            <div class="modal-row">
                <span class="modal-label">
                    Kode Pos
                </span>

                <span>:</span>

                <span id="m-kodepos"></span>
            </div>


            {{-- PEMBAYARAN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Pembayaran
                </span>

                <span>:</span>

                <span id="m-pembayaran"></span>
            </div>

        </div>



        {{-- =================================================
             PENANGGUNG JAWAB
        ================================================== --}}

        <div class="modal-section-title">
            Penanggung Jawab
        </div>


        <div class="modal-grid">


            {{-- NAMA PJ --}}
            <div class="modal-row">
                <span class="modal-label">
                    Nama
                </span>

                <span>:</span>

                <span id="m-pj-nama"></span>
            </div>


            {{-- HUBUNGAN --}}
            <div class="modal-row">
                <span class="modal-label">
                    Hubungan
                </span>

                <span>:</span>

                <span id="m-pj-hubungan"></span>
            </div>


            {{-- JENIS KELAMIN PJ --}}
            <div class="modal-row">
                <span class="modal-label">
                    Jenis Kelamin
                </span>

                <span>:</span>

                <span id="m-pj-jk"></span>
            </div>


            {{-- HP PJ --}}
            <div class="modal-row">
                <span class="modal-label">
                    No HP
                </span>

                <span>:</span>

                <span id="m-pj-hp"></span>
            </div>

        </div>

    </div>

</div>

@endsection



{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

@section('extra-js')

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* ======================================================
       DOUBLE CLICK BARIS
    ====================================================== */

    document.querySelectorAll('.row-pasien').forEach(function (row) {

        row.addEventListener('dblclick', function () {

            try {

                const data = JSON.parse(
                    this.getAttribute('data-pasien')
                );

                openPasienModal(data);

            } catch (error) {

                console.error(
                    'Data pasien tidak dapat dibaca:',
                    error
                );

            }

        });

    });



    /* ======================================================
       KLIK AREA LUAR MODAL
    ====================================================== */

    const modal = document.getElementById('pasienModal');

    if (modal) {

        modal.addEventListener('click', function (event) {

            if (event.target === modal) {

                closePasienModal();

            }

        });

    }



    /* ======================================================
       ESC UNTUK MENUTUP MODAL
    ====================================================== */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closePasienModal();

        }

    });

});



/* ==========================================================
   BUKA DETAIL DARI TOMBOL
========================================================== */

function bukaDetail(button) {

    const row = button.closest('.row-pasien');

    if (!row) {
        return;
    }

    try {

        const data = JSON.parse(
            row.getAttribute('data-pasien')
        );

        openPasienModal(data);

    } catch (error) {

        console.error(
            'Data pasien tidak dapat dibaca:',
            error
        );

    }

}



/* ==========================================================
   BUKA MODAL
========================================================== */

function openPasienModal(data) {


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



    /* ======================================================
       PENANGGUNG JAWAB
    ====================================================== */

    document.getElementById('m-pj-nama').textContent =
        data.pj_nama ?? '-';


    document.getElementById('m-pj-hubungan').textContent =
        data.pj_hubungan ?? '-';


    document.getElementById('m-pj-jk').textContent =
        data.pj_jenis_kelamin ?? '-';


    document.getElementById('m-pj-hp').textContent =
        data.pj_no_hp ?? '-';



    /* ======================================================
       TAMPILKAN MODAL
    ====================================================== */

    const modal = document.getElementById('pasienModal');

    if (modal) {

        modal.classList.add('show');

    }

}



/* ==========================================================
   TUTUP MODAL
========================================================== */

function closePasienModal() {

    const modal = document.getElementById('pasienModal');

    if (modal) {

        modal.classList.remove('show');

    }

}



/* ==========================================================
   FORMAT TANGGAL
========================================================== */

function formatTanggal(tanggal) {

    if (!tanggal) {

        return '-';

    }


    const date = new Date(tanggal);


    if (isNaN(date.getTime())) {

        return tanggal;

    }


    return date.toLocaleDateString(
        'id-ID',
        {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        }
    );

}



/* ==========================================================
   HAPUS DUMMY
========================================================== */

function hapusDummy(nama) {

    alert(
        'Data dummy "' +
        nama +
        '" belum tersimpan di database.'
    );

}

</script>

@endsection