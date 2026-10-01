@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Syaraf')
@section('header-icon', '🩺')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/syaraf-kunjungan-baru.css') }}">

    {{-- Kotak Assesment: Data Pasien, Tanda Vital, SOAP, Diagnosa --}}
    <style>
        :root {
            --kb-merah: #b81d24;
            --kb-hijau: #1e9e4a;
            --kb-garis: #d9dce1;
            --kb-teks-samar: #8a8f98;
        }

        /* ===== Data Pasien ===== */
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
           9 kolom: [label | isian | satuan] x 3. Label & satuan selebar isinya,
           sisa lebar dibagi rata ke 3 kolom isian (panjang menyesuaikan layar). */
        .kb-vitals-box {
            padding: 12px 14px 12px 18px;
            border: 1px solid var(--kb-garis);
            border-radius: 10px;
            background: #fff;
            box-sizing: border-box;
        }
        .kb-vitals-box .kb-vitals {
            display: grid;
            grid-template-columns:
                max-content minmax(0, 1fr) max-content
                max-content minmax(0, 1fr) max-content
                max-content minmax(0, 1fr) max-content;
            column-gap: 6px;
            row-gap: 10px;
            align-items: center;
        }
        .kb-vitals .kb-vital-row {
<<<<<<< HEAD
            display: contents;
=======
            display: grid;
            grid-template-columns: 56px minmax(0, 1fr) 64px;
            align-items: center;
            gap: 10px;
            margin: 0;
>>>>>>> 2f12c5c9ed13830015f622c52813c7b44f086c39
        }
        .kb-vitals .kb-vital-row label {
            font-weight: 600;
            margin: 0;
            white-space: nowrap;
            box-sizing: border-box;
            padding-right: 4px;
        }
        /* jarak antar kelompok kolom (kolom ke-2 dan ke-3) */
        .kb-vitals .kb-vital-row:nth-child(3n+2) label,
        .kb-vitals .kb-vital-row:nth-child(3n) label {
            padding-left: 14px;
        }
        .kb-vitals .kb-vital-row input[type=text] {
            width: 100%;
            min-width: 0;
            height: 34px;
            box-sizing: border-box;
            padding: 0 10px;
            font-size: 14px;
            font-family: inherit;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
        }
        .kb-vitals .kb-vital-row input[type=text]:focus {
            outline: none;
            border-color: var(--kb-merah);
            box-shadow: 0 0 0 3px rgba(184, 29, 36, 0.12);
        }
        .kb-vitals .kb-vital-unit {
            color: var(--kb-teks-samar);
            font-size: 13px;
            white-space: nowrap;
            margin: 0;
        }

<<<<<<< HEAD
        /* Layar sedang: 2 kelompok kolom */
        @media (max-width: 1000px) {
            .kb-vitals-box .kb-vitals {
                grid-template-columns:
                    max-content minmax(0, 1fr) max-content
                    max-content minmax(0, 1fr) max-content;
            }
            .kb-vitals .kb-vital-row label { padding-left: 0; }
            .kb-vitals .kb-vital-row:nth-child(even) label { padding-left: 14px; }
        }
        /* Layar kecil: 1 kelompok kolom */
        @media (max-width: 640px) {
            .kb-vitals-box .kb-vitals {
                grid-template-columns: max-content minmax(0, 1fr) max-content;
            }
            .kb-vitals .kb-vital-row label,
            .kb-vitals .kb-vital-row:nth-child(even) label { padding-left: 0; }
        }

        /* Checkbox Alergi: sama dengan kotak centang Penyakit/Tindakan */
=======
        /* Checkbox Lainnya */
