@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Syaraf')
@section('header-icon', '🩺')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/syaraf-kunjungan-baru.css') }}">

    {{-- Kotak Assesment: Data Pasien, Tanda Vital, SOAP, Diagnosa (dirapikan) --}}
    <style>
        :root {
            --kb-merah: #b81d24;
            --kb-hijau: #1e9e4a;
            --kb-garis: #d9dce1;
            --kb-teks-samar: #8a8f98;
        }

        /* ===== Data Pasien: 2 kolom sama lebar, label & isi sejajar ===== */
        .kb-card .kb-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 40px;
        }
        .kb-card .kb-fields .kb-field {
            display: grid;
            grid-template-columns: 150px minmax(0, 1fr);
            align-items: center;
            gap: 12px;
            margin: 0;
        }
        .kb-card .kb-fields .kb-label {
            font-weight: 600;
        }
        .kb-card .kb-fields .kb-value {
            display: flex;
            align-items: center;
            min-height: 42px;
            box-sizing: border-box;
            padding: 0 14px;
            border: 1px solid var(--kb-garis);
            border-radius: 6px;
            background: #fff;
            width: 100%;
        }
        @media (max-width: 900px) {
            .kb-card .kb-fields { grid-template-columns: 1fr; }
        }

        /* ===== Kotak Tanda Vital: 2 baris x 3 kolom (6 isian) =====
           Ukuran disamakan dengan Poli Obgyn (kotak rapat, isian 34px).
           Tiap isian = grid sendiri [label | isian | satuan], lebar menyesuaikan layar.
           Memakai #tab-assesment + !important agar tidak tertimpa syaraf-kunjungan-baru.css */
        #tab-assesment .kb-vitals-box {
            padding: 12px 14px 12px 18px !important;
            border: 1px solid var(--kb-garis);
            border-radius: 10px;
            background: #fff;
            box-sizing: border-box;
        }
        #tab-assesment .kb-vitals-box .kb-vitals {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            column-gap: 28px !important;
            row-gap: 10px !important;
            align-items: center !important;
        }
        #tab-assesment .kb-vitals .kb-vital-row {
            display: grid !important;
            grid-template-columns: 70px minmax(0, 1fr) 62px !important;
            align-items: center !important;
            column-gap: 8px !important;
            margin: 0 !important;
            width: auto !important;
            min-width: 0 !important;
        }
        #tab-assesment .kb-vitals .kb-vital-row label {
            font-weight: 600;
            margin: 0 !important;
            width: auto !important;
            white-space: nowrap;
        }
        #tab-assesment .kb-vitals .kb-vital-row input[type=text] {
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            height: 34px !important;
            box-sizing: border-box;
            padding: 0 10px !important;
            font-size: 14px !important;
            font-family: inherit;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
        }
        #tab-assesment .kb-vitals .kb-vital-row input[type=text]:focus {
            outline: none;
            border-color: var(--kb-merah);
            box-shadow: 0 0 0 3px rgba(184, 29, 36, 0.12);
        }
        #tab-assesment .kb-vitals .kb-vital-unit {
            color: var(--kb-teks-samar);
            font-size: 13px;
            white-space: nowrap;
            margin: 0 !important;
        }
        @media (max-width: 1000px) {
            #tab-assesment .kb-vitals-box .kb-vitals { grid-template-columns: repeat(2, minmax(0, 1fr)) !important; }
        }
        @media (max-width: 640px) {
            #tab-assesment .kb-vitals-box .kb-vitals { grid-template-columns: 1fr !important; }
        }

        /* Checkbox Alergi: sama dengan kotak centang Penyakit/Tindakan */
        #tab-assesment .kb-vital-row .kb-check-box {
            justify-self: start;
            flex: 0 0 auto;
            width: 34px !important;
            height: 34px !important;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
        }
        .kb-vital-row .kb-vital-check {
            -webkit-appearance: none;
            appearance: none;
            width: 22px;
            height: 22px;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #555;
            border-radius: 4px;
            background: #fff;
            cursor: pointer;
        }
        .kb-vital-row .kb-vital-check::after {
            content: "✓";
            color: transparent;
            font-size: 15px;
            font-weight: 700;
            line-height: 1;
        }
        .kb-vital-row .kb-vital-check:checked {
            background: var(--kb-hijau);
            border-color: var(--kb-hijau);
        }
        .kb-vital-row .kb-vital-check:checked::after {
            color: #fff;
        }
        .kb-vital-row .kb-vital-check:focus-visible {
            outline: 2px solid var(--kb-merah);
            outline-offset: 2px;
        }

        /* ===== Kotak SOAP ===== */
        .kb-soap-box {
            gap: 16px;
            padding: 20px 24px;
            border: 1px solid var(--kb-garis);
            border-radius: 10px;
            background: #fff;
            box-sizing: border-box;
        }
        .kb-soap-box .kb-soap-row {
            gap: 12px;
            align-items: flex-start;
        }
        .kb-soap-box .kb-soap-badge {
            width: 34px;
            height: 34px;
            font-size: 15px;
            margin-top: 2px;
            flex: 0 0 auto;
        }
        .kb-soap-box .kb-soap-row textarea {
            width: 100%;
            box-sizing: border-box;
            min-height: 110px;
            padding: 12px 16px;
            font-size: 15px;
            font-family: inherit;
            line-height: 1.5;
            resize: vertical;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .kb-soap-box .kb-soap-row textarea:focus {
            outline: none;
            border-color: var(--kb-merah);
            box-shadow: 0 0 0 3px rgba(184, 29, 36, 0.12);
        }

        .kb-plan-wrap {
            position: relative;
            flex: 1;
            min-width: 0;
        }
        .kb-plan-wrap textarea[name="plan"] {
            padding-right: 110px; /* ruang untuk tombol Cetak */
        }
        .kb-plan-wrap .kb-cetak-plan {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            margin: 0;
            white-space: nowrap;
            background: #fff;
            color: var(--kb-merah);
            font-weight: 600;
            font-size: 13px;
            padding: 7px 16px;
            border: 1px solid var(--kb-merah);
            border-radius: 5px;
            cursor: pointer;
        }
        .kb-plan-wrap .kb-cetak-plan:hover {
            background: var(--kb-merah);
            color: #fff;
        }

        /* ===== Diagnosa ===== */
        .kb-diagnosa-row {
            align-items: flex-start;
        }
        .kb-diagnosa-row .kb-check-box {
            flex: 0 0 auto;
            width: 40px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            background: #fff;
        }
        .kb-diagnosa-row .kb-check-box .kb-check-icon {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #555;
            border-radius: 4px;
            background: #fff;
            color: transparent;
            font-size: 15px;
            font-weight: 700;
            line-height: 1;
            cursor: pointer;
            user-select: none;
        }
        .kb-diagnosa-row .kb-check-box .kb-check-icon.aktif {
            background: var(--kb-hijau);
            border-color: var(--kb-hijau);
            color: #fff;
        }

        /* Daftar penyakit/tindakan yang sudah dicentang */
        .kb-diagnosa-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 10px;
        }
        .kb-diagnosa-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 8px 12px;
            font-size: 13px;
            color: #333;
            background: #fdecea;
            border: 1px solid #f3c2bd;
            border-radius: 8px;
        }
        .kb-diagnosa-aksi {
            display: flex;
            gap: 4px;
            flex: 0 0 auto;
        }
        .kb-diagnosa-aksi button {
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 6px;
            background: transparent;
            font-size: 14px;
            cursor: pointer;
            transition: background .15s;
        }
        .kb-aksi-hapus { color: var(--kb-merah); }
        .kb-aksi-hapus:hover { background: rgba(184, 29, 36, 0.14); }

        /* ===== Tab Radiologi ===== */
        #tab-radiologi .rd-card {
            padding: 20px 24px;
            border: 1px solid var(--kb-garis);
            border-radius: 10px;
            background: #fff;
            box-sizing: border-box;
        }
        #tab-radiologi .rd-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 14px;
        }
        #tab-radiologi .rd-sub {
            font-weight: 600;
            margin: 18px 0 8px;
        }
        #tab-radiologi .rd-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px 40px;
        }
        #tab-radiologi .rd-item {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        #tab-radiologi .rd-item input[type=checkbox] {
            width: 16px;
            height: 16px;
            margin: 0;
            accent-color: var(--kb-merah);
        }
        #tab-radiologi .rd-lainnya input[type=text] {
            flex: 1;
            min-width: 0;
            height: 34px;
            box-sizing: border-box;
            padding: 0 10px;
            font: inherit;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
        }
        #tab-radiologi .rd-lainnya input[type=text]:disabled {
            background: #f1f1f1;
            cursor: not-allowed;
        }
        #tab-radiologi .rd-lainnya input[type=text]:focus,
        #tab-radiologi .rd-hasil:focus {
            outline: none;
            border-color: var(--kb-merah);
            box-shadow: 0 0 0 3px rgba(184, 29, 36, 0.12);
        }
        #tab-radiologi .rd-upload {
            padding: 24px 16px;
            text-align: center;
            color: #666;
            border: 2px dashed #ccc;
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
        }
        #tab-radiologi .rd-upload:hover {
            border-color: var(--kb-merah);
        }
        #tab-radiologi .rd-hint {
            margin-top: 4px;
            font-size: 12px;
            color: var(--kb-teks-samar);
        }
        #tab-radiologi .rd-nama-file {
            margin-top: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }
        #tab-radiologi .rd-hasil {
            width: 100%;
            min-height: 110px;
            box-sizing: border-box;
            padding: 12px 16px;
            font: inherit;
            line-height: 1.5;
            resize: vertical;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        @media (max-width: 640px) {
            #tab-radiologi .rd-grid { grid-template-columns: 1fr; }
        }
    </style>

    {{-- Area cetak resep & cetak Clinical Pathway (hasil 1 lembar) --}}
    <style>
        @page {
            size: A4;
            margin: 0; /* juga menghilangkan tanggal & URL bawaan browser di kertas */
        }
        @media screen {
            .cetak-resep-only {
                display: none;
            }
        }
        @media print {
            body.cetak-resep-mode > *:not(#cetak-resep-area) {
                display: none !important;
            }
            body.cetak-cp-mode > *:not(#cetak-cp-area) {
                display: none !important;
            }
            html, body.cetak-resep-mode, body.cetak-cp-mode {
                margin: 0 !important;
                padding: 0 !important;
                height: auto !important;
                overflow: visible !important;
                background: #fff !important;
            }
            body.cetak-resep-mode #cetak-resep-area {
                display: block !important;
                visibility: visible !important;
                position: static !important;
                width: 100%;
                box-sizing: border-box;
                padding: 15mm 18mm;
                font-family: Arial, sans-serif;
                color: #000;
                page-break-inside: avoid;
            }
            body.cetak-cp-mode #cetak-cp-area {
                display: block !important;
                visibility: visible !important;
                position: static !important;
                width: 100%;
                box-sizing: border-box;
                padding: 15mm 18mm;
                font-family: Arial, sans-serif;
                color: #000;
            }
            body.cetak-resep-mode #cetak-resep-area *,
            body.cetak-cp-mode #cetak-cp-area * {
                visibility: visible !important;
            }
            .cetak-header {
                display: flex;
                align-items: center;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 15px;
            }
            .cetak-logo {
                width: 80px;
                height: 80px;
                margin-right: 20px;
                object-fit: contain;
            }
            .cetak-header-text {
                flex-grow: 1;
                text-align: center;
            }
            .cetak-header-text h2 {
                margin: 0;
                font-size: 18px;
            }
            .cetak-header-text p {
                margin: 3px 0;
                font-size: 12px;
            }
            .cetak-info-table {
                width: 100%;
                font-size: 12px;
                margin-bottom: 10px;
            }
            .cetak-info-table td {
                padding: 3px 0;
                vertical-align: top;
            }
            .cetak-divider {
                border-bottom: 2px solid #000;
                margin-bottom: 15px;
            }
            .cetak-title {
                text-align: center;
                font-weight: bold;
                font-size: 16px;
                margin-bottom: 20px;
            }
            .cetak-body {
                display: flex;
                gap: 15px;
                min-height: 250px;
                font-size: 14px;
            }
            .resep-rp {
                font-weight: bold;
                font-size: 18px;
                margin: 0;
            }
            .resep-isi {
                white-space: pre-wrap;
                flex-grow: 1;
                line-height: 1.5;
                min-height: 0;
                padding: 0;
            }
            .cetak-footer {
                margin-top: 20px;
                text-align: right;
                font-size: 12px;
            }
            .cetak-footer p {
                margin: 2px 0;
            }
            .cetak-signature {
                margin-top: 70px; /* Jarak untuk tanda tangan manual */
            }

            /* Tabel hasil Clinical Pathway */
            .cp-print-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }
            .cp-print-table th,
            .cp-print-table td {
                border: 1px solid #000;
                padding: 6px 8px;
                vertical-align: top;
            }
            .cp-print-table th {
                text-align: center;
                font-weight: bold;
            }
            .cp-print-table td.cp-print-aktivitas {
                width: 24%;
                font-weight: bold;
            }
            .cp-print-table td.cp-print-ket,
            .cp-print-table td.cp-print-waktu {
                white-space: pre-line;
                line-height: 1.5;
            }
            .cp-print-table td.cp-print-waktu {
                width: 10%;
                text-align: center;
            }
            .cp-print-table td.cp-print-tarif {
                width: 16%;
                text-align: right;
                white-space: nowrap;
            }
            .cp-print-table tr {
                page-break-inside: avoid;
            }
        }
    </style>

    {{-- Tabel Clinical Pathway --}}
    <style>
        #tab-pathway .cp-wrap { border: 1px solid #d9dce1; border-radius: 8px; overflow: hidden; background: #fff; }
        #tab-pathway .cp-scroll { max-height: 600px; overflow: auto; }
        #tab-pathway .cp-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        #tab-pathway .cp-table thead th { background: #a31515; color: #fff; padding: 8px 12px; text-align: center; position: sticky; top: 0; z-index: 1; }
        #tab-pathway .cp-table td { border: 1px solid #d9dce1; padding: 8px 12px; vertical-align: top; }
        #tab-pathway .cp-table td.cp-aktivitas { width: 25%; font-weight: 700; background: #f5f5f5; }
        #tab-pathway .cp-table input[type=text],
        #tab-pathway .cp-table textarea { width: 100%; box-sizing: border-box; padding: 5px 8px; margin: 2px 0; border: 1px solid #ccc; border-radius: 4px; font: inherit; }
        #tab-pathway .cp-check { display: flex; align-items: flex-start; gap: 6px; margin: 3px 0; }
        #tab-pathway .cp-check input { margin-top: 3px; }
        #tab-pathway .cp-rp { display: flex; align-items: center; gap: 6px; color: #666; }
        #tab-pathway .cp-footer { text-align: right; padding: 12px 16px; border-top: 1px solid #d9dce1; }
        #tab-pathway .cp-cetak { background: #b81d24; color: #fff; font-weight: 700; border: none; border-radius: 6px; padding: 8px 22px; cursor: pointer; }

        /* Baris Diagnosa: satu baris per Dx agar label dan isian sejajar */
        #tab-pathway .cp-dx-head td { border-bottom: none; padding-bottom: 2px; }
        #tab-pathway .cp-dx-row td { border-top: none; vertical-align: middle; padding-top: 3px; padding-bottom: 3px; }
        #tab-pathway .cp-dx-mid td { border-bottom: none; }
        #tab-pathway .cp-table td.cp-dx-label { font-weight: 400; font-size: 12px; color: #444; padding-left: 28px; }

        /* Kolom Waktu terisi otomatis */
        #tab-pathway .cp-table input.cp-waktu { background: #f5f5f5; text-align: center; color: #333; cursor: default; }
    </style>
