@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Obgyn')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/obgyn-kunjungan-baru.css') }}?v={{ @filemtime(public_path('css/obgyn-kunjungan-baru.css')) }}">

    {{-- Ukuran kolom SOAP, tombol Cetak di kolom Plan, dan daftar diagnosa di bawah kolom --}}
    <style>
        .ob-soap-box {
            gap: 16px;
            padding: 18px 20px;
        }
        .ob-soap-box .ob-soap-row {
            gap: 12px;
        }
        .ob-soap-box .ob-soap-badge {
            width: 34px;
            height: 34px;
            font-size: 15px;
        }
        .ob-soap-box .ob-soap-row textarea {
            min-height: 110px;
            padding: 12px 16px;
            font-size: 15px;
            line-height: 1.5;
        }

        .ob-plan-wrap {
            position: relative;
            flex: 1;
            min-width: 0;
        }
        .ob-plan-wrap textarea[name="plan"] {
            width: 100%;
            box-sizing: border-box;
            padding-right: 110px; /* ruang untuk tombol Cetak */
        }
        .ob-plan-wrap .ob-cetak-plan {
            position: absolute;
            top: 50%;
            right: 14px;
            bottom: auto;
            margin: 0;
            transform: translateY(-50%);
            white-space: nowrap;
        }

        /* Diagnosa: daftar hasil tampil di bawah kolom */
        .ob-diagnosa-row {
            align-items: start;
        }
        .ob-diagnosa-row .ob-soap-actions {
            align-self: start;
            margin-top: 26px;
        }
        .ob-diagnosa-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: 10px;
        }
        .ob-diagnosa-item {
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
        .ob-diagnosa-aksi {
            display: flex;
            gap: 4px;
            flex: 0 0 auto;
        }
        .ob-diagnosa-aksi button {
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
        .ob-aksi-hapus { color: #b81d24; }
        .ob-aksi-hapus:hover { background: rgba(184, 29, 36, 0.14); }

        /* Tombol Rujuk di baris bawah (di samping Simpan) */
        .ob-actions .ob-btn-rujuk {
            color: #b81d24;
            border: 1px solid #b81d24;
            background: #fff;
        }
        .ob-actions .ob-btn-rujuk:hover {
            background: #b81d24;
            color: #fff;
        }

        /* ===== Data Pasien: 2 kolom sama lebar, label & isi sejajar ===== */
        .ob-card .ob-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 40px;
        }
        .ob-card .ob-fields .ob-field {
            display: grid;
            grid-template-columns: 150px minmax(0, 1fr);
            align-items: center;
            gap: 12px;
            margin: 0;
        }
        .ob-card .ob-fields .ob-label {
            font-weight: 600;
        }
        .ob-card .ob-fields .ob-value {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 42px;
            box-sizing: border-box;
            padding: 0 14px;
            border: 1px solid #d9dce1;
            border-radius: 6px;
            background: #fff;
        }
        @media (max-width: 900px) {
            .ob-card .ob-fields { grid-template-columns: 1fr; }
        }

        /* ===== Kotak Tanda Vital: 2 baris x 4 kolom =====
           12 kolom: [label | isian | satuan] x 4. Label & satuan selebar isinya,
           sisa lebar dibagi rata ke 4 kolom isian (sejajar & mentok ke tepi kanan). */
        .ob-vitals-box {
            padding: 12px 14px 12px 18px;
            border: 1px solid #d9dce1;
            border-radius: 10px;
            background: #fff;
            box-sizing: border-box;
        }
        #tab-assessment .ob-vitals-box .ob-vitals-grid {
            display: grid !important;
            grid-template-columns:
                max-content minmax(0, 1fr) max-content
                max-content minmax(0, 1fr) max-content
                max-content minmax(0, 1fr) max-content
                max-content minmax(0, 1fr) max-content !important;
            column-gap: 6px !important;
            row-gap: 10px !important;
            align-items: center;
        }
        #tab-assessment .ob-vitals-grid .ob-vital-row {
            display: contents !important;
        }
        #tab-assessment .ob-vitals-grid .ob-vital-row label {
            font-weight: 600;
            margin: 0 !important;
            width: auto !important;
            min-width: 0 !important;
            white-space: nowrap;
            box-sizing: border-box;
            padding-right: 4px;
        }
        /* jarak antar kelompok kolom (kolom ke-2, 3, 4) */
        #tab-assessment .ob-vitals-grid .ob-vital-row:nth-child(4n+2) label,
        #tab-assessment .ob-vitals-grid .ob-vital-row:nth-child(4n+3) label,
        #tab-assessment .ob-vitals-grid .ob-vital-row:nth-child(4n) label {
            padding-left: 14px;
        }
        #tab-assessment .ob-vitals-grid .ob-vital-row input {
            width: 100% !important;
            max-width: none !important;
            min-width: 0 !important;
            flex: none !important;
            height: 34px;
            box-sizing: border-box;
            padding: 0 10px;
            font-size: 14px;
            font-family: inherit;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
        }
        #tab-assessment .ob-vitals-grid .ob-vital-row input[type=date] {
            padding: 0 8px;
            cursor: pointer;
            color: #333;
        }
        #tab-assessment .ob-vitals-grid .ob-vital-row input:focus {
            outline: none;
            border-color: #b81d24;
            box-shadow: 0 0 0 3px rgba(184, 29, 36, 0.12);
        }
        #tab-assessment .ob-vitals-grid .ob-vital-unit {
            color: #8a8f98;
            font-size: 13px;
            white-space: nowrap;
            width: auto !important;
            min-width: 0 !important;
            margin: 0 !important;
        }
        #tab-assessment .ob-vitals-grid .ob-vital-row .ob-vital-check {
            justify-self: start;
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
        .ob-vitals-grid .ob-vital-check .ob-check-icon {
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
        .ob-vitals-grid .ob-vital-check .ob-check-icon.aktif {
            background: #1e9e4a;
            border-color: #1e9e4a;
            color: #fff;
        }

        /* Layar sedang: 2 kelompok kolom */
        @media (max-width: 1100px) {
            #tab-assessment .ob-vitals-box .ob-vitals-grid {
                grid-template-columns:
                    max-content minmax(0, 1fr) max-content
                    max-content minmax(0, 1fr) max-content !important;
            }
            #tab-assessment .ob-vitals-grid .ob-vital-row label { padding-left: 0; }
            #tab-assessment .ob-vitals-grid .ob-vital-row:nth-child(even) label { padding-left: 14px; }
        }
        /* Layar kecil: 1 kelompok kolom */
        @media (max-width: 640px) {
            #tab-assessment .ob-vitals-box .ob-vitals-grid {
                grid-template-columns: max-content minmax(0, 1fr) max-content !important;
            }
            #tab-assessment .ob-vitals-grid .ob-vital-row label,
            #tab-assessment .ob-vitals-grid .ob-vital-row:nth-child(even) label { padding-left: 0; }
        }
    </style>