>>>>>>> 2f12c5c9ed13830015f622c52813c7b44f086c39
        .kb-vital-row .kb-check-box {
            justify-self: start;
            flex: 0 0 auto;
            width: 34px;
            height: 34px;
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
            padding-right: 110px;
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
    </style>

    {{-- Area cetak resep --}}
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        @media screen {
            .cetak-resep-only { display: none; }
        }
        @media print {
            body.cetak-resep-mode > *:not(#cetak-resep-area) {
                display: none !important;
            }
            html, body.cetak-resep-mode {
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
            body.cetak-resep-mode #cetak-resep-area * {
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
            .cetak-header-text h2 { margin: 0; font-size: 18px; }
            .cetak-header-text p { margin: 3px 0; font-size: 12px; }
            .cetak-info-table {
                width: 100%;
                font-size: 12px;
                margin-bottom: 10px;
            }
            .cetak-info-table td { padding: 3px 0; vertical-align: top; }
            .cetak-divider { border-bottom: 2px solid #000; margin-bottom: 15px; }
            .cetak-title { text-align: center; font-weight: bold; font-size: 16px; margin-bottom: 20px; }
            .cetak-body { display: flex; gap: 15px; min-height: 250px; font-size: 14px; }
            .resep-rp { font-weight: bold; font-size: 18px; margin: 0; }
            .resep-isi { white-space: pre-wrap; flex-grow: 1; line-height: 1.5; min-height: 0; padding: 0; }
            .cetak-footer { margin-top: 20px; text-align: right; font-size: 12px; }
            .cetak-footer p { margin: 2px 0; }
            .cetak-signature { margin-top: 70px; }
        }
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
    </div>

    {{-- ===== AREA CETAK RESEP ===== --}}
    <div id="cetak-resep-area" class="cetak-resep-only">
        <div class="cetak-header">
            <img src="{{ asset('images/logo.png') }}" class="cetak-logo" alt="Logo Klinik">
            <div class="cetak-header-text">
                <h2>KLINIK RAWAT INAP MERAH PUTIH</h2>
                <p>Jl. Ronggo Warsito No. 98 A, Ngawi</p>
                <p>Telp: (0351) 745596</p>
            </div>
        </div>

        <table class="cetak-info-table">
            <tr><td width="15%">Nama Pasien</td><td width="2%">:</td><td width="83%">{{ $pasien->nama_pasien ?? '-' }}</td></tr>
            <tr><td>No. R.M.</td><td>:</td><td>{{ $pasien->no_rm ?? '-' }}</td></tr>
            <tr><td>Umur / JK</td><td>:</td><td>{{ $pasien->umur ?? '-' }} / {{ $pasien->jenis_kelamin ?? '-' }}</td></tr>
            <tr><td>Pemberi Resep</td><td>:</td><td>Dokter Poli Syaraf</td></tr>
        </table>

        <div class="cetak-divider"></div>
        <div class="cetak-title">RESEP</div>

        <div class="cetak-body">
            <div class="resep-rp">R/</div>
            <div class="resep-isi" id="resep-isi-plan"></div>
        </div>

        <div class="cetak-footer">
            <p>Ngawi, <span id="resep-tanggal"></span></p>
            <div class="cetak-signature">
                <p>dr. Poli Syaraf</p>
            </div>
        </div>
    </div>

    {{-- TAB 1: ASSESMENT --}}
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

    {{-- TAB 2: CLINICAL PATHWAY (Tabel Lengkap yang bisa di-scroll) --}}
    <div id="tab-pathway" class="kb-tab-content">
        <div class="card shadow-sm border-danger mb-4 rounded-3 overflow-hidden">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-bordered mb-0 align-middle">
                    <thead class="text-white text-center" style="background-color: #a31515;">
                        <tr>
                            <th style="width: 25%; color: white !important;">Aktivitas Pelayanan</th>
                            <th style="width: 45%; color: white !important;">Keterangan</th>
                            <th style="width: 15%; color: white !important;">Waktu</th>
                            <th style="width: 15%; color: white !important;">Tarif</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Diagnosa -->
                        <tr>
                            <td class="fw-bold bg-light">
                                Diagnosa
                                <div class="fw-normal small ms-2 text-muted">
                                    <div class="my-1">Dx Utama</div>
                                    <div class="my-1">Dx Sekunder</div>
                                    <div class="my-1">Dx Banding</div>
                                </div>
                            </td>
                            <td>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                            </td>
                            <td></td>
                            <td></td>
                        </tr>

                        <!-- Asesmen Klinis -->
                        <tr>
                            <td class="fw-bold bg-light">Asesmen Klinis</td>
                            <td><textarea class="form-control form-control-sm" rows="2"></textarea></td>
                            <td>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                            </td>
                            <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                        </tr>

                        <!-- Pemeriksaan Fisik -->
                        <tr>
                            <td class="fw-bold bg-light">Pemeriksaan Fisik</td>
                            <td>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Pemeriksaan tanda vital</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Inspeksi postur tulang belakang dan gerakan aktif volumna vertebralis</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Pemeriksaan motorik, reflek, dan sensorik dermatom</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                            </td>
                            <td>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                            </td>
                            <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                        </tr>

                        <!-- Pemeriksaan Penunjang -->
                        <tr>
                            <td class="fw-bold bg-light">Pemeriksaan Penunjang</td>
                            <td>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Magnetic Resonance Imaging (MRI)</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Computerized Tomography (CT Scan)</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Foto polos lumbosakral (rontgen / X-ray)</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                            </td>
                            <td>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                            </td>
                            <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                        </tr>

                        <!-- Farmakologis -->
                        <tr>
                            <td class="fw-bold bg-light">Farmakologis</td>
                            <td>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Antipiretik</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Analgesik Adjuvan</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> NSAID oral</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Muscle Relaxant</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Cairan IV kristaloid</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                            </td>
                            <td>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                            </td>
                            <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                        </tr>

                        <!-- Fisioterapi -->
                        <tr>
                            <td class="fw-bold bg-light">Fisioterapi</td>
                            <td>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Terapi lampu hangat (Infra Red)</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Stimulasi Listrik (TENS)</label></div>
                            </td>
                            <td>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                            </td>
                            <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                        </tr>

                        <!-- Edukasi -->
                        <tr>
                            <td class="fw-bold bg-light">Edukasi</td>
                            <td>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi menjaga postur tubuh yang benar saat duduk dan berdiri</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi olahraga yang menguatkan tulang belakang</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi angkat beban berat</label></div>
                                <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi menjaga berat badan ideal</label></div>
                                <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                            </td>
                            <td>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                            </td>
                            <td></td>
                        </tr>

                        <!-- Variasi Pelayanan -->
                        <tr>
                            <td class="fw-bold bg-light">Variasi Pelayanan</td>
                            <td><textarea class="form-control form-control-sm" rows="2"></textarea></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <!-- Total -->
                        <tr>
                            <td class="fw-bold bg-light border-bottom-0">Total</td>
                            <td class="border-bottom-0"></td>
                            <td class="border-bottom-0"></td>
                            <td class="fw-bold border-bottom-0">
                                <span class="text-muted small">Rp</span> 
                                <input type="text" class="form-control form-control-sm d-inline-block w-75 fw-bold bg-light" readonly>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-end mb-4">
            <button type="button" class="btn btn-danger btn-lg px-5 py-2 fw-bold shadow-sm" style="background-color: #b81d24; border: none; border-radius: 8px;">
                <i class="fa-solid fa-print me-2"></i> Cetak
            </button>
        </div>
    </div>

@endsection

@section('extra-js')
<script>
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

        document.body.appendChild(area);
        document.body.classList.add('cetak-resep-mode');

        window.print();
    }

    window.addEventListener('afterprint', function () {
        document.body.classList.remove('cetak-resep-mode');
    });

    function simpanKunjunganBaru() {
        alert('Data kunjungan baru ini belum tersimpan ke database — masih dummy front-end. Beri tahu saya kalau mau disambungkan ke tabel kunjungan.');
    }

<<<<<<< HEAD
=======
    function rujukPasien() {
        alert('Fitur Rujuk belum tersambung ke database — masih dummy front-end.');
    }

    // Teks tanda vital yang terakhir dimasukkan otomatis ke Objective
>>>>>>> 0c4ba4fdcc7c212ea42cb70f1abad07eae65712c
    let vitalTerakhir = '';


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

    function hapusVitalDariObjektif() {
        const objektif = document.querySelector('textarea[name="objective"]');
        if (vitalTerakhir && objektif.value.startsWith(vitalTerakhir)) {
            objektif.value = objektif.value.slice(vitalTerakhir.length).replace(/^\n/, '');
        }
        vitalTerakhir = '';
    }

    function terapkanVitalKeObjektif(teks) {
        const objektif = document.querySelector('textarea[name="objective"]');
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

    document.querySelectorAll('.kb-vitals input[type=text]').forEach(function (input) {
        input.addEventListener('input', function () {
            if (!document.getElementById('chkVital').checked) return;
            const teks = bangunTeksVital();
            if (teks === '') {
                hapusVitalDariObjektif();
            } else {
                terapkanVitalKeObjektif(teks);
            }
        });
    });

    const URL_CARI = "{{ url('/cari-kode') }}";
    const konfig = {
        penyakit: { input: 'cariPenyakit', dropdown: 'dropdownPenyakit', icon: 'checkPenyakit', list: 'listPenyakit', name: 'penyakit_id[]' },
        tindakan: { input: 'cariTindakan', dropdown: 'dropdownTindakan', icon: 'checkTindakan', list: 'listTindakan', name: 'tindakan_id[]' },
    };

    const pilihan = { penyakit: null, tindakan: null };
    const diagnosaTerpilih = { penyakit: [], tindakan: [] };
    const timerCari = {};
    const urutanCari = { penyakit: 0, tindakan: 0 };

    function updateCentang(jenis) {
        document.getElementById(konfig[jenis].icon).classList.toggle('aktif', pilihan[jenis] !== null);
    }

    function cariItem(jenis) {
        const k = konfig[jenis];
        const input = document.getElementById(k.input);
        const dropdown = document.getElementById(k.dropdown);
        const keyword = input.value.trim();

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
                if (nomor !== urutanCari[jenis]) return;
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

<<<<<<< HEAD
    function gantiDiagnosa(jenis, id) {
        const item = diagnosaTerpilih[jenis].find(d => String(d.id) === String(id));
        if (!item) return;

        hapusDiagnosa(jenis, id);
        const input = document.getElementById(konfig[jenis].input);
        input.value = item.nama;
        input.focus();
        cariItem(jenis);
    }

=======
>>>>>>> 0c4ba4fdcc7c212ea42cb70f1abad07eae65712c
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

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = k.name;
            hidden.value = item.id;

            const aksi = document.createElement('span');
            aksi.className = 'kb-diagnosa-aksi';

<<<<<<< HEAD
            const btnGanti = document.createElement('button');
            btnGanti.type = 'button';
            btnGanti.className = 'kb-aksi-ganti';
            btnGanti.title = 'Ganti';
            btnGanti.innerHTML = '<i class="fa-solid fa-pen"></i>';
            btnGanti.onclick = function () { gantiDiagnosa(jenis, item.id); };

=======
>>>>>>> 0c4ba4fdcc7c212ea42cb70f1abad07eae65712c
            const btnHapus = document.createElement('button');
            btnHapus.type = 'button';
            btnHapus.className = 'kb-aksi-hapus';
            btnHapus.title = 'Hapus';
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