@endsection

@section('content')

    @php
        $semuaPasien = $semuaPasien ?? [
            (object)['no_rm'=>'RM-0001','nama_pasien'=>'Budi Santoso','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1980-01-01','umur'=>'46 Tahun','no_hp'=>'081234567890'],
            (object)['no_rm'=>'RM-0003','nama_pasien'=>'Andi Pratama','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Malang','tgl_lahir'=>'1990-03-15','umur'=>'36 Tahun','no_hp'=>'082112223333'],
            (object)['no_rm'=>'RM-0005','nama_pasien'=>'Rudi Hartono','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Mojokerto','tgl_lahir'=>'1975-06-12','umur'=>'51 Tahun','no_hp'=>'081377889900'],
            (object)['no_rm'=>'RM-0007','nama_pasien'=>'Fajar Ramadhan','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1998-01-18','umur'=>'28 Tahun','no_hp'=>'083811223344'],
            (object)['no_rm'=>'RM-0009','nama_pasien'=>'Agus Setiawan','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Surabaya','tgl_lahir'=>'1970-06-22','umur'=>'56 Tahun','no_hp'=>'081245678901'],
        ];

        $pasien = collect($semuaPasien)->firstWhere('no_rm', $no_rm ?? request('no_rm'));

        $tglKunjungan = request()->route('tanggal');
    @endphp

    <div class="kb-card">
        <div class="kb-section-title">Data Pasien</div>

        @if ($pasien)
            <div class="kb-fields">
                <div class="kb-field">
                    <span class="kb-label">No RM</span>
                    <span class="kb-value">{{ $pasien->no_rm }}</span>
                </div>
                <div class="kb-field">
                    <span class="kb-label">Tempat, Tgl Lahir</span>
                    <span class="kb-value">{{ $pasien->tempat_lahir }}, {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') }}</span>
                </div>
                <div class="kb-field">
                    <span class="kb-label">Nama</span>
                    <span class="kb-value">{{ $pasien->nama_pasien }}</span>
                </div>
                <div class="kb-field">
                    <span class="kb-label">Umur</span>
                    <span class="kb-value">{{ $pasien->umur }}</span>
                </div>
                <div class="kb-field">
                    <span class="kb-label">Jenis Kelamin</span>
                    <span class="kb-value">{{ $pasien->jenis_kelamin }}</span>
                </div>
                <div class="kb-field">
                    <span class="kb-label">No Hp</span>
                    <span class="kb-value">{{ $pasien->no_hp }}</span>
                </div>
            </div>
        @else
            <p style="color:#888;">Data pasien tidak ditemukan.</p>
        @endif
    </div>

    <div class="kb-tabs">
        <div class="kb-tab active" onclick="gantiTab('assesment', event)"><i class="fa-solid fa-clipboard-list"></i> Assesment</div>
        <div class="kb-tab" onclick="gantiTab('pathway', event)"><i class="fa-solid fa-diagram-project"></i> Clinical Pathway</div>
        <div class="kb-tab" onclick="gantiTab('radiologi', event)"><i class="fa-solid fa-x-ray"></i> Radiologi</div>
    </div>

    {{-- ===== AREA CETAK RESEP (tersembunyi, hanya muncul saat print, hasil 1 lembar) ===== --}}
    <div id="cetak-resep-area" class="cetak-resep-only">
        <!-- Kop Resep -->
        <div class="cetak-header">
            <img src="{{ asset('images/logo.png') }}" class="cetak-logo" alt="Logo Klinik">
            <div class="cetak-header-text">
                <h2>KLINIK RAWAT INAP MERAH PUTIH</h2>
                <p>Jl. Ronggo Warsito No. 98 A, Ngawi</p>
                <p>Telp: (0351) 745596</p>
            </div>
        </div>

        <!-- Informasi Pasien (menggunakan tabel agar titik dua sejajar) -->
        <table class="cetak-info-table">
            <tr>
                <td width="15%">Nama Pasien</td><td width="2%">:</td><td width="83%">{{ $pasien->nama_pasien ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. R.M.</td><td>:</td><td>{{ $pasien->no_rm ?? '-' }}</td>
            </tr>
            <tr>
                <td>Umur / JK</td><td>:</td><td>{{ $pasien->umur ?? '-' }} / {{ $pasien->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pemberi Resep</td><td>:</td><td>Dokter Poli Syaraf</td>
            </tr>
        </table>

        <div class="cetak-divider"></div>

        <div class="cetak-title">RESEP</div>

        <!-- Isi Resep -->
        <div class="cetak-body">
            <div class="resep-rp">R/</div>
            <div class="resep-isi" id="resep-isi-plan"></div>
        </div>

        <!-- Tanda Tangan Dokter -->
        <div class="cetak-footer">
            <p>Ngawi, <span id="resep-tanggal"></span></p>
            <div class="cetak-signature">
                <p>dr. Poli Syaraf</p>
            </div>
        </div>
    </div>

    {{-- ===== AREA CETAK CLINICAL PATHWAY (tersembunyi, hanya muncul saat print) ===== --}}
    <div id="cetak-cp-area" class="cetak-resep-only">
        <!-- Kop -->
        <div class="cetak-header">
            <img src="{{ asset('images/logo.png') }}" class="cetak-logo" alt="Logo Klinik">
            <div class="cetak-header-text">
                <h2>KLINIK RAWAT INAP MERAH PUTIH</h2>
                <p>Jl. Ronggo Warsito No. 98 A, Ngawi</p>
                <p>Telp: (0351) 745596</p>
            </div>
        </div>

        <!-- Informasi Pasien -->
        <table class="cetak-info-table">
            <tr>
                <td width="15%">Nama Pasien</td><td width="2%">:</td><td width="83%">{{ $pasien->nama_pasien ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. R.M.</td><td>:</td><td>{{ $pasien->no_rm ?? '-' }}</td>
            </tr>
            <tr>
                <td>Umur / JK</td><td>:</td><td>{{ $pasien->umur ?? '-' }} / {{ $pasien->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tgl. Kunjungan</td><td>:</td><td>{{ $tglKunjungan ? \Carbon\Carbon::parse($tglKunjungan)->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Poli</td><td>:</td><td>Poli Syaraf</td>
            </tr>
        </table>

        <div class="cetak-divider"></div>

        <div class="cetak-title">CLINICAL PATHWAY</div>

        <!-- Hasil isian Clinical Pathway (diisi lewat JavaScript) -->
        <table class="cp-print-table">
            <thead>
                <tr>
                    <th>Aktivitas Pelayanan</th>
                    <th>Keterangan</th>
                    <th>Waktu</th>
                    <th>Tarif</th>
                </tr>
            </thead>
            <tbody id="cp-cetak-body"></tbody>
            <tfoot>
                <tr>
                    <td class="cp-print-aktivitas" colspan="3" style="text-align:right;">Total</td>
                    <td class="cp-print-tarif" id="cp-cetak-total"></td>
                </tr>
            </tfoot>
        </table>

        <!-- Tanda Tangan Dokter -->
        <div class="cetak-footer">
            <p>Ngawi, <span id="cp-tanggal"></span></p>
            <div class="cetak-signature">
                <p>dr. Poli Syaraf</p>
            </div>
        </div>
    </div>

    <div id="tab-assesment" class="kb-tab-content active">

        {{-- ===== Kotak Vital Signs ===== --}}
        <div class="kb-vitals-box">
            <div class="kb-vitals">
                <div class="kb-vital-row">
                    <label>TD</label>
                    <input type="text" placeholder="mis. 120/80" inputmode="numeric" autocomplete="off">
                    <span class="kb-vital-unit">mmHg</span>
                </div>
                <div class="kb-vital-row">
                    <label>HR</label>
                    <input type="text" placeholder="mis. 80" inputmode="numeric" autocomplete="off">
                    <span class="kb-vital-unit">x/menit</span>
                </div>
                <div class="kb-vital-row">
                    <label>SpO2</label>
                    <input type="text" placeholder="mis. 98" inputmode="numeric" autocomplete="off">
                    <span class="kb-vital-unit">%</span>
                </div>
                <div class="kb-vital-row">
                    <label>Suhu</label>
                    <input type="text" placeholder="mis. 36.5" inputmode="decimal" autocomplete="off">
                    <span class="kb-vital-unit">°C</span>
                </div>
                <div class="kb-vital-row">
                    <label>RR</label>
                    <input type="text" placeholder="mis. 20" inputmode="numeric" autocomplete="off">
                    <span class="kb-vital-unit">x/menit</span>
                </div>
                <div class="kb-vital-row kb-vital-lainnya">
                    <label for="chkVital">Alergi</label>
                    <input type="text" placeholder="mis. tidak ada" autocomplete="off">
                    <span class="kb-check-box">
                        <input type="checkbox" class="kb-vital-check" id="chkVital"
                               title="Centang untuk memasukkan tanda vital ke Objective"
                               onchange="toggleVitalKeObjektif(this)">
                    </span>
                </div>
            </div>
        </div>

        <hr class="kb-divider">

        {{-- ===== Kotak SOAP ===== --}}
        <div class="kb-soap-title">SOAP</div>
        <div class="kb-soap-box">
            <div class="kb-soap-row">
                <div class="kb-soap-badge">S</div>
                <textarea name="subjective" rows="4" placeholder="Subjektif..."></textarea>
            </div>

            <div class="kb-soap-row">
                <div class="kb-soap-badge">O</div>
                <textarea name="objective" rows="4" placeholder="Hasil pemeriksaan objektif..."></textarea>
            </div>

            <div class="kb-soap-row">
                <div class="kb-soap-badge">A</div>
                <textarea name="assessment" rows="4" placeholder="Assessment / analisa..."></textarea>
            </div>

            <div class="kb-soap-row">
                <div class="kb-soap-badge">P</div>
                {{-- Plan: tombol Cetak berada di dalam kolom --}}
                <div class="kb-plan-wrap">
                    <textarea name="plan" rows="4" placeholder="Rencana/plan..."></textarea>
                    <button type="button" class="kb-cetak-btn kb-cetak-plan" onclick="cetakResep()">
                        <i class="fa-solid fa-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>

        <hr class="kb-divider">

        {{-- ===== Diagnosa ===== --}}
        <div class="kb-diagnosa-title">Diagnosa</div>
        <div class="kb-diagnosa-row">
            <div class="kb-search-field">
                <label>Penyakit</label>
                <div class="kb-search-inline">
                    <div class="kb-search-box">
                        <input type="text" id="cariPenyakit" placeholder="Cari kode / nama penyakit..." autocomplete="off"
                               oninput="cariItem('penyakit')"
                               onkeydown="if (event.key === 'Enter') { event.preventDefault(); tambahDiagnosa('penyakit'); }">
                        <span class="kb-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <div class="kb-dropdown" id="dropdownPenyakit"></div>
                    </div>
                    <div class="kb-check-box">
                        <span class="kb-check-icon" id="checkPenyakit" role="button"
                              title="Tambahkan penyakit ke daftar"
                              onclick="tambahDiagnosa('penyakit')">✓</span>
                    </div>
                </div>
                <div class="kb-diagnosa-list" id="listPenyakit"></div>
            </div>
            <div class="kb-search-field">
                <label>Tindakan</label>
                <div class="kb-search-inline">
                    <div class="kb-search-box">
                        <input type="text" id="cariTindakan" placeholder="Cari kode / nama tindakan..." autocomplete="off"
                               oninput="cariItem('tindakan')"
                               onkeydown="if (event.key === 'Enter') { event.preventDefault(); tambahDiagnosa('tindakan'); }">
                        <span class="kb-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <div class="kb-dropdown" id="dropdownTindakan"></div>
                    </div>
                    <div class="kb-check-box">
                        <span class="kb-check-icon" id="checkTindakan" role="button"
                              title="Tambahkan tindakan ke daftar"
                              onclick="tambahDiagnosa('tindakan')">✓</span>
                    </div>
                </div>
                <div class="kb-diagnosa-list" id="listTindakan"></div>
            </div>
        </div>

        <div class="kb-actions">
            <button type="button" class="kb-btn kb-btn-rujuk" onclick="rujukPasien()">
                <i class="fa-solid fa-right-from-bracket"></i> Rujuk
            </button>
            <button type="button" class="kb-btn kb-btn-simpan" onclick="simpanKunjunganBaru()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
        </div>
    </div>

    <div id="tab-pathway" class="kb-tab-content">
        <div class="cp-wrap">
            <div class="cp-scroll">
                <table class="cp-table">
                    <thead>
                        <tr>
                            <th style="width:25%;">Aktivitas Pelayanan</th>
                            <th style="width:45%;">Keterangan</th>
                            <th style="width:15%;">Waktu</th>
                            <th style="width:15%;">Tarif</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Diagnosa: satu baris per Dx, tiap baris punya Waktu sendiri --}}
                        <tr class="cp-dx-head">
                            <td class="cp-aktivitas">Diagnosa</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr class="cp-dx-row cp-dx-mid">
                            <td class="cp-aktivitas cp-dx-label">Dx Utama</td>
                            <td><input type="text" class="cp-dx-input" data-label="Dx Utama"></td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td></td>
                        </tr>
                        <tr class="cp-dx-row cp-dx-mid">
                            <td class="cp-aktivitas cp-dx-label">Dx Sekunder</td>
                            <td><input type="text" class="cp-dx-input" data-label="Dx Sekunder"></td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td></td>
                        </tr>
                        <tr class="cp-dx-row">
                            <td class="cp-aktivitas cp-dx-label">Dx Banding</td>
                            <td><input type="text" class="cp-dx-input" data-label="Dx Banding"></td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td></td>
                        </tr>

                        <tr class="cp-row">
                            <td class="cp-aktivitas" data-nama="Asesmen Klinis">Asesmen Klinis</td>
                            <td><textarea rows="2"></textarea></td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td><div class="cp-rp">Rp <input type="text" class="cp-tarif"></div></td>
                        </tr>

                        <tr class="cp-row">
                            <td class="cp-aktivitas" data-nama="Pemeriksaan Fisik">Pemeriksaan Fisik</td>
                            <td>
                                <label class="cp-check"><input type="checkbox"> Pemeriksaan tanda vital</label>
                                <label class="cp-check"><input type="checkbox"> Inspeksi postur tulang belakang dan gerakan aktif volumna vertebralis</label>
                                <label class="cp-check"><input type="checkbox"> Pemeriksaan motorik, reflek, dan sensorik dermatom</label>
                                <label class="cp-check"><input type="checkbox"> ........</label>
                            </td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td><div class="cp-rp">Rp <input type="text" class="cp-tarif"></div></td>
                        </tr>

                        <tr class="cp-row">
                            <td class="cp-aktivitas" data-nama="Pemeriksaan Penunjang">Pemeriksaan Penunjang</td>
                            <td>
                                <label class="cp-check"><input type="checkbox"> Magnetic Resonance Imaging (MRI)</label>
                                <label class="cp-check"><input type="checkbox"> Computerized Tomography (CT Scan)</label>
                                <label class="cp-check"><input type="checkbox"> Foto polos lumbosakral (rontgen / X-ray)</label>
                                <label class="cp-check"><input type="checkbox"> ........</label>
                            </td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td><div class="cp-rp">Rp <input type="text" class="cp-tarif"></div></td>
                        </tr>

                        <tr class="cp-row">
                            <td class="cp-aktivitas" data-nama="Farmakologis">Farmakologis</td>
                            <td>
                                <label class="cp-check"><input type="checkbox"> Antipiretik</label>
                                <label class="cp-check"><input type="checkbox"> Analgesik Adjuvan</label>
                                <label class="cp-check"><input type="checkbox"> NSAID oral</label>
                                <label class="cp-check"><input type="checkbox"> Muscle Relaxant</label>
                                <label class="cp-check"><input type="checkbox"> Cairan IV kristaloid</label>
                                <label class="cp-check"><input type="checkbox"> ........</label>
                            </td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td><div class="cp-rp">Rp <input type="text" class="cp-tarif"></div></td>
                        </tr>

                        <tr class="cp-row">
                            <td class="cp-aktivitas" data-nama="Fisioterapi">Fisioterapi</td>
                            <td>
                                <label class="cp-check"><input type="checkbox"> Terapi lampu hangat (Infra Red)</label>
                                <label class="cp-check"><input type="checkbox"> Stimulasi Listrik (TENS)</label>
                            </td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td><div class="cp-rp">Rp <input type="text" class="cp-tarif"></div></td>
                        </tr>

                        <tr class="cp-row">
                            <td class="cp-aktivitas" data-nama="Edukasi">Edukasi</td>
                            <td>
                                <label class="cp-check"><input type="checkbox"> Edukasi menjaga postur tubuh yang benar saat duduk dan berdiri</label>
                                <label class="cp-check"><input type="checkbox"> Edukasi olahraga yang menguatkan tulang belakang</label>
                                <label class="cp-check"><input type="checkbox"> Edukasi angkat beban berat</label>
                                <label class="cp-check"><input type="checkbox"> Edukasi menjaga berat badan ideal</label>
                                <label class="cp-check"><input type="checkbox"> ........</label>
                            </td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td></td>
                        </tr>

                        <tr class="cp-row">
                            <td class="cp-aktivitas" data-nama="Variasi Pelayanan">Variasi Pelayanan</td>
                            <td><textarea rows="2"></textarea></td>
                            <td><input type="text" class="cp-waktu" readonly></td>
                            <td></td>
                        </tr>

                        <tr>
                            <td class="cp-aktivitas">Total</td>
                            <td></td>
                            <td></td>
                            <td><div class="cp-rp">Rp <input type="text" id="cpTotal" readonly style="font-weight:700; background:#f5f5f5;"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="cp-footer">
                <button type="button" class="cp-cetak" onclick="cetakCP()"><i class="fa-solid fa-print"></i> Cetak</button>
            </div>
        </div>
    </div>

    {{-- ===== TAB RADIOLOGI (isi sama dengan Poli Obgyn) ===== --}}
    @php
        $radiologiOptions = ['Foto Thoraks', 'MRI', 'USG Abdomen', 'Rontgen', 'CT Scan'];
    @endphp
    <div id="tab-radiologi" class="kb-tab-content">
        <div class="rd-card">
            <div class="rd-title">Form Pemeriksaan</div>
            <div class="rd-sub" style="margin-top:0;">Ceklist Periksa Radiologi</div>

            <div class="rd-grid">
                @foreach ($radiologiOptions as $opt)
                    <label class="rd-item">
                        <input type="checkbox" name="radiologi[]" value="{{ $opt }}"> {{ $opt }}
                    </label>
                @endforeach
                <label class="rd-item rd-lainnya">
                    <input type="checkbox" id="checkRadiologiLainnya" name="radiologi[]" value="Lainnya"> Lainnya
                    <input type="text" id="radiologiLainnya" name="radiologi_lainnya" placeholder="(tuliskan)" disabled>
                </label>
            </div>

            <div class="rd-sub">Scan Hasil (jika perlu)</div>
            <div class="rd-upload" onclick="document.getElementById('scanHasil').click()">
                Klik untuk mengunggah gambar/scan
                <div class="rd-hint">(format: JPG, PNG, PDF &nbsp; Maks. 10 MB)</div>
                <div class="rd-nama-file" id="namaFileScan"></div>
                <input type="file" id="scanHasil" name="scan_hasil" accept=".jpg,.jpeg,.png,.pdf" style="display:none;"
                       onchange="document.getElementById('namaFileScan').textContent = this.files.length ? this.files[0].name : ''">
            </div>

            <div class="rd-sub">Hasil Pemeriksaan Radiologi</div>
            <textarea class="rd-hasil" name="hasil_radiologi" rows="5" placeholder="Masukkan hasil pemeriksaan radiologi di sini"></textarea>
        </div>

        <div class="kb-actions">
            <button type="button" class="kb-btn kb-btn-rujuk" onclick="resetRadiologi()">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </button>
            <button type="button" class="kb-btn kb-btn-simpan" onclick="simpanKunjunganBaru()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
        </div>
    </div>

@endsection

@section('extra-js')
<script>
    // No. RM pasien yang sedang diperiksa (null kalau pasien tidak ditemukan)
    const NO_RM_PASIEN = @json($pasien->no_rm ?? null);

    // Kunci penyimpanan status periksa Poli Syaraf (dipakai juga di halaman Daftar Pasien & Pendaftaran)
    const KUNCI_STATUS_PERIKSA = 'syaraf_status_periksa';
    const KUNCI_WAKTU_SELESAI = 'syaraf_waktu_selesai';

    function gantiTab(tab, event) {
        document.querySelectorAll('.kb-tab').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.kb-tab-content').forEach(el => el.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('tab-' + tab).classList.add('active');
    }

    function cetakResep() {
        const planEl = document.querySelector('textarea[name="plan"]');
        const planText = planEl ? planEl.value.trim() : '';

        if (planText === '') {
            alert('Isi bagian Plan (P) di SOAP dulu sebelum mencetak resep.');
            return;
        }

        const area = document.getElementById('cetak-resep-area');

        document.getElementById('resep-isi-plan').innerText = planText;
        document.getElementById('resep-tanggal').innerText = new Date().toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });

        // Pindahkan area resep jadi anak langsung <body> agar elemen lain bisa disembunyikan total
        document.body.appendChild(area);
        document.body.classList.add('cetak-resep-mode');

        window.print();
    }

    // ===== Clinical Pathway: total tarif otomatis =====
    function hitungTotalCP() {
        let total = 0;
        document.querySelectorAll('#tab-pathway .cp-tarif').forEach(function (input) {
            const angka = parseInt(input.value.replace(/\D/g, ''), 10);
            if (!isNaN(angka)) total += angka;
        });
        document.getElementById('cpTotal').value = total ? total.toLocaleString('id-ID') : '';
    }

    // ===== Clinical Pathway: Waktu terisi otomatis saat baris diisi =====
    function jamSekarang() {
        const d = new Date();
        return String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
    }

    function perbaruiWaktuBaris(tr) {
        const waktu = tr.querySelector('.cp-waktu');
        if (!waktu) return;

        let terisi = false;
        tr.querySelectorAll('input[type=checkbox]').forEach(function (cb) {
            if (cb.checked) terisi = true;
        });
        tr.querySelectorAll('input[type=text]:not(.cp-waktu), textarea').forEach(function (f) {
            if (f.value.trim() !== '') terisi = true;
        });

        if (terisi) {
            // jam hanya dicatat saat pertama kali baris diisi
            if (waktu.value === '') waktu.value = jamSekarang();
        } else {
            waktu.value = '';
        }
    }

    // Event delegation: tetap berfungsi walau skrip dimuat sebelum tabel tampil
    document.addEventListener('input', function (e) {
        const tr = e.target.closest('#tab-pathway tr.cp-row, #tab-pathway tr.cp-dx-row');
        if (tr) perbaruiWaktuBaris(tr);

        if (e.target.classList && e.target.classList.contains('cp-tarif')) {
            hitungTotalCP();
        }
    });

    document.addEventListener('change', function (e) {
        const tr = e.target.closest('#tab-pathway tr.cp-row, #tab-pathway tr.cp-dx-row');
        if (tr) perbaruiWaktuBaris(tr);
    });

    // ===== Clinical Pathway: cetak hasil isian =====
    function cetakCP() {
        const tbody = document.getElementById('cp-cetak-body');
        tbody.innerHTML = '';
        let adaIsi = false;

        function tambahBaris(aktivitas, ketList, waktu, tarif) {
            const baris = document.createElement('tr');

            const tdA = document.createElement('td');
            tdA.className = 'cp-print-aktivitas';
            tdA.textContent = aktivitas;

            const tdK = document.createElement('td');
            tdK.className = 'cp-print-ket';
            tdK.textContent = ketList.length ? ketList.join('\n') : '-';

            const tdW = document.createElement('td');
            tdW.className = 'cp-print-waktu';
            tdW.textContent = waktu !== '' ? waktu : '-';

            const tdT = document.createElement('td');
            tdT.className = 'cp-print-tarif';
            tdT.textContent = tarif !== '' ? 'Rp ' + tarif : '-';

            baris.appendChild(tdA);
            baris.appendChild(tdK);
            baris.appendChild(tdW);
            baris.appendChild(tdT);
            tbody.appendChild(baris);
        }

        // Diagnosa (Dx Utama, Dx Sekunder, Dx Banding) beserta waktunya masing-masing
        const dx = [];
        const dxWaktu = [];
        document.querySelectorAll('#tab-pathway tr.cp-dx-row').forEach(function (tr) {
            const f = tr.querySelector('.cp-dx-input');
            const w = tr.querySelector('.cp-waktu');
            const v = f ? f.value.trim() : '';
            if (v !== '') {
                dx.push(f.dataset.label + ': ' + v);
                dxWaktu.push(w && w.value.trim() !== '' ? w.value.trim() : '-');
            }
        });
        if (dx.length) adaIsi = true;
        tambahBaris('Diagnosa', dx, dxWaktu.join('\n'), '');

        // Baris lainnya
        document.querySelectorAll('#tab-pathway tr.cp-row').forEach(function (tr) {
            const sel = tr.querySelectorAll(':scope > td');
            const aktivitas = sel[0].dataset.nama || sel[0].textContent.trim();

            // Keterangan: item yang dicentang + isian teks
            const ket = [];
            sel[1].querySelectorAll('label.cp-check').forEach(function (label) {
                const cb = label.querySelector('input[type=checkbox]');
                const teks = label.textContent.trim();
                if (cb && cb.checked && teks !== '........') ket.push('- ' + teks);
            });
            sel[1].querySelectorAll('input[type=text], textarea').forEach(function (f) {
                const v = f.value.trim();
                if (v !== '') ket.push(v);
            });

            // Waktu & Tarif
            const waktuEl = sel[2].querySelector('input');
            const tarifEl = sel[3].querySelector('input');
            const waktu = waktuEl ? waktuEl.value.trim() : '';
            const tarif = tarifEl ? tarifEl.value.trim() : '';

            if (ket.length || waktu || tarif) adaIsi = true;

            tambahBaris(aktivitas, ket, waktu, tarif);
        });

        if (!adaIsi) {
            alert('Isi Clinical Pathway dulu sebelum mencetak.');
            return;
        }

        const total = document.getElementById('cpTotal').value.trim();
        document.getElementById('cp-cetak-total').textContent = total !== '' ? 'Rp ' + total : '-';
        document.getElementById('cp-tanggal').innerText = new Date().toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });

        const area = document.getElementById('cetak-cp-area');
        document.body.appendChild(area);
        document.body.classList.add('cetak-cp-mode');

        window.print();
    }

    // Kembalikan tampilan normal setelah dialog cetak ditutup
    window.addEventListener('afterprint', function () {
        document.body.classList.remove('cetak-resep-mode');
        document.body.classList.remove('cetak-cp-mode');
    });

    // Klik Simpan: status pasien berubah dari "Periksa" (kuning) menjadi "Selesai" (hijau)
    function simpanKunjunganBaru() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        try {
            const kunci = String(NO_RM_PASIEN).trim().toUpperCase();

            const status = JSON.parse(localStorage.getItem(KUNCI_STATUS_PERIKSA)) || {};
            status[kunci] = 'selesai';
            localStorage.setItem(KUNCI_STATUS_PERIKSA, JSON.stringify(status));

            // Catat waktu selesai pemeriksaan (dipakai untuk mengurutkan di Daftar Pasien)
            const waktu = JSON.parse(localStorage.getItem(KUNCI_WAKTU_SELESAI)) || {};
            waktu[kunci] = Date.now();
            localStorage.setItem(KUNCI_WAKTU_SELESAI, JSON.stringify(waktu));
        } catch (e) {
            console.error(e);
        }

        alert('Data kunjungan berhasil disimpan. Status pasien berubah menjadi Selesai.');

        // Kembali ke Daftar Pasien supaya perubahan warna langsung terlihat
        window.location.href = "{{ route('poli.syaraf') }}";
    }

    // ===== Radiologi: kolom "Lainnya" aktif hanya kalau dicentang =====
    const checkRadiologiLainnya = document.getElementById('checkRadiologiLainnya');
    const radiologiLainnya = document.getElementById('radiologiLainnya');

    if (checkRadiologiLainnya) {
        checkRadiologiLainnya.addEventListener('change', function () {
            radiologiLainnya.disabled = !this.checked;
            if (!this.checked) radiologiLainnya.value = '';
        });
    }

    function resetRadiologi() {
        document.querySelectorAll('#tab-radiologi input[type=checkbox]').forEach(el => el.checked = false);
        radiologiLainnya.value = '';
        radiologiLainnya.disabled = true;
        document.getElementById('scanHasil').value = '';
        document.getElementById('namaFileScan').textContent = '';
        document.querySelector('#tab-radiologi textarea[name="hasil_radiologi"]').value = '';
    }

    function rujukPasien() {
        alert('Fitur Rujuk belum tersambung ke database — masih dummy front-end.');
    }

    // Teks tanda vital yang terakhir dimasukkan otomatis ke Objective
    let vitalTerakhir = '';


    // ===== Tanda vital -> Objective (lewat checkbox) =====
    function bangunTeksVital() {
        const bagian = [];

        document.querySelectorAll('.kb-vitals .kb-vital-row').forEach(function (row) {
            const label = row.querySelector('label').textContent.trim();
            const nilai = row.querySelector('input[type=text]').value.trim();
            const unitEl = row.querySelector('.kb-vital-unit');
            const unit = unitEl ? unitEl.textContent.trim() : '';

            if (nilai !== '') {
                bagian.push(label + ': ' + nilai + (unit ? ' ' + unit : ''));
            }
        });

        return bagian.join(', ');
    }

    // Hapus hanya bagian yang diisi otomatis; tulisan manual tetap aman
    function hapusVitalDariObjektif() {
        const objektif = document.querySelector('textarea[name="objective"]');
        if (vitalTerakhir && objektif.value.startsWith(vitalTerakhir)) {
            objektif.value = objektif.value.slice(vitalTerakhir.length).replace(/^\n/, '');
        }
        vitalTerakhir = '';
    }

    function terapkanVitalKeObjektif(teks) {
        const objektif = document.querySelector('textarea[name="objective"]');

        // Ganti bagian otomatis sebelumnya agar tidak terduplikasi
        let sisa = objektif.value;
        if (vitalTerakhir && sisa.startsWith(vitalTerakhir)) {
            sisa = sisa.slice(vitalTerakhir.length).replace(/^\n/, '');
        }

        objektif.value = sisa ? teks + '\n' + sisa : teks;
        vitalTerakhir = teks;
    }

    function toggleVitalKeObjektif(cb) {
        if (cb.checked) {
            const teks = bangunTeksVital();

            if (teks === '') {
                alert('Isi minimal satu tanda vital terlebih dahulu.');
                cb.checked = false;
                return;
            }

            terapkanVitalKeObjektif(teks);
            document.querySelector('textarea[name="objective"]').focus();
        } else {
            hapusVitalDariObjektif();
        }
    }

    // Jika checkbox sudah dicentang lalu nilai vital diubah, Objective ikut diperbarui
    document.addEventListener('input', function (e) {
        if (!e.target.matches('.kb-vitals input[type=text]')) return;

        const chk = document.getElementById('chkVital');
        if (!chk || !chk.checked) return;

        const teks = bangunTeksVital();
        if (teks === '') {
            hapusVitalDariObjektif();
        } else {
            terapkanVitalKeObjektif(teks);
        }
    });

    // ===== Pencarian ICD dari database (kode_diagnosis & kode_tindakan) =====
    const URL_CARI = "{{ url('/cari-kode') }}";

    const konfig = {
        penyakit: { input: 'cariPenyakit', dropdown: 'dropdownPenyakit', icon: 'checkPenyakit', list: 'listPenyakit', name: 'penyakit_id[]' },
        tindakan: { input: 'cariTindakan', dropdown: 'dropdownTindakan', icon: 'checkTindakan', list: 'listTindakan', name: 'tindakan_id[]' },
    };

    // Item yang sedang dipilih dari dropdown (belum dicentang)
    const pilihan = { penyakit: null, tindakan: null };
    // Daftar yang sudah dicentang: [{id, kode, nama}]
    const diagnosaTerpilih = { penyakit: [], tindakan: [] };

    const timerCari = {};
    const urutanCari = { penyakit: 0, tindakan: 0 };

    // Centang hijau hanya menyala kalau ada item yang dipilih dari dropdown
    function updateCentang(jenis) {
        document.getElementById(konfig[jenis].icon).classList.toggle('aktif', pilihan[jenis] !== null);
    }

    function cariItem(jenis) {
        const k = konfig[jenis];
        const input = document.getElementById(k.input);
        const dropdown = document.getElementById(k.dropdown);
        const keyword = input.value.trim();

        // mengetik lagi = membatalkan pilihan sebelumnya
        pilihan[jenis] = null;
        updateCentang(jenis);

        clearTimeout(timerCari[jenis]);

        if (keyword.length < 2) {
            tampilPesan(dropdown, keyword === '' ? null : 'Ketik minimal 2 karakter');
            return;
        }

        timerCari[jenis] = setTimeout(async function () {
            const nomor = ++urutanCari[jenis];
            try {
                const res = await fetch(URL_CARI + '/' + jenis + '?q=' + encodeURIComponent(keyword), {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (nomor !== urutanCari[jenis]) return; // abaikan respons lama
                renderDropdown(jenis, data);
            } catch (e) {
                tampilPesan(dropdown, 'Gagal memuat data');
            }
        }, 250);
    }

    function tampilPesan(dropdown, teks) {
        dropdown.innerHTML = '';
        if (teks === null) {
            dropdown.style.display = 'none';
            return;
        }
        const div = document.createElement('div');
        div.className = 'kb-dropdown-item kb-dropdown-empty';
        div.textContent = teks;
        dropdown.appendChild(div);
        dropdown.style.display = 'block';
    }

    function renderDropdown(jenis, data) {
        const dropdown = document.getElementById(konfig[jenis].dropdown);
        if (!data.length) {
            tampilPesan(dropdown, 'Tidak ditemukan');
            return;
        }
        dropdown.innerHTML = '';
        data.forEach(function (item) {
            const div = document.createElement('div');
            div.className = 'kb-dropdown-item';

            const kode = document.createElement('b');
            kode.textContent = item.kode;
            div.appendChild(kode);
            div.appendChild(document.createTextNode(' — ' + item.nama));

            div.onclick = function () { pilihItem(jenis, item); };
            dropdown.appendChild(div);
        });
        dropdown.style.display = 'block';
    }

    function pilihItem(jenis, item) {
        const k = konfig[jenis];
        pilihan[jenis] = item;
        document.getElementById(k.input).value = item.kode + ' — ' + item.nama;
        document.getElementById(k.dropdown).style.display = 'none';
        updateCentang(jenis);
    }

    // ===== Klik ✓ : masukkan ke daftar di bawah kolom =====
    function tambahDiagnosa(jenis) {
        const k = konfig[jenis];
        const item = pilihan[jenis];

        if (!item) {
            alert('Pilih ' + jenis + ' dari daftar yang muncul terlebih dahulu.');
            return;
        }

        if (diagnosaTerpilih[jenis].some(d => String(d.id) === String(item.id))) {
            alert('Item ini sudah ada di daftar.');
        } else {
            diagnosaTerpilih[jenis].push(item);
        }

        pilihan[jenis] = null;
        document.getElementById(k.input).value = '';
        document.getElementById(k.dropdown).style.display = 'none';
        updateCentang(jenis);
        renderDiagnosa(jenis);
    }

    // ===== Klik ikon hapus : keluarkan item dari daftar =====
    function hapusDiagnosa(jenis, id) {
        diagnosaTerpilih[jenis] = diagnosaTerpilih[jenis].filter(d => String(d.id) !== String(id));
        renderDiagnosa(jenis);
    }

    function renderDiagnosa(jenis) {
        const k = konfig[jenis];
        const list = document.getElementById(k.list);
        list.innerHTML = '';

        diagnosaTerpilih[jenis].forEach(function (item) {
            const baris = document.createElement('div');
            baris.className = 'kb-diagnosa-item';

            const teks = document.createElement('span');
            teks.className = 'kb-diagnosa-text';
            const kode = document.createElement('b');
            kode.textContent = item.kode;
            teks.appendChild(kode);
            teks.appendChild(document.createTextNode(' — ' + item.nama));

            // dikirim saat Simpan (kalau nanti dibungkus <form>)
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = k.name;
            hidden.value = item.id;

            const aksi = document.createElement('span');
            aksi.className = 'kb-diagnosa-aksi';

            const btnHapus = document.createElement('button');
            btnHapus.type = 'button';
            btnHapus.className = 'kb-aksi-hapus';
            btnHapus.title = 'Hapus';
            btnHapus.setAttribute('aria-label', 'Hapus');
            btnHapus.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
            btnHapus.onclick = function () { hapusDiagnosa(jenis, item.id); };

            aksi.appendChild(btnHapus);
            baris.appendChild(teks);
            baris.appendChild(hidden);
            baris.appendChild(aksi);
            list.appendChild(baris);
        });
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.kb-search-box')) {
            const dp = document.getElementById('dropdownPenyakit');
            const dt = document.getElementById('dropdownTindakan');
            if (dp) dp.style.display = 'none';
            if (dt) dt.style.display = 'none';
        }
    });
</script>
@endsection