@endsection

@section('content')

    @php
        $semuaPasienObgyn = $semuaPasienObgyn ?? [
            (object)['no_rm'=>'RM-0002','nama_pasien'=>'Siti Aminah','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Surabaya','tgl_lahir'=>'1985-05-12','umur'=>'41 Tahun','no_hp'=>'081355667788'],
            (object)['no_rm'=>'RM-0004','nama_pasien'=>'Dewi Lestari','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Surabaya','tgl_lahir'=>'1992-07-05','umur'=>'34 Tahun','no_hp'=>'082233445566'],
            (object)['no_rm'=>'RM-0006','nama_pasien'=>'Lina Marlina','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1980-08-15','umur'=>'46 Tahun','no_hp'=>'081366778899'],
            (object)['no_rm'=>'RM-0008','nama_pasien'=>'Nur Aisyah','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Gresik','tgl_lahir'=>'1984-03-02','umur'=>'42 Tahun','no_hp'=>'083855667788'],
            (object)['no_rm'=>'RM-0010','nama_pasien'=>'Sri Wahyuni','jenis_kelamin'=>'Perempuan','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1975-07-18','umur'=>'51 Tahun','no_hp'=>'081298112233'],
        ];

        $pasien = collect($semuaPasienObgyn)->firstWhere('no_rm', $no_rm ?? request('no_rm'));

        // Data untuk field Vital Signs: [label, name, unit, placeholder]
        $vitalFields = [
            ['TD', 'td', 'mmHg', 'mis. 120/80'],
            ['HR', 'hr', 'x/menit', 'mis. 88'],
            ['SpO2', 'spo2', '%', 'mis. 98'],
            ['Suhu', 'suhu', '°C', 'mis. 36.5'],
            ['RR', 'rr', 'x/menit', 'mis. 20'],
            ['HPHT', 'hpht', '', 'hh-bb-tttt'],
            ['UK', 'uk', 'minggu', 'mis. 12'],
        ];

        // Data untuk baris SOAP: [kode, name, tipe, placeholder]
        $soapRows = [
            ['S', 'subjective', 'textarea', 'Subjektif...'],
            ['O', 'objective', 'textarea', 'Hasil pemeriksaan objektif...'],
            ['A', 'assessment', 'textarea', 'Assessment / analisa...'],
            ['P', 'plan', 'textarea', 'Rencana/plan...'],
        ];

        // Data untuk kolom pencarian Diagnosa: [label, placeholder]
        $diagnosaFields = [
            ['Penyakit', 'Cari penyakit...'],
            ['Tindakan', 'Cari tindakan...'],
        ];

        // Urutan checklist radiologi mengikuti tata letak 2 kolom (kiri-kanan, atas-bawah)
        $radiologiOptions = ['Foto Thoraks', 'MRI', 'USG Abdomen', 'Rontgen', 'CT Scan'];
    @endphp

    {{-- ===== DATA PASIEN ===== --}}
    <div class="ob-card">
        <div class="ob-section-title">Data Pasien</div>

        @if ($pasien)
            @php
                $dataPasien = [
                    'No RM' => $pasien->no_rm,
                    'Tempat, Tgl Lahir' => $pasien->tempat_lahir . ', ' . \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d'),
                    'Nama' => $pasien->nama_pasien,
                    'Umur' => $pasien->umur,
                    'Jenis Kelamin' => $pasien->jenis_kelamin,
                    'No Hp' => $pasien->no_hp,
                ];
            @endphp
            <div class="ob-fields">
                @foreach ($dataPasien as $label => $value)
                    <div class="ob-field">
                        <span class="ob-label">{{ $label }}</span>
                        <span class="ob-value">{{ $value }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color:#888;">Data pasien tidak ditemukan.</p>
        @endif
    </div>

    {{-- ===== TAB ===== --}}
    <div class="ob-tabs">
        <div class="ob-tab active" id="ob-tab-btn-assessment" onclick="gantiTab('assessment', event)">
            <i class="fa-solid fa-clipboard-list"></i> Assessment
        </div>
        <div class="ob-tab" id="ob-tab-btn-radiologi" onclick="gantiTab('radiologi', event)">
            <i class="fa-solid fa-x-ray"></i> Radiologi
        </div>
    </div>

    {{-- ===== AREA CETAK RESEP (tersembunyi, hanya muncul saat print, hasil 1 lembar) ===== --}}
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
            /* Saat mode cetak, semua elemen lain dihilangkan total agar tidak menambah halaman */
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
        }
    </style>

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
                <td>Pemberi Resep</td><td>:</td><td>Dokter Poli Obgyn</td>
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
                <p>dr. Poli Obgyn</p>
            </div>
        </div>
    </div>

    {{-- ===== TAB ASSESSMENT ===== --}}
    <div id="tab-assessment" class="ob-tab-content active">

        <div class="ob-vitals-box">
            <div class="ob-vitals-grid">
                @foreach ($vitalFields as [$label, $name, $unit, $placeholder])
                    <div class="ob-vital-row">
                        <label>{{ $label }}</label>
                        @if ($name === 'hpht')
                            <input type="date" name="{{ $name }}" max="{{ date('Y-m-d') }}" title="Pilih tanggal HPHT">
                        @else
                            <input type="text" name="{{ $name }}" placeholder="{{ $placeholder }}">
                        @endif
                        <span class="ob-vital-unit">{{ $unit }}</span>
                    </div>
                @endforeach
                <div class="ob-vital-row">
                    <label>Alergi</label>
                    <input type="text" name="alergi" placeholder="mis. tidak ada">
                    <div class="ob-vital-check">
                        <span class="ob-check-icon" id="btnCentangVital" role="button" title="Masukkan tanda vital ke Objective" onclick="isiObjektifDariVital()">✓</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="ob-divider">

        {{-- ===== SOAP ===== --}}
        <div class="ob-soap-title">SOAP</div>
        <div class="ob-soap-box">
            @foreach ($soapRows as [$kode, $name, $tipe, $placeholder])
                <div class="ob-soap-row">
                    <div class="ob-soap-badge">{{ $kode }}</div>
                    @if ($tipe === 'input')
                        <input type="text" name="{{ $name }}" placeholder="{{ $placeholder }}">
                    @elseif ($name === 'plan')
                        {{-- Plan: tombol Cetak berada di dalam kolom --}}
                        <div class="ob-plan-wrap">
                            <textarea name="{{ $name }}" rows="4" placeholder="{{ $placeholder }}"></textarea>
                            <button type="button" class="ob-cetak-btn ob-cetak-plan" onclick="cetakResep()">
                                <i class="fa-solid fa-print"></i> Cetak
                            </button>
                        </div>
                    @else
                        <textarea name="{{ $name }}" rows="4" placeholder="{{ $placeholder }}"></textarea>
                    @endif
                </div>
            @endforeach
        </div>

        <hr class="ob-divider">

        {{-- ===== DIAGNOSA ===== --}}
        <div class="ob-diagnosa-title">Diagnosa</div>
        <div class="ob-diagnosa-box">

            <div class="ob-diagnosa-row">
                @foreach ($diagnosaFields as [$label, $placeholder])
                    @php $jenis = strtolower($label); @endphp
                    <div class="ob-search-field">
                        <label>{{ $label }}</label>
                        <div class="ob-search-inline">
                            <div class="ob-search-box">
                                <input type="text" id="cari{{ $label }}" placeholder="{{ $placeholder }}"
                                       autocomplete="off"
                                       oninput="cariItem('{{ $jenis }}')"
                                       onfocus="cariItem('{{ $jenis }}')"
                                       onkeydown="if (event.key === 'Enter') { event.preventDefault(); tambahDiagnosa('{{ $jenis }}'); }">
                                <span class="ob-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <div class="ob-dropdown" id="dropdown{{ $label }}"></div>
                            </div>
                            <div class="ob-check-box">
                                <span class="ob-check-icon" id="check{{ $label }}" role="button"
                                      title="Tambahkan {{ strtolower($label) }} ke daftar"
                                      onclick="tambahDiagnosa('{{ $jenis }}')">✓</span>
                            </div>
                        </div>
                        <div class="ob-diagnosa-list" id="list{{ $label }}"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ob-actions">
            <button type="button" class="ob-btn ob-btn-reset ob-btn-rujuk" onclick="rujukKeRadiologi()">
                <i class="fa-solid fa-right-from-bracket"></i> Rujuk
            </button>
            <button type="button" class="ob-btn ob-btn-simpan" onclick="simpanKunjunganBaru()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
        </div>
    </div>

    {{-- ===== TAB RADIOLOGI ===== --}}
    <div id="tab-radiologi" class="ob-tab-content">
        <div class="ob-radio-card">
            <div class="ob-radio-title">Form Pemeriksaan</div>
            <div class="ob-radio-sub">Ceklist Periksa Radiologi</div>

            <div class="ob-checklist-grid">
                @foreach ($radiologiOptions as $opt)
                    <label class="ob-checklist-item">
                        <input type="checkbox" name="radiologi[]" value="{{ $opt }}"> {{ $opt }}
                    </label>
                @endforeach
                <label class="ob-checklist-item ob-checklist-lainnya">
                    <input type="checkbox" id="checkRadiologiLainnya" name="radiologi[]" value="Lainnya"> Lainnya
                    <input type="text" id="radiologiLainnya" name="radiologi_lainnya" placeholder="(tuliskan)" disabled>
                </label>
            </div>

            <div class="ob-radio-sub">Scan Hasil (jika perlu)</div>
            <div class="ob-upload-box" onclick="document.getElementById('scanHasil').click()">
                Klik untuk mengunggah gambar/scan
                <div class="ob-upload-hint">(format: JPG, PNG, PDF &nbsp; Maks. 10 MB)</div>
                <input type="file" id="scanHasil" name="scan_hasil" accept=".jpg,.jpeg,.png,.pdf" style="display:none;">
            </div>

            <div class="ob-radio-sub">Hasil Pemeriksaan Radiologi</div>
            <textarea class="ob-hasil-textarea" name="hasil_radiologi" rows="5" placeholder="Masukkan hasil pemeriksaan radiologi di sini"></textarea>
        </div>

        <div class="ob-actions">
            <button type="button" class="ob-btn ob-btn-simpan" onclick="simpanKunjunganBaru()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
            <button type="button" class="ob-btn ob-btn-reset" onclick="resetForm()">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </button>
        </div>
    </div>

