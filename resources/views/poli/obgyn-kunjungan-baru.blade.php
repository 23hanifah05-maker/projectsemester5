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
            gap: 4px;
            margin-top: 8px;
        }
        .ob-diagnosa-item {
            font-size: 13px;
            color: #333;
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
            ['HPHT', 'hpht', '', 'cth. 10-01-2026'],
            ['SpO2', 'spo2', '%', 'mis. 98'],
            ['Suhu', 'suhu', '°C', 'mis. 36.5'],
            ['RR', 'rr', 'x/menit', 'mis. 20'],
            ['UK', 'uk', 'minggu', 'mis. 12'],
        ];

        // Data untuk baris SOAP: [kode, name, tipe, placeholder]
        $soapRows = [
            ['S', 'subjective', 'textarea', 'Diagnosis Masuk :'],
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
                        <input type="text" name="{{ $name }}" placeholder="{{ $placeholder }}">
                        @if ($unit)<span class="ob-vital-unit">{{ $unit }}</span>@endif
                    </div>
                @endforeach
                <div class="ob-vital-row">
                    <label>Lainnya</label>
                    <input type="text" name="lainnya" placeholder="Catatan lainnya...">
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

                <div class="ob-soap-actions">
                    <button type="button" class="ob-rujuk-btn" onclick="rujukKeRadiologi()">
                        <i class="fa-solid fa-right-from-bracket"></i> Rujuk
                    </button>
                </div>
            </div>
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
        document.querySelectorAll('#tab-assessment input[type=text], #tab-assessment textarea')
            .forEach(el => el.value = '');
        vitalTerakhir = '';
        updateCentangVital();
        diagnosaTerpilih.penyakit = [];
        diagnosaTerpilih.tindakan = [];
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
            const nilai = row.querySelector('input').value.trim();
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

    const daftarPenyakit = [
        'Kehamilan Normal', 'Anemia pada Kehamilan', 'Preeklampsia', 'Eklampsia',
        'Hiperemesis Gravidarum', 'Abortus', 'Kehamilan Ektopik', 'Plasenta Previa',
        'Solusio Plasenta', 'Infeksi Saluran Kemih pada Kehamilan',
    ];

    const daftarTindakan = [
        'Pemeriksaan Kehamilan', 'Pemeriksaan USG', 'Pemeriksaan Laboratorium',
        'Konsultasi Kehamilan', 'Pemeriksaan Leopold', 'Pemeriksaan Denyut Jantung Janin',
        'Pemberian Terapi', 'Konsultasi Lanjutan', 'Rujukan Spesialis',
    ];

    // Centang hijau menyala selama kolom terisi, abu-abu jika kosong
    function updateCentang(jenis) {
        const inputId = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const iconId = jenis === 'penyakit' ? 'checkPenyakit' : 'checkTindakan';
        const terisi = document.getElementById(inputId).value.trim() !== '';
        document.getElementById(iconId).classList.toggle('aktif', terisi);
    }

    function cariItem(jenis) {
        const inputId = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const dropdownId = jenis === 'penyakit' ? 'dropdownPenyakit' : 'dropdownTindakan';
        const daftar = jenis === 'penyakit' ? daftarPenyakit : daftarTindakan;

        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        const keyword = input.value.trim().toLowerCase();

        updateCentang(jenis);
        dropdown.innerHTML = '';

        if (keyword === '') {
            dropdown.style.display = 'none';
            return;
        }

        const hasil = daftar.filter(item => item.toLowerCase().includes(keyword));

        if (hasil.length === 0) {
            dropdown.innerHTML = '<div class="ob-dropdown-item ob-dropdown-empty">Tidak ditemukan</div>';
        } else {
            hasil.forEach(item => {
                const div = document.createElement('div');
                div.className = 'ob-dropdown-item';
                div.textContent = item;
                div.onclick = () => pilihItem(jenis, item);
                dropdown.appendChild(div);
            });
        }

        dropdown.style.display = 'block';
    }

    function pilihItem(jenis, item) {
        const inputId = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const dropdownId = jenis === 'penyakit' ? 'dropdownPenyakit' : 'dropdownTindakan';

        document.getElementById(inputId).value = item;
        document.getElementById(dropdownId).style.display = 'none';
        updateCentang(jenis);
    }

    // ===== Daftar Penyakit & Tindakan yang sudah dicentang (tampil di bawah kolom) =====
    const diagnosaTerpilih = { penyakit: [], tindakan: [] };

    function tambahDiagnosa(jenis) {
        const inputId = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const dropdownId = jenis === 'penyakit' ? 'dropdownPenyakit' : 'dropdownTindakan';

        const input = document.getElementById(inputId);
        const nilai = input.value.trim();

        if (nilai === '') {
            alert('Pilih atau tuliskan ' + jenis + ' terlebih dahulu.');
            return;
        }

        if (!diagnosaTerpilih[jenis].includes(nilai)) {
            diagnosaTerpilih[jenis].push(nilai);
        }

        input.value = '';
        document.getElementById(dropdownId).style.display = 'none';
        updateCentang(jenis);
        renderDiagnosa(jenis);
    }

    function renderDiagnosa(jenis) {
        const listId = jenis === 'penyakit' ? 'listPenyakit' : 'listTindakan';
        const list = document.getElementById(listId);
        list.innerHTML = '';

        diagnosaTerpilih[jenis].forEach(function (item) {
            const baris = document.createElement('div');
            baris.className = 'ob-diagnosa-item';
            baris.textContent = item;
            list.appendChild(baris);
        });
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