@extends('layouts.app')

@section('title', 'Kunjungan Poli Obgyn - Klinik Utama Merah Putih')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <style>
        /* ===== Satu card besar membungkus Data Pasien + Riwayat Kunjungan ===== */
        .kj-card {
            border: 1px solid #d9d9d9;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            padding: 18px 20px 20px;
        }

        .kj-section-title {
            color: #b81d24;
            font-weight: 700;
            font-size: 15px;
            padding: 0 18px 12px;
            margin: 0 -18px 14px;
            border-bottom: 1px solid #999;
        }

        /* Kotak border membungkus judul "Data Pasien" + fields-nya, sama seperti tabel Riwayat Kunjungan */
        .kj-datapasien-box {
            border: 1px solid #999;
            border-radius: 6px;
            padding: 14px 18px 18px;
            margin-bottom: 22px;
        }

        /* ===== Data Pasien: label polos + value dalam kotak border, sejajar ===== */
        .kj-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 32px;
        }
        .kj-field {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .kj-field .kj-label {
            flex: 0 0 auto;
            min-width: 110px;
            font-size: 13px;
            font-weight: 700;
            color: #333;
            white-space: nowrap;
        }
        .kj-field .kj-value {
            flex: 1 1 auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 13px;
            color: #333;
            background: #fff;
            text-align: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            min-width: 0;
        }
        /* ===== End Data Pasien ===== */

        .kj-riwayat-title {
            color: #b81d24;
            font-weight: 700;
            font-size: 15px;
            padding: 0 18px 12px;
            margin: 0 -18px 14px;
            border-bottom: 1px solid #999;
        }

        /* Kotak border membungkus judul "Riwayat Kunjungan" + tabelnya */
        .kj-riwayat-box {
            border: 1px solid #999;
            border-radius: 6px;
            padding: 14px 18px 18px;
        }

        /* ===== Tabel Riwayat Kunjungan ===== */
        .kj-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border: 1px solid #999;
        }
        .kj-table th:nth-child(1),
        .kj-table td:nth-child(1) { width: 60px; text-align: center; }
        .kj-table th:nth-child(2),
        .kj-table td:nth-child(2) { width: 180px; }
        .kj-table th:nth-child(3),
        .kj-table td:nth-child(3) { width: auto; }
        .kj-table thead th {
            background: #b81d24;
            color: #fff;
            font-weight: 700;
            text-align: left;
            padding: 8px 16px;
            font-size: 14px;
            border-right: 1px solid #d64b52;
            border-bottom: 1px solid #999;
        }
        .kj-table thead th:first-child { text-align: center; }
        .kj-table thead th:last-child { border-right: none; }
        .kj-table tbody td {
            padding: 4px 16px;
            border-bottom: 1px solid #999;
            border-right: 1px solid #999;
            font-size: 14px;
            color: #333;
            height: 22px;
        }
        .kj-table tbody td:last-child { border-right: none; }
        .kj-table tbody tr:last-child td { border-bottom: none; }
        .kj-table tbody td:first-child { text-align: center; }
        .kj-table tbody tr.kj-empty-row td {
            color: transparent;
        }
        /* ===== End tabel ===== */

        .kj-back-btn {
            display: inline-block;
            background: #b81d24;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-bottom: 16px;
        }
        .kj-back-btn:hover {
            background: #9c161c;
            color: #fff;
        }
    </style>
@endsection

@section('content')

    {{-- =====================================================
         DATA DUMMY PASIEN POLI OBGYN
         Sama persis dengan yang ada di obgyn.blade.php, supaya
         pasien dengan no_rm dari URL bisa ditemukan datanya.
    ===================================================== --}}
    @php
        $semuaPasien = $semuaPasien ?? [
            (object) [
                'no_rm' => 'RM-0002', 'nama_pasien' => 'Siti Aminah', 'nik' => '3515025205850002',
                'tgl_lahir' => '1985-05-12', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Diponegoro No. 25',
                'no_hp' => '081355667788', 'tempat_lahir' => 'Surabaya', 'umur' => '41 Tahun',
            ],
            (object) [
                'no_rm' => 'RM-0004', 'nama_pasien' => 'Dewi Lestari', 'nik' => '3515044507920004',
                'tgl_lahir' => '1992-07-05', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Ahmad Yani No. 15',
                'no_hp' => '082233445566', 'tempat_lahir' => 'Surabaya', 'umur' => '34 Tahun',
            ],
            (object) [
                'no_rm' => 'RM-0006', 'nama_pasien' => 'Lina Marlina', 'nik' => '3515065508800006',
                'tgl_lahir' => '1980-08-15', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Pahlawan No. 32',
                'no_hp' => '081366778899', 'tempat_lahir' => 'Sidoarjo', 'umur' => '46 Tahun',
            ],
            (object) [
                'no_rm' => 'RM-0008', 'nama_pasien' => 'Nur Aisyah', 'nik' => '3515084203840008',
                'tgl_lahir' => '1984-03-02', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Hasanudin No. 18',
                'no_hp' => '083855667788', 'tempat_lahir' => 'Gresik', 'umur' => '42 Tahun',
            ],
            (object) [
                'no_rm' => 'RM-0010', 'nama_pasien' => 'Sri Wahyuni', 'nik' => '3515105807750010',
                'tgl_lahir' => '1975-07-18', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Mawar No. 12',
                'no_hp' => '081298112233', 'tempat_lahir' => 'Sidoarjo', 'umur' => '51 Tahun',
            ],
        ];

        // Cari pasien sesuai no_rm yang datang dari URL
        $pasien = collect($semuaPasien)->firstWhere('no_rm', $no_rm ?? request('no_rm'));
    @endphp

    <a href="{{ route('poli.obgyn') }}" class="kj-back-btn">Kembali</a>

    <div class="kj-card">

        {{-- ===== Data Pasien (title + fields dibungkus 1 box border) ===== --}}
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
                <p style="color:#888;">Data pasien tidak ditemukan.</p>
            @endif
        </div>

        {{-- ===== Riwayat Kunjungan (title + tabel dibungkus 1 box border) ===== --}}
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
                    @for ($i = 0; $i < 6; $i++)
                        <tr class="kj-empty-row">
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

    </div>

@endsection