@endsection

@section('extra-js')
<script>
    function gantiTab(tab, event) {
        document.querySelectorAll('.ob-tab').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.ob-tab-content').forEach(el => el.classList.remove('active'));
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

    // Kembalikan tampilan normal setelah dialog cetak ditutup
    window.addEventListener('afterprint', function () {
        document.body.classList.remove('cetak-resep-mode');
    });

    function rujukKeRadiologi() {
        document.querySelectorAll('.ob-tab').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.ob-tab-content').forEach(el => el.classList.remove('active'));
        document.getElementById('ob-tab-btn-radiologi').classList.add('active');
        document.getElementById('tab-radiologi').classList.add('active');
    }

    function simpanKunjunganBaru() {
        alert('Data kunjungan baru ini belum tersimpan ke database — masih dummy front-end.');
    }

    let vitalTerakhir = '';

    function resetForm() {
        document.querySelectorAll('#tab-assessment input[type=text], #tab-assessment input[type=date], #tab-assessment textarea')
            .forEach(el => el.value = '');
        vitalTerakhir = '';
        updateCentangVital();
        diagnosaTerpilih.penyakit = [];
        diagnosaTerpilih.tindakan = [];
        pilihan.penyakit = null;
        pilihan.tindakan = null;
        renderDiagnosa('penyakit');
        renderDiagnosa('tindakan');
        updateCentang('penyakit');
        updateCentang('tindakan');
        document.getElementById('dropdownPenyakit').style.display = 'none';
        document.getElementById('dropdownTindakan').style.display = 'none';
    }

    // Centang TTV hijau jika minimal satu tanda vital terisi
    function updateCentangVital() {
        const adaIsi = Array.from(document.querySelectorAll('.ob-vitals-grid .ob-vital-row input'))
            .some(el => el.value.trim() !== '');
        document.getElementById('btnCentangVital').classList.toggle('aktif', adaIsi);
    }

    document.querySelectorAll('.ob-vitals-grid .ob-vital-row input').forEach(function (el) {
        el.addEventListener('input', updateCentangVital);
    });

    function isiObjektifDariVital() {
        const bagian = [];

        document.querySelectorAll('.ob-vitals-grid .ob-vital-row').forEach(function (row) {
            const label = row.querySelector('label').textContent.trim();
            const inputEl = row.querySelector('input');
            let nilai = inputEl.value.trim();
            if (inputEl.type === 'date' && nilai !== '') {
                nilai = nilai.split('-').reverse().join('-'); // yyyy-mm-dd -> dd-mm-yyyy
            }
            const unitEl = row.querySelector('.ob-vital-unit');
            const unit = unitEl ? unitEl.textContent.trim() : '';

            if (nilai !== '') {
                bagian.push(label + ': ' + nilai + (unit ? ' ' + unit : ''));
            }
        });

        if (bagian.length === 0) {
            alert('Isi minimal satu tanda vital terlebih dahulu.');
            return;
        }

        const teks = bagian.join(', ');
        const objektif = document.querySelector('textarea[name="objective"]');

        // Jika sebelumnya sudah pernah diisi otomatis, ganti bagian itu saja
        // agar tulisan manual di bawahnya tidak hilang dan tidak terduplikasi.
        let sisa = objektif.value;
        if (vitalTerakhir && sisa.startsWith(vitalTerakhir)) {
            sisa = sisa.slice(vitalTerakhir.length).replace(/^\n/, '');
        }

        objektif.value = sisa ? teks + '\n' + sisa : teks;
        vitalTerakhir = teks;

        objektif.focus();
    }

    // ===== Pencarian ICD dari database (kode_diagnosis & kode_tindakan) =====
    // Memakai route /cari-kode/{penyakit|tindakan} yang sama dengan Poli Syaraf.
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

    // Centang hijau menyala kalau ada item yang dipilih dari dropdown
    function updateCentang(jenis) {
        document.getElementById(konfig[jenis].icon).classList.toggle('aktif', pilihan[jenis] !== null);
    }

    function cariItem(jenis) {
        const k = konfig[jenis];
        const input = document.getElementById(k.input);
        const dropdown = document.getElementById(k.dropdown);
        const keyword = input.value.trim();

        // Mengetik lagi = membatalkan pilihan sebelumnya
        // (kecuali teks masih sama dengan item yang dipilih, mis. saat fokus ulang)
        if (pilihan[jenis] && input.value === pilihan[jenis].kode + ' — ' + pilihan[jenis].nama) {
            return;
        }
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
        div.className = 'ob-dropdown-item ob-dropdown-empty';
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
            div.className = 'ob-dropdown-item';

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

    function renderDiagnosa(jenis) {
        const k = konfig[jenis];
        const list = document.getElementById(k.list);
        list.innerHTML = '';

        diagnosaTerpilih[jenis].forEach(function (item) {
            const baris = document.createElement('div');
            baris.className = 'ob-diagnosa-item';

            const teks = document.createElement('span');
            const kode = document.createElement('b');
            kode.textContent = item.kode;
            teks.appendChild(kode);
            teks.appendChild(document.createTextNode(' — ' + item.nama));
            baris.appendChild(teks);

            // dikirim saat Simpan (kalau nanti dibungkus <form>)
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = k.name;
            hidden.value = item.id;
            baris.appendChild(hidden);

            // tombol hapus
            const aksi = document.createElement('span');
            aksi.className = 'ob-diagnosa-aksi';

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'ob-aksi-hapus';
            btn.title = 'Hapus';
            btn.setAttribute('aria-label', 'Hapus');
            btn.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
            btn.onclick = function () { hapusDiagnosa(jenis, item.id); };
            aksi.appendChild(btn);
            baris.appendChild(aksi);

            list.appendChild(baris);
        });
    }

    // ===== Klik ikon hapus : keluarkan item dari daftar =====
    function hapusDiagnosa(jenis, id) {
        diagnosaTerpilih[jenis] = diagnosaTerpilih[jenis].filter(d => String(d.id) !== String(id));
        renderDiagnosa(jenis);
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.ob-search-box')) {
            const dp = document.getElementById('dropdownPenyakit');
            const dt = document.getElementById('dropdownTindakan');
            if (dp) dp.style.display = 'none';
            if (dt) dt.style.display = 'none';
        }
    });

    const checkRadiologiLainnya = document.getElementById('checkRadiologiLainnya');
    const radiologiLainnya = document.getElementById('radiologiLainnya');

    if (checkRadiologiLainnya) {
        checkRadiologiLainnya.addEventListener('change', function () {
            radiologiLainnya.disabled = !this.checked;
            if (!this.checked) radiologiLainnya.value = '';
        });
    }
</script>
@endsection