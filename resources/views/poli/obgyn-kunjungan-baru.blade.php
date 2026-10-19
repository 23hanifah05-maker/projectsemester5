@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Obgyn')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/obgyn-kunjungan-baru.css') }}?v={{ @filemtime(public_path('css/obgyn-kunjungan-baru.css')) }}">

    <style>
        .ob-ttd-wrap {
            display: flex;
            gap: 24px;
            margin: 24px 0 8px;
            padding: 20px;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
        }

        .ob-ttd-item {
            flex: 1;
            text-align: center;
        }

        .ob-ttd-judul {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .ob-ttd-canvas {
            display: block;
            width: 100%;
            height: 160px;
            background: #fff;
            border: 1px solid #999;
            border-radius: 4px;
            touch-action: none;
            cursor: crosshair;
        }

        .ob-ttd-hapus {
            display: inline-block;
            margin-top: 6px;
            font-size: 13px;
            color: #c1121f;
            text-decoration: underline;
        }

        /* Tombol Cetak & Simpan Informed Consent sejajar di kanan */
        .ob-ttd-aksi {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 16px;
        }

        .ob-btn-simpan:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        @media (max-width: 900px) {
            .ob-ttd-wrap {
                flex-direction: column;
            }
        }

        .ob-radio-note {
            margin: -6px 0 14px;
            font-size: 12px;
            color: #777;
        }

        .ob-radio-readonly .ob-checklist-item {
            cursor: default;
        }

        .ob-radio-readonly .ob-checklist-item input[type=checkbox]:disabled {
            opacity: 1;
            cursor: default;
            accent-color: #b81d24;
        }

        .ob-radio-readonly .ob-checklist-lainnya input[type=text]:disabled {
            background: #f7f7f7;
            color: #333;
            cursor: default;
        }

        .ob-radio-readonly .ob-upload-box {
            cursor: default;
            border-style: solid;
        }

        .ob-radio-readonly .ob-upload-box:hover {
            border-color: #ccc;
            color: #777;
        }

        .ob-radio-readonly .ob-hasil-textarea {
            background: #f7f7f7;
            cursor: default;
        }

        .ob-scan-img {
            display: block;
            max-width: 100%;
            max-height: 320px;
            margin: 0 auto 10px;
            border-radius: 6px;
        }

        .ob-scan-link {
            color: #b81d24;
            font-weight: 600;
            text-decoration: none;
        }

        .ob-scan-link:hover {
            text-decoration: underline;
        }

        /* =====================================================
           FORMULIR CETAK INFORMED CONSENT (A4, 1 lembar)
           Dicetak lewat dialog print browser, jadi bisa dipilih
           "Save as PDF" atau printer (mis. Epson) di dialog.
           ===================================================== */
        @page ic {
            size: A4;
            margin: 0; /* juga menghilangkan tanggal & URL bawaan browser */
        }

        @media screen {
            .ic-cetak-only {
                display: none;
            }
        }

        @media print {
            body.cetak-ic-mode > *:not(#cetak-ic-area) {
                display: none !important;
            }

            html,
            body.cetak-ic-mode {
                margin: 0 !important;
                padding: 0 !important;
                height: auto !important;
                overflow: visible !important;
                background: #fff !important;
            }

            body.cetak-ic-mode #cetak-ic-area {
                display: block !important;
                visibility: visible !important;
                position: static !important;
                page: ic;
                width: 100%;
                box-sizing: border-box;
                padding: 12mm 16mm;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 11px;
                line-height: 1.4;
                color: #000;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body.cetak-ic-mode #cetak-ic-area * {
                visibility: visible !important;
            }

            /* Kop surat */
            .icp-header {
                display: flex;
                align-items: center;
                padding-bottom: 8px;
                margin-bottom: 10px;
                border-bottom: 2px solid #000;
            }

            .icp-logo {
                width: 70px;
                height: 70px;
                margin-right: 16px;
                object-fit: contain;
            }

            .icp-header-text {
                flex-grow: 1;
                text-align: center;
            }

            .icp-header-text h2 {
                margin: 0;
                font-size: 17px;
            }

            .icp-header-text p {
                margin: 2px 0;
                font-size: 11px;
            }

            .icp-title {
                margin: 6px 0 10px;
                text-align: center;
                font-size: 14px;
                font-weight: bold;
                letter-spacing: .3px;
            }

            .icp-title span {
                display: block;
                font-size: 11px;
                font-weight: normal;
            }

            /* Identitas pasien */
            .icp-info {
                width: 100%;
                margin-bottom: 10px;
                font-size: 11px;
                border-collapse: collapse;
            }

            .icp-info td {
                padding: 2px 0;
                vertical-align: top;
            }

            .icp-info td.icp-info-label { width: 17%; }
            .icp-info td.icp-info-sep { width: 2%; }
            .icp-info td.icp-info-isi { width: 31%; }

            /* Tabel isi informasi */
            .icp-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 11px;
            }

            .icp-table th,
            .icp-table td {
                padding: 4px 7px;
                border: 1px solid #000;
                vertical-align: top;
            }

            .icp-table th {
                background: #e6e6e6;
                text-align: center;
                font-weight: bold;
            }

            .icp-table tr {
                page-break-inside: avoid;
            }

            .icp-table td.icp-kategori {
                width: 25%;
                font-weight: bold;
            }

            .icp-table td.icp-cek {
                width: 13%;
                text-align: center;
                vertical-align: middle;
            }

            /* Kotak centang */
            .icp-box {
                display: inline-block;
                width: 12px;
                height: 12px;
                margin-right: 4px;
                border: 1.5px solid #000;
                font-size: 11px;
                font-weight: bold;
                line-height: 11px;
                text-align: center;
                vertical-align: middle;
            }

            .icp-table td.icp-cek .icp-box {
                margin-right: 0;
            }

            /* Pernyataan */
            .icp-persetujuan {
                margin-top: 12px;
                page-break-inside: avoid;
            }

            .icp-subjudul {
                margin-bottom: 5px;
                font-size: 12px;
                font-weight: bold;
            }

            .icp-keputusan {
                margin-bottom: 6px;
            }

            .icp-keputusan span.icp-pilihan {
                display: inline-block;
                margin-right: 28px;
            }

            .icp-pernyataan {
                margin: 0 0 8px;
            }

            .icp-waktu {
                margin-bottom: 4px;
                text-align: right;
            }

            /* Tanda tangan */
            .icp-ttd {
                width: 100%;
                border-collapse: collapse;
                page-break-inside: avoid;
            }

            .icp-ttd td {
                width: 33.33%;
                padding: 0 8px;
                text-align: center;
                vertical-align: top;
            }

            .icp-ttd-judul {
                font-weight: bold;
            }

            .icp-ttd-gambar {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 75px;
                margin: 4px 0;
            }

            .icp-ttd-gambar img {
                max-width: 100%;
                max-height: 75px;
            }

            .icp-ttd-nama {
                min-height: 14px;
                padding-top: 2px;
                border-top: 1px solid #000;
            }
        }
    </style>
@endsection

@section('content')

    @php
    
        $pasien = $pasien ?? null;
        $ttv = $ttv ?? null;
        $assesmen = $assesmen ?? null;
        $diagnosisTersimpan = $diagnosisTersimpan ?? collect();
        $tindakanTersimpan = $tindakanTersimpan ?? collect();
        $radiologi = $radiologi ?? null;

        $vitalFields = [
            ['TD', 'td', 'mmHg', 'mis. 120/80'],
            ['HR', 'hr', 'x/menit', 'mis. 88'],
            ['SpO2', 'spo2', '%', 'mis. 98'],
            ['Suhu', 'suhu', '°C', 'mis. 36.5'],
            ['RR', 'rr', 'x/menit', 'mis. 20'],
            ['HPHT', 'hpht', '', 'hh-bb-tttt'],
            ['UK', 'uk', 'minggu', 'mis. 12'],
        ];

        $soapRows = [
            ['S', 'subjective', 'textarea', 'Subjektif...'],
            ['O', 'objective', 'textarea', 'Hasil pemeriksaan objektif...'],
            ['A', 'assessment', 'textarea', 'Assessment / analisa...'],
            ['P', 'plan', 'textarea', 'Rencana/plan...'],
        ];

        $diagnosaFields = [
            ['Penyakit', 'Cari penyakit...'],
            ['Tindakan', 'Cari tindakan...'],
        ];

        $radiologiOptions = [
            'Foto Thoraks',
            'MRI',
            'USG Abdomen',
            'Rontgen',
            'CT Scan',
        ];

        $radPemeriksaan = (array) data_get($radiologi, 'pemeriksaan', []);
        $radLainnya = trim((string) data_get($radiologi, 'lainnya', ''));
        $radFile = data_get($radiologi, 'file_scan');
        $radHasil = (string) data_get($radiologi, 'hasil', '');

        $informedRows = [
            ['Diagnosis/ Tindakan', 'KB IUD'],
            ['Tindakan Kedokteran', 'Pemasangan IUD'],
            ['Indikasi Tindakan', 'Mengatur jumlah kelahiran bayi'],
            ['Tata Cara', 'Pengukuran rahim, penyiapan iud, persiapan alat'],
            ['Tujuan', 'Mencegah kehamilan dan mengatur jarak kelahiran'],
            ['Manfaat', 'Mencegah kehamilan jangka panjang'],
            ['Risiko', 'Kram perut, perdarahan (flek), atau infeksi rahim'],
            ['Komplikasi', 'IUD bergeser/ keluar, infeksi, panggul berat, robekan rahim dan barang hilang/ putus'],
            ['Prognosis', 'Dubia ad sanam'],
            ['Alternatif', 'KB yang lain'],
            ['Pertimbangan Pelayanan', '-'],
            ['Lain-lain', '-'],
        ];

        $tanggalLahir = $pasien && $pasien->tgl_lahir
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d')
            : '-';

        $umur = ($pasien && $pasien->tgl_lahir)
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age . ' Tahun'
            : '-';

        $jenisKelamin = match ((string) ($pasien->jenis_kelamin ?? '')) {
            '0' => 'Tidak diketahui',
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
            '3' => 'Tidak dapat ditentukan',
            '4' => 'Tidak dikonfirmasi',
            default => '-',
        };

        $diagnosisJson = $diagnosisTersimpan->map(function ($item) {
            return [
                'id' => $item->id_kode10,
                'kode' => $item->kode,
                'nama' => $item->nama,
            ];
        })->values()->all();

        $tindakanJson = $tindakanTersimpan->map(function ($item) {
            return [
                'id' => $item->id_kode9,
                'kode' => $item->kode,
                'nama' => $item->nama,
            ];
        })->values()->all();

    @endphp

    {{-- DATA PASIEN --}}
    <div class="ob-card" id="kartu-pasien-utama">
        <div class="ob-section-title">Data Pasien</div>

        @if ($pasien)
            @php
                $dataPasien = [
                    'No RM' => $pasien->no_rm ?? '-',
                    'Tempat, Tgl Lahir' => ($pasien->tempat_lahir ?? '-') . ', ' . $tanggalLahir,
                    'Nama' => $pasien->nama_pasien ?? '-',
                    'Umur' => $umur,
                    'Jenis Kelamin' => $jenisKelamin,
                    'No Hp' => $pasien->no_hp ?? '-',
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
            <p class="teks-kosong">Data pasien tidak ditemukan.</p>
        @endif
    </div>

    {{-- TAB --}}
    <div class="ob-tabs">
        <div class="ob-tab active" id="ob-tab-btn-assessment"
             onclick="gantiTab('assessment', event)">
            <i class="fa-solid fa-clipboard-list"></i> Assessment
        </div>

        <div class="ob-tab" id="ob-tab-btn-radiologi"
             onclick="gantiTab('radiologi', event)">
            <i class="fa-solid fa-x-ray"></i> Radiologi
        </div>

        <div class="ob-tab" id="ob-tab-btn-informed"
             onclick="gantiTab('informed', event)">
            <i class="fa-solid fa-file-signature"></i> Informed Consent
        </div>
    </div>

    {{-- AREA CETAK RESEP --}}
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
            <tr>
                <td width="15%">Nama Pasien</td>
                <td width="2%">:</td>
                <td width="83%">{{ $pasien->nama_pasien ?? '-' }}</td>
            </tr>
            <tr>
                <td>No. R.M.</td>
                <td>:</td>
                <td>{{ $pasien->no_rm ?? '-' }}</td>
            </tr>
            <tr>
                <td>Umur / JK</td>
                <td>:</td>
                <td>{{ $umur }} / {{ $jenisKelamin }}</td>
            </tr>
            <tr>
                <td>Pemberi Resep</td>
                <td>:</td>
                <td>Dokter Poli Obgyn</td>
            </tr>
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
                <p>dr. Poli Obgyn</p>
            </div>
        </div>
    </div>

    {{-- AREA CETAK INFORMED CONSENT (tersembunyi, hanya muncul saat print) --}}
    <div id="cetak-ic-area" class="ic-cetak-only">
        <div class="icp-header">
            <img src="{{ asset('images/logo.png') }}" class="icp-logo" alt="Logo Klinik">
            <div class="icp-header-text">
                <h2>KLINIK RAWAT INAP MERAH PUTIH</h2>
                <p>Jl. Ronggo Warsito No. 98 A, Ngawi</p>
                <p>Telp: (0351) 745596</p>
            </div>
        </div>

        <div class="icp-title">
            PERSETUJUAN TINDAKAN KEDOKTERAN
            <span>(INFORMED CONSENT)</span>
        </div>

        <table class="icp-info">
            <tr>
                <td class="icp-info-label">Nama Pasien</td>
                <td class="icp-info-sep">:</td>
                <td class="icp-info-isi">{{ $pasien->nama_pasien ?? '-' }}</td>
                <td class="icp-info-label">No. R.M.</td>
                <td class="icp-info-sep">:</td>
                <td class="icp-info-isi">{{ $pasien->no_rm ?? '-' }}</td>
            </tr>
            <tr>
                <td class="icp-info-label">Tempat, Tgl Lahir</td>
                <td class="icp-info-sep">:</td>
                <td class="icp-info-isi">{{ ($pasien->tempat_lahir ?? '-') . ', ' . $tanggalLahir }}</td>
                <td class="icp-info-label">Umur / JK</td>
                <td class="icp-info-sep">:</td>
                <td class="icp-info-isi">{{ $umur }} / {{ $jenisKelamin }}</td>
            </tr>
        </table>

        <table class="icp-table">
            <thead>
                <tr>
                    <th>Informed Consent</th>
                    <th>Isi Informasi</th>
                    <th>Checklist</th>
                </tr>
            </thead>
            <tbody id="ic-cetak-body"></tbody>
        </table>

        <div class="icp-persetujuan">
            <div class="icp-subjudul">Pernyataan Persetujuan dan/ Penolakan</div>

            <div class="icp-keputusan" id="ic-cetak-keputusan"></div>

            <p class="icp-pernyataan">
                Dengan ini menyatakan telah memahami informasi yang telah diberikan.
            </p>

            <div class="icp-waktu">
                Ngawi, <span id="ic-cetak-tanggal"></span>, pukul <span id="ic-cetak-pukul"></span>
            </div>

            <table class="icp-ttd">
                <tr>
                    <td>
                        <div class="icp-ttd-judul">Pemberi Informasi (Dokter)</div>
                        <div class="icp-ttd-gambar" id="ic-cetak-ttd-dokter"></div>
                        <div class="icp-ttd-nama" id="ic-cetak-nama-dokter"></div>
                    </td>
                    <td>
                        <div class="icp-ttd-judul">Pasien/ Keluarga</div>
                        <div class="icp-ttd-gambar" id="ic-cetak-ttd-pasien"></div>
                        <div class="icp-ttd-nama" id="ic-cetak-nama-pasien"></div>
                    </td>
                    <td>
                        <div class="icp-ttd-judul">Saksi Klinik</div>
                        <div class="icp-ttd-gambar" id="ic-cetak-ttd-saksi"></div>
                        <div class="icp-ttd-nama" id="ic-cetak-nama-saksi"></div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- TAB ASSESSMENT --}}
    <div id="tab-assessment" class="ob-tab-content active">

        <div class="ob-vitals-box">
            <div class="ob-vitals-grid">
                @foreach ($vitalFields as [$label, $name, $unit, $placeholder])
                    <div class="ob-vital-row">
                        <label>{{ $label }}</label>

                        @if ($name === 'hpht')
                            <input
                                type="date"
                                name="hpht"
                                max="{{ date('Y-m-d') }}"
                                title="Pilih tanggal HPHT"
                                value="{{ old('hpht', $ttv->hpht ?? '') }}"
                            >
                        @elseif ($name === 'uk')
                            <input
                                type="number"
                                name="uk"
                                min="0"
                                max="50"
                                placeholder="{{ $placeholder }}"
                                value="{{ old('uk', $ttv->usia_kehamilan ?? '') }}"
                            >
                        @else
                            <input
                                type="text"
                                name="{{ $name }}"
                                placeholder="{{ $placeholder }}"
                                value="{{ old($name, match ($name) {
                                    'td' => $ttv->tekanan_darah ?? '',
                                    'hr' => $ttv->heart_rate ?? '',
                                    'spo2' => $ttv->spo2 ?? '',
                                    'suhu' => $ttv->suhu ?? '',
                                    'rr' => $ttv->respiratory_rate ?? '',
                                    default => '',
                                }) }}"
                            >
                        @endif

                        <span class="ob-vital-unit">{{ $unit }}</span>
                    </div>
                @endforeach

                <div class="ob-vital-row">
                    <label>Alergi</label>
                    <input
                        type="text"
                        name="alergi"
                        placeholder="mis. tidak ada"
                        value="{{ old('alergi', $ttv->alergi ?? '') }}"
                    >

                    <div class="ob-vital-check">
                        <span
                            class="ob-check-icon"
                            id="btnCentangVital"
                            role="button"
                            title="Masukkan tanda vital ke Objective"
                            onclick="isiObjektifDariVital()"
                        >✓</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="ob-divider">

        {{-- SOAP --}}
        <div class="ob-soap-title">SOAP</div>

        <div class="ob-soap-box">
            @foreach ($soapRows as [$kode, $name, $tipe, $placeholder])
                <div class="ob-soap-row">
                    <div class="ob-soap-badge">{{ $kode }}</div>

                    @if ($name === 'plan')
                        <div class="ob-plan-wrap">
                            <textarea
                                name="{{ $name }}"
                                rows="4"
                                placeholder="{{ $placeholder }}"
                            >{{ old($name, $assesmen->plan ?? '') }}</textarea>

                            <button
                                type="button"
                                class="ob-cetak-btn ob-cetak-plan"
                                onclick="cetakResep()"
                            >
                                <i class="fa-solid fa-print"></i> Cetak
                            </button>
                        </div>
                    @else
                        <textarea
                            name="{{ $name }}"
                            rows="4"
                            placeholder="{{ $placeholder }}"
                        >{{ old($name, match ($name) {
                            'subjective' => $assesmen->subjek ?? '',
                            'objective' => $assesmen->objek ?? '',
                            'assessment' => $assesmen->assesmen ?? '',
                            default => '',
                        }) }}</textarea>
                    @endif
                </div>
            @endforeach
        </div>

        <hr class="ob-divider">

        {{-- DIAGNOSIS --}}
        <div class="ob-diagnosa-title">Diagnosa</div>

        <div class="ob-diagnosa-box">
            <div class="ob-diagnosa-row">
                @foreach ($diagnosaFields as [$label, $placeholder])
                    @php
                        $jenis = strtolower($label);
                    @endphp

                    <div class="ob-search-field">
                        <label>{{ $label }}</label>

                        <div class="ob-search-inline">
                            <div class="ob-search-box">
                                <input
                                    type="text"
                                    id="cari{{ $label }}"
                                    placeholder="{{ $placeholder }}"
                                    autocomplete="off"
                                    oninput="cariItem('{{ $jenis }}')"
                                    onfocus="cariItem('{{ $jenis }}')"
                                    onkeydown="if (event.key === 'Enter') {
                                        event.preventDefault();
                                        tambahDiagnosa('{{ $jenis }}');
                                    }"
                                >

                                <span class="ob-search-icon">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>

                                <div class="ob-dropdown" id="dropdown{{ $label }}"></div>
                            </div>

                            <div class="ob-check-box">
                                <span
                                    class="ob-check-icon"
                                    id="check{{ $label }}"
                                    role="button"
                                    title="Tambahkan {{ strtolower($label) }} ke daftar"
                                    onclick="tambahDiagnosa('{{ $jenis }}')"
                                >✓</span>
                            </div>
                        </div>

                        <div class="ob-diagnosa-list" id="list{{ $label }}"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ob-actions">
            <button
                type="button"
                class="ob-btn ob-btn-reset ob-btn-rujuk"
                onclick="rujukPasien()"
            >
                <i class="fa-solid fa-right-from-bracket"></i> Rujuk
            </button>

            <button
                type="button"
                id="btnSimpanKunjungan"
                class="ob-btn ob-btn-simpan"
                onclick="simpanKunjunganBaru()"
            >
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
        </div>
    </div>

    {{-- TAB RADIOLOGI: READ ONLY --}}
    <div id="tab-radiologi" class="ob-tab-content">
        <div class="ob-radio-card ob-radio-readonly">
            <div class="ob-radio-title">Form Pemeriksaan</div>
            <div class="ob-radio-note">Halaman ini hanya untuk melihat hasil radiologi.</div>
            <div class="ob-radio-sub">Ceklist Periksa Radiologi</div>

            <div class="ob-checklist-grid">
                @foreach ($radiologiOptions as $opt)
                    <label class="ob-checklist-item">
                        <input
                            type="checkbox"
                            disabled
                            {{ in_array($opt, $radPemeriksaan) ? 'checked' : '' }}
                        >
                        {{ $opt }}
                    </label>
                @endforeach

                <label class="ob-checklist-item ob-checklist-lainnya">
                    <input
                        type="checkbox"
                        disabled
                        {{ $radLainnya !== '' ? 'checked' : '' }}
                    >
                    Lainnya

                    <input
                        type="text"
                        value="{{ $radLainnya }}"
                        placeholder="-"
                        readonly
                        disabled
                    >
                </label>
            </div>

            <div class="ob-radio-sub">Scan Hasil</div>

            <div class="ob-upload-box">
                @if ($radFile)
                    @if (preg_match('/\.(jpe?g|png)$/i', $radFile))
                        <a href="{{ $radFile }}" target="_blank" rel="noopener">
                            <img
                                src="{{ $radFile }}"
                                alt="Scan hasil radiologi"
                                class="ob-scan-img"
                            >
                        </a>
                    @endif

                    <a
                        href="{{ $radFile }}"
                        target="_blank"
                        rel="noopener"
                        class="ob-scan-link"
                    >
                        <i class="fa-solid fa-up-right-from-square"></i>
                        Buka scan hasil
                    </a>
                @else
                    Belum ada scan hasil.
                @endif
            </div>

            <div class="ob-radio-sub">Hasil Pemeriksaan Radiologi</div>

            <textarea
                class="ob-hasil-textarea"
                rows="5"
                readonly
                placeholder="Belum ada hasil pemeriksaan radiologi."
            >{{ $radHasil }}</textarea>
        </div>
    </div>

    {{-- TAB INFORMED CONSENT --}}
    <div id="tab-informed" class="ob-tab-content">
        <div class="ob-ic-card">
            <div class="ob-ic-title">Informed Consent</div>

            <div class="ob-ic-table-wrap">
                <table class="ob-ic-table">
                    <thead>
                        <tr>
                            <th class="ob-ic-col-kategori">Informed Consent</th>
                            <th>Isi Informasi</th>
                            <th class="ob-ic-col-cek">Checklist</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($informedRows as [$kategori, $isi])
                            @php
                                $bisaDiisi = in_array(
                                    $kategori,
                                    ['Pertimbangan Pelayanan', 'Lain-lain']
                                );
                            @endphp

                            <tr>
                                <td>{{ $kategori }}</td>

                                <td>
                                    @if ($bisaDiisi)
                                        <input
                                            type="text"
                                            class="ob-ic-isi-bebas"
                                            name="informed_isi[{{ $kategori }}]"
                                            autocomplete="off"
                                            placeholder="Tuliskan di sini..."
                                            style="width:100%;box-sizing:border-box;padding:6px 10px;border:1px solid #ccc;border-radius:4px;font:inherit;"
                                        >
                                    @else
                                        {{ $isi }}
                                    @endif
                                </td>

                                <td class="ob-ic-cek">
                                    <input
                                        type="checkbox"
                                        name="informed_cek[]"
                                        value="{{ $kategori }}"
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="ob-ic-persetujuan">
                <div class="ob-ic-subtitle">
                    Pernyataan Persetujuan dan/ Penolakan
                </div>

                <div class="ob-ic-radio">
                    <label>
                        <input
                            type="radio"
                            name="informed_keputusan"
                            value="setuju"
                            checked
                        >
                        Setuju
                    </label>

                    <label>
                        <input
                            type="radio"
                            name="informed_keputusan"
                            value="menolak"
                        >
                        Menolak
                    </label>
                </div>

                <div class="ob-ic-pernyataan">
                    Dengan ini menyatakan telah memahami informasi yang telah diberikan.
                </div>

                <div class="ob-ic-form">
                    <div class="ob-ic-field">
                        <label>Nama Dokter</label>
                        <div class="ob-ic-input-icon">
                            <input
                                type="text"
                                id="icDokter"
                                name="informed_dokter"
                                value="dr. Aditya Iqbal M, Sp.OG"
                                autocomplete="off"
                                readonly
                            >
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>

                    <div class="ob-ic-field">
                        <label>Nama Pasien/ Keluarga</label>
                        <input
                            type="text"
                            id="icPasien"
                            name="informed_pasien"
                            value="{{ $pasien->nama_pasien ?? '' }}"
                            autocomplete="off"
                        >
                    </div>

                    <div class="ob-ic-field">
                        <label>Nama Saksi Klinik</label>
                        <input
                            type="text"
                            id="icSaksi"
                            name="informed_saksi"
                            autocomplete="off"
                        >
                    </div>

                    <div class="ob-ic-field">
                        <label>Tanggal Tindakan</label>
                        <input
                            type="date"
                            id="icTanggal"
                            name="informed_tanggal"
                        >
                    </div>

                    <div class="ob-ic-field">
                        <label>Pukul</label>
                        <input
                            type="time"
                            id="icPukul"
                            name="informed_pukul"
                        >
                    </div>
                </div>

                {{-- TANDA TANGAN --}}
                <div class="ob-ttd-wrap">
                    <div class="ob-ttd-item">
                        <div class="ob-ttd-judul">Pemberi Informasi (Dokter)</div>
                        <canvas
                            id="ttdDokter"
                            class="ob-ttd-canvas"
                            width="500"
                            height="200"
                        ></canvas>
                        <a
                            href="#"
                            class="ob-ttd-hapus"
                            onclick="hapusTtd('ttdDokter'); return false;"
                        >Hapus tanda tangan</a>
                    </div>

                    <div class="ob-ttd-item">
                        <div class="ob-ttd-judul">Tanda Tangan Pasien/ Keluarga</div>
                        <canvas
                            id="ttdPasien"
                            class="ob-ttd-canvas"
                            width="500"
                            height="200"
                        ></canvas>
                        <a
                            href="#"
                            class="ob-ttd-hapus"
                            onclick="hapusTtd('ttdPasien'); return false;"
                        >Hapus tanda tangan</a>
                    </div>

                    <div class="ob-ttd-item">
                        <div class="ob-ttd-judul">Saksi Klinik</div>
                        <canvas
                            id="ttdSaksi"
                            class="ob-ttd-canvas"
                            width="500"
                            height="200"
                        ></canvas>
                        <a
                            href="#"
                            class="ob-ttd-hapus"
                            onclick="hapusTtd('ttdSaksi'); return false;"
                        >Hapus tanda tangan</a>
                    </div>
                </div>

                <div class="ob-ttd-aksi">
                    <button
                        type="button"
                        class="ob-btn ob-btn-reset ob-btn-rujuk"
                        onclick="cetakInformedConsent()"
                    >
                        <i class="fa-solid fa-print"></i> Cetak
                    </button>

                    <button
                        type="button"
                        class="ob-btn ob-btn-simpan"
                        onclick="simpanInformedConsent()"
                    >
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- FORM RUJUKAN --}}
    <div id="view-rujukan">
        <div class="rj-box">
            <div class="rj-back">
                <button
                    type="button"
                    onclick="tutupRujukan()"
                    aria-label="Kembali"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </div>

            <div class="rj-body">
                <div class="rj-grid">
                    <div class="rj-field">
                        <label>Poli Asal</label>
                        <input type="text" id="rjPoliAsal" value="Poli Obgyn" readonly>
                    </div>

                    <div class="rj-field">
                        <label>Tujuan</label>
                        <select id="rjTujuan">
                            <option value="">-- Pilih Poli --</option>
                            <option value="saraf">Poli Syaraf</option>
                            <option value="jantung">Poli Jantung</option>
                            <option value="jiwa">Poli Jiwa</option>
                            <option value="radiologi">Radiologi</option>
                        </select>
                    </div>

                    <div class="rj-field">
                        <label>Dokter Pengirim</label>
                        <input type="text" id="rjDokter" value="dr. Aditya Iqbal M, Sp.OG" readonly>
                    </div>

                    <div class="rj-field">
                        <label>Tanggal Rujuk</label>
                        <input type="date" id="rjTanggal">
                    </div>
                </div>
            </div>
        </div>

        <div class="rj-box">
            <div class="rj-title">Data Pasien</div>

            <div class="rj-body">
                @if ($pasien)
                    <div class="rj-grid">
                        <div class="rj-field">
                            <label>No RM</label>
                            <input type="text" value="{{ $pasien->no_rm }}" readonly>
                        </div>

                        <div class="rj-field">
                            <label>Tempat, Tgl Lahir</label>
                            <input
                                type="text"
                                value="{{ ($pasien->tempat_lahir ?? '-') . ', ' . $tanggalLahir }}"
                                readonly
                            >
                        </div>

                        <div class="rj-field">
                            <label>Nama</label>
                            <input type="text" value="{{ $pasien->nama_pasien }}" readonly>
                        </div>

                        <div class="rj-field">
                            <label>Umur</label>
                            <input type="text" value="{{ $umur }}" readonly>
                        </div>

                        <div class="rj-field">
                            <label>Jenis Kelamin</label>
                            <input type="text" value="{{ $jenisKelamin }}" readonly>
                        </div>

                        <div class="rj-field">
                            <label>No Hp</label>
                            <input type="text" value="{{ $pasien->no_hp ?? '-' }}" readonly>
                        </div>
                    </div>
                @else
                    <p class="teks-kosong">Data pasien tidak ditemukan.</p>
                @endif

                <div class="rj-grid rj-grid-mt">
                    <div>
                        <div class="rj-field rj-mb">
                            <label>Diagnosa</label>
                            <input type="text" id="rjDiagnosa" autocomplete="off">
                        </div>

                        <div class="rj-field rj-top">
                            <label>Alasan Rujuk</label>
                            <textarea id="rjAlasan"></textarea>
                        </div>
                    </div>

                    <div>
                        <div class="rj-cek-title">Checklist Periksa Radiologi</div>

                        @foreach (['Foto Thoraks', 'USG Abdomen', 'CT Scan', 'MRI', 'Rontgen'] as $opt)
                            <label class="rj-cek-item">
                                <input
                                    type="checkbox"
                                    name="rujuk_radiologi[]"
                                    value="{{ $opt }}"
                                >
                                {{ $opt }}
                            </label>
                        @endforeach

                        <label class="rj-cek-item">
                            <input
                                type="checkbox"
                                id="rjCekLainnya"
                                name="rujuk_radiologi[]"
                                value="Lainnya"
                            >
                            Lainnya

                            <input
                                type="text"
                                id="rjLainnya"
                                placeholder="(tuliskan)"
                                autocomplete="off"
                            >
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="rj-actions">
            <button type="button" class="rj-kirim" onclick="kirimRujukan()">
                KIRIM RUJUKAN
            </button>
        </div>
    </div>

@endsection

@section('extra-js')
<script>
    // =========================================================
    // KONFIGURASI
    // =========================================================
    const NO_RM_PASIEN = @json($pasien->no_rm ?? null);
    const ID_PENDAFTARAN = @json($id_pendaftaran ?? null);

    // true bila kunjungan ini sudah punya data asesmen di database.
    // false = pasien baru: wajib klik Simpan sebelum meninggalkan halaman.
    const SUDAH_ADA_ASSESMEN = @json($assesmen !== null);

    const URL_SIMPAN_TTV = @json(route('poli.obgyn.ttv.store'));
    const URL_SIMPAN_ASSESMEN = @json(route('poli.obgyn.assesmen.store'));
    const URL_UPDATE_STATUS = @json(route('poli.obgyn.status-periksa', ['id' => '__ID__']));
    const URL_HALAMAN_OBGYN = @json(route('poli.obgyn'));
    const URL_CARI = @json(url('/cari-kode'));
    const CSRF_TOKEN = @json(csrf_token());
    const URL_KIRIM_RUJUKAN = @json(route('poli.obgyn.rujukan'));

    // true hanya sesaat sebelum pindah halaman setelah Simpan / Rujuk berhasil.
    let izinkanKeluar = false;

    // Membaca respons server dengan aman, supaya error HTML/500 terlihat jelas.
    async function bacaJson(response, namaProses) {
        const teks = await response.text();

        try {
            return JSON.parse(teks);
        } catch (e) {
            console.error(
                'Respons ' + namaProses + ' bukan JSON:',
                response.status,
                teks
            );

            throw new Error(
                'Server tidak mengirim respons JSON saat ' + namaProses +
                ' (status ' + response.status + '). Periksa route dan log Laravel.'
            );
        }
    }

    // =========================================================
    // TAB
    // =========================================================
    function gantiTab(tab, event) {
        document.querySelectorAll('.ob-tab').forEach(el => {
            el.classList.remove('active');
        });

        document.querySelectorAll('.ob-tab-content').forEach(el => {
            el.classList.remove('active');
        });

        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }

        const panel = document.getElementById('tab-' + tab);
        if (panel) panel.classList.add('active');
    }

    // =========================================================
    // CETAK (resep & informed consent)
    // Area cetak dipindah ke <body> sementara agar hanya area itu
    // yang tampil di kertas, lalu dikembalikan setelah dialog print
    // ditutup (afterprint).
    // =========================================================
    let areaCetakAktif = null;

    function siapkanCetak(idArea, kelasMode) {
        const area = document.getElementById(idArea);

        areaCetakAktif = {
            area: area,
            parent: area.parentNode,
            next: area.nextSibling
        };

        document.body.appendChild(area);
        document.body.classList.add(kelasMode);
    }

    window.addEventListener('afterprint', function () {
        document.body.classList.remove('cetak-resep-mode', 'cetak-ic-mode');

        if (!areaCetakAktif) return;

        const { area, parent, next } = areaCetakAktif;

        if (next) {
            parent.insertBefore(area, next);
        } else {
            parent.appendChild(area);
        }

        areaCetakAktif = null;
    });

    function tanggalIndonesia(tanggal) {
        return tanggal.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    function cetakResep() {
        const planEl = document.querySelector('textarea[name="plan"]');
        const planText = planEl ? planEl.value.trim() : '';

        if (!planText) {
            alert('Isi bagian Plan (P) di SOAP dulu sebelum mencetak resep.');
            return;
        }

        document.getElementById('resep-isi-plan').innerText = planText;
        document.getElementById('resep-tanggal').innerText = tanggalIndonesia(new Date());

        siapkanCetak('cetak-resep-area', 'cetak-resep-mode');

        window.print();
    }

    // =========================================================
    // RUJUKAN
    // =========================================================
    function rujukPasien() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        const tgl = document.getElementById('rjTanggal');

        if (!tgl.value) {
            const d = new Date();

            tgl.value =
                d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0');
        }

        // Diagnosa rujukan otomatis dari diagnosa penyakit di form Assessment
        const rjDx = document.getElementById('rjDiagnosa');

        // 1) daftar penyakit yang sudah ditambahkan (✓)
        const daftarDx = diagnosaTerpilih.penyakit
            .map(item => item.kode + ' — ' + item.nama);

        // 2) penyakit yang sudah dipilih dari pencarian tapi belum di-✓
        if (pilihan.penyakit) {
            const teksPilihan = pilihan.penyakit.kode + ' — ' + pilihan.penyakit.nama;
            if (!daftarDx.includes(teksPilihan)) daftarDx.push(teksPilihan);
        }

        let teksDx = daftarDx.join('; ');

        // 3) jika belum ada, pakai isi kolom A (Assessment) di SOAP
        if (!teksDx) {
            const asesmen = document.querySelector('textarea[name="assessment"]');
            teksDx = asesmen ? asesmen.value.trim() : '';
        }

        rjDx.value = teksDx;
        // terkunci jika terisi otomatis; jika kosong tetap bisa diketik manual
        rjDx.readOnly = teksDx !== '';

        document.body.classList.add('mode-rujukan');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function tutupRujukan() {
        document.body.classList.remove('mode-rujukan');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    const rjCekLainnya = document.getElementById('rjCekLainnya');
    const rjLainnya = document.getElementById('rjLainnya');

    if (rjCekLainnya && rjLainnya) {
        rjCekLainnya.addEventListener('change', function () {
            if (this.checked) {
                rjLainnya.focus();
            } else {
                rjLainnya.value = '';
            }
        });

        rjLainnya.addEventListener('input', function () {
            rjCekLainnya.checked = this.value.trim() !== '';
        });
    }


    async function kirimRujukan() {
        const tujuan = document.getElementById('rjTujuan')?.value ?? '';
        const alasan = document.getElementById('rjAlasan')?.value.trim() ?? '';
        const diagnosa = document.getElementById('rjDiagnosa')?.value.trim() ?? '';
        const tanggal = document.getElementById('rjTanggal')?.value ?? '';
        const dokter = document.getElementById('rjDokter')?.value.trim() ?? '';

        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        if (!tujuan) {
            alert('Pilih poli tujuan rujukan terlebih dahulu.');
            return;
        }

        if (!alasan) {
            alert('Isi alasan rujuk terlebih dahulu.');
            return;
        }

        if (!tanggal) {
            alert('Pilih tanggal rujukan terlebih dahulu.');
            return;
        }

        const tombol = document.querySelector('.rj-kirim');

        if (!tombol || tombol.disabled) return;

        const teksAwal = tombol.textContent;
        tombol.disabled = true;
        tombol.textContent = 'Memproses...';

        const radiologi = Array.from(
            document.querySelectorAll(
                '#view-rujukan input[name="rujuk_radiologi[]"]:checked'
            )
        )
            .filter(input => input.id !== 'rjCekLainnya')
            .map(input => input.value);

        const cekLainnya = document.getElementById('rjCekLainnya');
        const inputLainnya = document.getElementById('rjLainnya');
        const radiologiLainnya = cekLainnya?.checked
            ? (inputLainnya?.value.trim() ?? '')
            : '';

        if (cekLainnya?.checked && !radiologiLainnya) {
            alert('Tuliskan jenis pemeriksaan radiologi lainnya.');
            tombol.disabled = false;
            tombol.textContent = teksAwal;
            return;
        }

        try {
            const response = await fetch(URL_KIRIM_RUJUKAN, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({
                    no_rm: NO_RM_PASIEN,
                    id_pendaftaran: ID_PENDAFTARAN,
                    tujuan: tujuan,
                    alasan: alasan,
                    diagnosa: diagnosa,
                    dokter_pengirim: dokter,
                    tanggal_rujuk: tanggal,
                    pemeriksaan_radiologi: radiologi,
                    radiologi_lainnya: radiologiLainnya
                })
            });

            const hasil = await bacaJson(response, 'mengirim rujukan');

            if (!response.ok || !hasil.success) {
                const pesanValidasi = Object.values(hasil.errors || {})
                    .flat()
                    .join('\n');

                throw new Error(
                    pesanValidasi ||
                    hasil.message ||
                    'Rujukan gagal diproses.'
                );
            }

            // Rujukan berhasil: halaman boleh ditinggalkan.
            izinkanKeluar = true;

            alert(hasil.message || 'Rujukan berhasil dikirim.');

            if (hasil.redirect) {
                window.location.href = hasil.redirect;
            } else {
                window.location.href = URL_HALAMAN_OBGYN;
            }

        } catch (error) {
            console.error('Gagal mengirim rujukan Obgyn:', error);
            alert('Rujukan gagal dikirim: ' + error.message);

        } finally {
            tombol.disabled = false;
            tombol.textContent = teksAwal;
        }
    }

    // =========================================================
    // SIMPAN TTV DAN SOAP KE DATABASE
    // =========================================================
    async function simpanKunjunganBaru() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        if (!ID_PENDAFTARAN) {
            alert('ID pendaftaran tidak ditemukan. Periksa data pendaftaran pada controller.');
            return;
        }

        const tombolSimpan = document.getElementById('btnSimpanKunjungan');

        if (tombolSimpan) {
            tombolSimpan.disabled = true;
            tombolSimpan.innerHTML =
                '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';
        }

        try {
            const ambilNilai = nama => {
                const el = document.querySelector(`[name="${nama}"]`);
                return el ? el.value.trim() : '';
            };

            const ttvPayload = {
                id_pendaftaran: ID_PENDAFTARAN,
                tekanan_darah: ambilNilai('td') || null,
                heart_rate: ambilNilai('hr') || null,
                spo2: ambilNilai('spo2') || null,
                suhu: ambilNilai('suhu') || null,
                respiratory_rate: ambilNilai('rr') || null,
                alergi: ambilNilai('alergi') || null,
                hpht: ambilNilai('hpht') || null,
                usia_kehamilan: ambilNilai('uk') || null
            };

            const penyakitIds = diagnosaTerpilih.penyakit.map(item => Number(item.id));
            const tindakanIds = diagnosaTerpilih.tindakan.map(item => Number(item.id));

            const assesmenPayload = {
                id_pendaftaran: Number(ID_PENDAFTARAN),
                subjective: ambilNilai('subjective'),
                objective: ambilNilai('objective'),
                assessment: ambilNilai('assessment'),
                plan: ambilNilai('plan'),
                penyakit_id: penyakitIds,
                tindakan_id: tindakanIds
            };

            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            // 1. Simpan TTV
            const hasilTtv = await fetch(URL_SIMPAN_TTV, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(ttvPayload)
            });

            const dataTtv = await bacaJson(hasilTtv, 'menyimpan TTV');

            if (!hasilTtv.ok) {
                const pesanValidasi = Object.values(dataTtv.errors || {})
                    .flat()
                    .join('\n');

                throw new Error(
                    pesanValidasi ||
                    dataTtv.message ||
                    'Gagal menyimpan TTV.'
                );
            }

            // 2. Simpan SOAP dan diagnosis/tindakan
            const hasilAssesmen = await fetch(URL_SIMPAN_ASSESMEN, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify(assesmenPayload)
            });

            const dataAssesmen = await bacaJson(hasilAssesmen, 'menyimpan asesmen');

            if (!hasilAssesmen.ok || dataAssesmen.success !== true) {
                const pesanValidasi = Object.values(dataAssesmen.errors || {})
                    .flat()
                    .join('\n');

                throw new Error(
                    pesanValidasi ||
                    dataAssesmen.message ||
                    'Gagal menyimpan asesmen.'
                );
            }

            // 3. Perbarui status kunjungan menjadi selesai
            const urlStatus = URL_UPDATE_STATUS.replace(
                '__ID__',
                encodeURIComponent(ID_PENDAFTARAN)
            );

            const hasilStatus = await fetch(urlStatus, {
                method: 'PATCH',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            });

            const dataStatus = await bacaJson(hasilStatus, 'memperbarui status');

            if (!hasilStatus.ok || !dataStatus.success) {
                throw new Error(
                    dataStatus.message || 'Gagal memperbarui status pemeriksaan.'
                );
            }

            // 4. Semua data sudah tersimpan: halaman boleh ditinggalkan.
            izinkanKeluar = true;

            alert('TTV dan asesmen berhasil disimpan ke database.');

            // Kembali ke daftar Poli Obgyn setelah penyimpanan berhasil
            window.location.href = URL_HALAMAN_OBGYN;

        } catch (error) {
            console.error('Gagal menyimpan kunjungan Obgyn:', error);

            alert('Data belum berhasil disimpan: ' + error.message);
        } finally {
            if (tombolSimpan) {
                tombolSimpan.disabled = false;
                tombolSimpan.innerHTML =
                    '<i class="fa-solid fa-floppy-disk"></i> Simpan';
            }
        }
    }

    // =========================================================
    // INFORMED CONSENT - TAMPILAN FRONT-END
    // =========================================================
    (function () {
        const d = new Date();
        const tgl = document.getElementById('icTanggal');

        if (tgl && !tgl.value) {
            tgl.value =
                d.getFullYear() + '-' +
                String(d.getMonth() + 1).padStart(2, '0') + '-' +
                String(d.getDate()).padStart(2, '0');
        }
    })();

    document.querySelectorAll('.ob-ic-isi-bebas').forEach(function (input) {
        input.addEventListener('input', function () {
            const cek = this.closest('tr').querySelector('input[type=checkbox]');

            if (cek) {
                cek.checked = this.value.trim() !== '';
            }
        });
    });

    const ID_TTD = ['ttdDokter', 'ttdPasien', 'ttdSaksi'];
    const ttdTerisi = {};

    ID_TTD.forEach(function (id) {
        const canvas = document.getElementById(id);

        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        let gambar = false;

        ttdTerisi[id] = false;

        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#000';

        function posisi(e) {
            const r = canvas.getBoundingClientRect();

            return {
                x: (e.clientX - r.left) * (canvas.width / r.width),
                y: (e.clientY - r.top) * (canvas.height / r.height)
            };
        }

        canvas.addEventListener('pointerdown', function (e) {
            gambar = true;
            ttdTerisi[id] = true;

            canvas.setPointerCapture(e.pointerId);

            const p = posisi(e);

            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.x + 0.1, p.y + 0.1);
            ctx.stroke();
        });

        canvas.addEventListener('pointermove', function (e) {
            if (!gambar) return;

            const p = posisi(e);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
        });

        ['pointerup', 'pointercancel'].forEach(function (ev) {
            canvas.addEventListener(ev, function () {
                gambar = false;
            });
        });
    });

    function hapusTtd(id) {
        const canvas = document.getElementById(id);

        if (!canvas) return;

        canvas.getContext('2d').clearRect(
            0, 0, canvas.width, canvas.height
        );

        ttdTerisi[id] = false;
    }

    function simpanInformedConsent() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        const wajib = [
            ['icDokter', 'Nama Dokter'],
            ['icPasien', 'Nama Pasien/ Keluarga'],
            ['icSaksi', 'Nama Saksi Klinik'],
            ['icTanggal', 'Tanggal Tindakan'],
            ['icPukul', 'Pukul']
        ];

        for (const [id, nama] of wajib) {
            const el = document.getElementById(id);

            if (!el || el.value.trim() === '') {
                alert('Isi ' + nama + ' terlebih dahulu.');

                if (el) el.focus();

                return;
            }
        }

        if (!ttdTerisi['ttdDokter']) {
            alert('Tanda tangan dokter (pemberi informasi) belum diisi.');
            return;
        }

        if (!ttdTerisi['ttdPasien']) {
            alert('Tanda tangan pasien/ keluarga belum diisi.');
            return;
        }

        const dataTtd = {};

        ID_TTD.forEach(function (id) {
            const canvas = document.getElementById(id);

            dataTtd[id] = ttdTerisi[id]
                ? canvas.toDataURL('image/png')
                : '';
        });

        const keputusanEl = document.querySelector(
            'input[name="informed_keputusan"]:checked'
        );

        const keputusan = keputusanEl ? keputusanEl.value : 'setuju';

        // Data belum dikirim ke database karena endpoint informed consent
        // dan tabel penyimpanannya belum disediakan.
        console.log('Data Informed Consent sementara:', {
            keputusan,
            dataTtd
        });

        alert(
            'Validasi Informed Consent berhasil. ' +
            'Data belum tersimpan ke database karena backend Informed Consent belum tersedia.'
        );
    }

    // =========================================================
    // INFORMED CONSENT - CETAK FORMULIR
    // Mengisi area cetak dari isian form, lalu membuka dialog print.
    // Di dialog print pilih "Save as PDF" atau printer (mis. Epson).
    // Cetak boleh dengan tanda tangan kosong (untuk ditandatangani manual).
    // =========================================================
    function buatKotak(terisi, ikutiTeks) {
        const kotak = document.createElement('span');

        kotak.className = 'icp-box';
        kotak.textContent = terisi ? '✓' : '';

        return kotak;
    }

    async function cetakInformedConsent() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        const nilai = id => (document.getElementById(id)?.value ?? '').trim();

        // 1. Tabel isi informasi + checklist
        const tbody = document.getElementById('ic-cetak-body');
        tbody.innerHTML = '';

        document.querySelectorAll('#tab-informed .ob-ic-table tbody tr').forEach(function (tr) {
            const sel = tr.querySelectorAll(':scope > td');
            const isiBebas = sel[1].querySelector('.ob-ic-isi-bebas');
            const cek = sel[2].querySelector('input[type=checkbox]');

            const baris = document.createElement('tr');

            const tdKategori = document.createElement('td');
            tdKategori.className = 'icp-kategori';
            tdKategori.textContent = sel[0].textContent.trim();

            const tdIsi = document.createElement('td');
            tdIsi.textContent = isiBebas
                ? (isiBebas.value.trim() || '-')
                : sel[1].textContent.trim();

            const tdCek = document.createElement('td');
            tdCek.className = 'icp-cek';
            tdCek.appendChild(buatKotak(cek && cek.checked));

            baris.appendChild(tdKategori);
            baris.appendChild(tdIsi);
            baris.appendChild(tdCek);
            tbody.appendChild(baris);
        });

        // 2. Keputusan setuju / menolak
        const keputusanEl = document.querySelector('input[name="informed_keputusan"]:checked');
        const keputusan = keputusanEl ? keputusanEl.value : 'setuju';
        const wadahKeputusan = document.getElementById('ic-cetak-keputusan');
        wadahKeputusan.innerHTML = '';

        [['setuju', 'Setuju'], ['menolak', 'Menolak']].forEach(function (opsi) {
            const pilihan = document.createElement('span');
            pilihan.className = 'icp-pilihan';
            pilihan.appendChild(buatKotak(keputusan === opsi[0]));
            pilihan.appendChild(document.createTextNode(opsi[1]));
            wadahKeputusan.appendChild(pilihan);
        });

        // 3. Tanggal & pukul
        const tanggal = nilai('icTanggal');
        const pukul = nilai('icPukul');

        document.getElementById('ic-cetak-tanggal').textContent = tanggal
            ? tanggalIndonesia(new Date(tanggal + 'T00:00:00'))
            : '...............';

        document.getElementById('ic-cetak-pukul').textContent = pukul
            ? pukul + ' WIB'
            : '.........';

        // 4. Nama & tanda tangan
        const petaTtd = [
            ['ttdDokter', 'ic-cetak-ttd-dokter', 'ic-cetak-nama-dokter', 'icDokter'],
            ['ttdPasien', 'ic-cetak-ttd-pasien', 'ic-cetak-nama-pasien', 'icPasien'],
            ['ttdSaksi', 'ic-cetak-ttd-saksi', 'ic-cetak-nama-saksi', 'icSaksi']
        ];

        const daftarGambar = [];

        petaTtd.forEach(function (item) {
            const [idCanvas, idGambar, idNama, idInput] = item;
            const wadah = document.getElementById(idGambar);

            wadah.innerHTML = '';

            if (ttdTerisi[idCanvas]) {
                const img = document.createElement('img');
                img.src = document.getElementById(idCanvas).toDataURL('image/png');
                img.alt = 'Tanda tangan';
                wadah.appendChild(img);
                daftarGambar.push(img);
            }

            const nama = nilai(idInput);
            document.getElementById(idNama).textContent = nama ? '( ' + nama + ' )' : '(                              )';
        });

        // Pastikan gambar tanda tangan sudah siap sebelum print
        await Promise.all(daftarGambar.map(img => img.decode().catch(() => {})));

        siapkanCetak('cetak-ic-area', 'cetak-ic-mode');

        window.print();
    }

    // =========================================================
    // TTV KE OBJECTIVE
    // Centang TIDAK aktif otomatis saat TTV diisi.
    // Centang hanya berubah (class "aktif") setelah di-klik.
    // =========================================================
    let vitalTerakhir = '';

    function isiObjektifDariVital() {
        const bagian = [];

        document.querySelectorAll(
            '.ob-vitals-grid .ob-vital-row'
        ).forEach(function (row) {
            const labelEl = row.querySelector('label');
            const inputEl = row.querySelector('input');

            if (!labelEl || !inputEl) return;

            const label = labelEl.textContent.trim();
            let nilai = inputEl.value.trim();

            if (inputEl.type === 'date' && nilai !== '') {
                const [tahun, bulan, hari] = nilai.split('-');
                nilai = `${hari}-${bulan}-${tahun}`;
            }

            const unitEl = row.querySelector('.ob-vital-unit');
            const unit = unitEl ? unitEl.textContent.trim() : '';

            if (nilai !== '') {
                bagian.push(
                    label + ': ' + nilai + (unit ? ' ' + unit : '')
                );
            }
        });

        if (bagian.length === 0) {
            alert('Isi minimal satu tanda vital terlebih dahulu.');
            return;
        }

        const teks = bagian.join(', ');
        const objektif = document.querySelector(
            'textarea[name="objective"]'
        );

        if (!objektif) return;

        let sisa = objektif.value;

        if (vitalTerakhir && sisa.startsWith(vitalTerakhir)) {
            sisa = sisa
                .slice(vitalTerakhir.length)
                .replace(/^\n/, '');
        }

        objektif.value = sisa ? teks + '\n' + sisa : teks;
        vitalTerakhir = teks;

        // Centang aktif HANYA setelah di-klik
        const tombolCentang = document.getElementById('btnCentangVital');
        if (tombolCentang) tombolCentang.classList.add('aktif');

        objektif.focus();
    }

    // =========================================================
    // PENCARIAN ICD DARI DATABASE
    // =========================================================
    const konfig = {
        penyakit: {
            input: 'cariPenyakit',
            dropdown: 'dropdownPenyakit',
            icon: 'checkPenyakit',
            list: 'listPenyakit',
            name: 'penyakit_id[]'
        },

        tindakan: {
            input: 'cariTindakan',
            dropdown: 'dropdownTindakan',
            icon: 'checkTindakan',
            list: 'listTindakan',
            name: 'tindakan_id[]'
        }
    };

    const pilihan = {
        penyakit: null,
        tindakan: null
    };

    // Isi dari diagnosis dan tindakan yang dikirim controller
    const diagnosaTerpilih = {
        penyakit: @json($diagnosisJson),
        tindakan: @json($tindakanJson)
    };

    const timerCari = {};
    const urutanCari = {
        penyakit: 0,
        tindakan: 0
    };

    function updateCentang(jenis) {
        const tombol = document.getElementById(konfig[jenis].icon);

        if (!tombol) return;

        tombol.classList.toggle(
            'aktif',
            pilihan[jenis] !== null
        );
    }

    function cariItem(jenis) {
        const k = konfig[jenis];
        const input = document.getElementById(k.input);
        const dropdown = document.getElementById(k.dropdown);

        if (!input || !dropdown) return;

        const keyword = input.value.trim();

        if (
            pilihan[jenis] &&
            input.value === pilihan[jenis].kode + ' — ' + pilihan[jenis].nama
        ) {
            return;
        }

        pilihan[jenis] = null;
        updateCentang(jenis);

        clearTimeout(timerCari[jenis]);

        if (keyword.length < 2) {
            tampilPesan(
                dropdown,
                keyword === '' ? null : 'Ketik minimal 2 karakter'
            );

            return;
        }

        timerCari[jenis] = setTimeout(async function () {
            const nomor = ++urutanCari[jenis];

            try {
                const res = await fetch(
                    URL_CARI + '/' + jenis + '?q=' +
                    encodeURIComponent(keyword),
                    {
                        headers: {
                            'Accept': 'application/json'
                        }
                    }
                );

                if (!res.ok) {
                    throw new Error('HTTP ' + res.status);
                }

                const data = await res.json();

                if (nomor !== urutanCari[jenis]) return;

                renderDropdown(jenis, data);

            } catch (e) {
                console.error('Pencarian gagal:', e);
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
        const dropdown = document.getElementById(
            konfig[jenis].dropdown
        );

        if (!Array.isArray(data) || data.length === 0) {
            tampilPesan(dropdown, 'Tidak ditemukan');
            return;
        }

        dropdown.innerHTML = '';

        data.forEach(function (item) {
            const div = document.createElement('div');
            div.className = 'ob-dropdown-item';

            const kode = document.createElement('b');
            kode.textContent = item.kode ?? '';

            div.appendChild(kode);
            div.appendChild(
                document.createTextNode(' — ' + (item.nama ?? ''))
            );

            div.addEventListener('click', function () {
                pilihItem(jenis, item);
            });

            dropdown.appendChild(div);
        });

        dropdown.style.display = 'block';
    }

    function pilihItem(jenis, item) {
        const k = konfig[jenis];

        pilihan[jenis] = item;

        document.getElementById(k.input).value =
            item.kode + ' — ' + item.nama;

        document.getElementById(k.dropdown).style.display = 'none';

        updateCentang(jenis);
    }

    function tambahDiagnosa(jenis) {
        const k = konfig[jenis];
        const item = pilihan[jenis];

        if (!item) {
            alert(
                'Pilih ' + jenis +
                ' dari daftar yang muncul terlebih dahulu.'
            );

            return;
        }

        if (
            diagnosaTerpilih[jenis].some(
                d => String(d.id) === String(item.id)
            )
        ) {
            alert('Item ini sudah ada di daftar.');
        } else {
            diagnosaTerpilih[jenis].push({
                id: item.id,
                kode: item.kode,
                nama: item.nama
            });
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

        if (!list) return;

        list.innerHTML = '';

        diagnosaTerpilih[jenis].forEach(function (item, urutan) {
            const baris = document.createElement('div');
            baris.className = 'ob-diagnosa-item';

            const teks = document.createElement('span');
            const kode = document.createElement('b');

            kode.textContent = item.kode ?? '';

            teks.appendChild(kode);
            teks.appendChild(
                document.createTextNode(' — ' + (item.nama ?? ''))
            );

            if (jenis === 'penyakit' && urutan === 0) {
                const badge = document.createElement('span');

                badge.textContent = 'Diagnosa Utama';
                badge.style.cssText =
                    'display:inline-block;margin-left:10px;' +
                    'padding:2px 10px;background:#c1121f;color:#fff;' +
                    'border-radius:10px;font-size:11px;font-weight:600;' +
                    'white-space:nowrap;vertical-align:middle;';

                teks.appendChild(badge);
            }

            baris.appendChild(teks);

            const hidden = document.createElement('input');

            hidden.type = 'hidden';
            hidden.name = k.name;
            hidden.value = item.id;

            baris.appendChild(hidden);

            const aksi = document.createElement('span');
            aksi.className = 'ob-diagnosa-aksi';

            const btn = document.createElement('button');

            btn.type = 'button';
            btn.className = 'ob-aksi-hapus';
            btn.title = 'Hapus';
            btn.setAttribute('aria-label', 'Hapus');
            btn.innerHTML = '<i class="fa-solid fa-trash-can"></i>';

            btn.addEventListener('click', function () {
                hapusDiagnosa(jenis, item.id);
            });

            aksi.appendChild(btn);
            baris.appendChild(aksi);
            list.appendChild(baris);
        });
    }

    function hapusDiagnosa(jenis, id) {
        diagnosaTerpilih[jenis] =
            diagnosaTerpilih[jenis].filter(
                d => String(d.id) !== String(id)
            );

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

    // Tampilkan diagnosis dan tindakan yang sudah tersimpan
    renderDiagnosa('penyakit');
    renderDiagnosa('tindakan');
    updateCentang('penyakit');
    updateCentang('tindakan');

    /* =========================================================
       CEGAH KELUAR HALAMAN SEBELUM ASESMEN DISIMPAN
       Berlaku bila:
       - pasien baru (kunjungan ini belum punya data asesmen), atau
       - ada perubahan TTV / SOAP / diagnosa / tindakan yang belum disimpan.

       - Tombol Back browser: ditahan, muncul peringatan.
       - Klik menu / link lain: dibatalkan, muncul peringatan.
       - Refresh / tutup tab: browser menampilkan konfirmasi.
       Setelah Simpan atau Rujuk berhasil, semua pembatasan dilepas.
       ========================================================= */
    const PESAN_BELUM_SIMPAN =
        'Data pasien belum disimpan.\n' +
        'Klik tombol Simpan terlebih dahulu sebelum meninggalkan halaman ini.';

    function ambilSnapshotForm() {
        const nilai = nama => (
            document.querySelector('[name="' + nama + '"]')?.value ?? ''
        ).trim();

        return JSON.stringify({
            ttv: [
                'td', 'hr', 'spo2', 'suhu', 'rr',
                'hpht', 'uk', 'alergi'
            ].map(nilai),
            soap: [
                'subjective', 'objective', 'assessment', 'plan'
            ].map(nilai),
            penyakit: diagnosaTerpilih.penyakit
                .map(d => String(d.id)).sort(),
            tindakan: diagnosaTerpilih.tindakan
                .map(d => String(d.id)).sort()
        });
    }

    // Kondisi form saat halaman dibuka.
    const snapshotAwal = ambilSnapshotForm();
    let guardHistoryAktif = false;

    function adaDataBelumDisimpan() {
        if (izinkanKeluar) return false;

        // Pasien baru: wajib Simpan dulu, walaupun form belum diubah.
        if (!SUDAH_ADA_ASSESMEN) return true;

        return ambilSnapshotForm() !== snapshotAwal;
    }

    // Tambah satu entri riwayat agar tombol Back bisa ditahan.
    function pasangGuardHistory() {
        if (guardHistoryAktif) return;

        history.pushState({ guardSimpan: true }, '', location.href);
        guardHistoryAktif = true;
    }

    function periksaGuard() {
        if (adaDataBelumDisimpan()) pasangGuardHistory();
    }

    // Pasien baru langsung dijaga sejak halaman dibuka.
    periksaGuard();

    document.addEventListener('input', periksaGuard);
    document.addEventListener('change', periksaGuard);
    document.addEventListener('click', function () {
        // Menangkap tambah/hapus diagnosa dan tindakan yang bukan input biasa.
        setTimeout(periksaGuard, 0);
    });

    // Tombol Back / Forward browser.
    window.addEventListener('popstate', function () {
        if (adaDataBelumDisimpan()) {
            alert(PESAN_BELUM_SIMPAN);
            history.pushState({ guardSimpan: true }, '', location.href);
        } else if (guardHistoryAktif) {
            guardHistoryAktif = false;
            history.back();
        }
    });

    // Klik link / menu sidebar ke halaman lain.
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');

        if (!link) return;

        const href = (link.getAttribute('href') || '').trim().toLowerCase();

        if (
            href === '' ||
            href.startsWith('#') ||
            href.startsWith('javascript:') ||
            link.target === '_blank'
        ) {
            return;
        }

        if (adaDataBelumDisimpan()) {
            e.preventDefault();
            e.stopPropagation();
            alert(PESAN_BELUM_SIMPAN);
        }
    }, true);

    // Refresh, tutup tab, atau mengetik alamat lain.
    window.addEventListener('beforeunload', function (e) {
        if (adaDataBelumDisimpan()) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>
@endsection