@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Syaraf')
@section('header-icon', '🩺')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/syaraf-kunjungan-baru.css') }}">

    {{-- Ukuran kolom SOAP (disamakan dengan Obgyn), tombol Cetak di kolom Plan, checkbox TTV, dan daftar diagnosa --}}
    <style>
        .kb-soap-box {
            gap: 16px;
            padding: 18px 20px;
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
            color: #b81d24;
            font-weight: 600;
            font-size: 13px;
            padding: 7px 16px;
            border: 1px solid #b81d24;
            border-radius: 5px;
            cursor: pointer;
        }
        .kb-plan-wrap .kb-cetak-plan:hover {
            background: #b81d24;
            color: #fff;
        }

        /* Checkbox tanda vital (checkbox asli, bukan simbol) */
        .kb-vital-check {
            appearance: auto;
            -webkit-appearance: checkbox;
            width: 22px;
            height: 22px;
            margin: 0;
            flex: 0 0 auto;
            cursor: pointer;
            accent-color: #1a9c4a;
        }

        /* Diagnosa: baris rata atas supaya daftar di bawah kolom tidak menggeser kolom lain */
        .kb-diagnosa-row {
            align-items: flex-start;
        }

        /* Kotak centang Penyakit/Tindakan: kosong = abu-abu, terisi = hijau */
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
            background: #1e9e4a;
            border-color: #1e9e4a;
            color: #fff;
        }

        /* Daftar hasil yang muncul di bawah kolom */
        .kb-diagnosa-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-top: 8px;
        }
        .kb-diagnosa-item {
            font-size: 13px;
            color: #333;
        }
    </style>

    {{-- Area cetak resep (sama dengan Obgyn, hasil 1 lembar) --}}
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
                <div class="kb-vital-row">
                    <label>Lainnya</label>
                    <input type="text" placeholder="Catatan lainnya..." autocomplete="off">
                    <input type="checkbox" class="kb-vital-check" id="chkVital"
                           title="Centang untuk memasukkan tanda vital ke Objective"
                           onchange="toggleVitalKeObjektif(this)">
                </div>
            </div>
        </div>

        <hr class="kb-divider">

        {{-- ===== Kotak SOAP ===== --}}
        <div class="kb-soap-title">SOAP</div>
        <div class="kb-soap-box">
            <div class="kb-soap-row">
                <div class="kb-soap-badge">S</div>
                <textarea name="subjective" rows="4" placeholder="Diagnosis Masuk :"></textarea>
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
                        <input type="text" id="cariPenyakit" placeholder="Cari penyakit..." autocomplete="off"
                               oninput="cariItem('penyakit')" onfocus="cariItem('penyakit')"
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
                        <input type="text" id="cariTindakan" placeholder="Cari tindakan..." autocomplete="off"
                               oninput="cariItem('tindakan')" onfocus="cariItem('tindakan')"
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
            <button type="button" class="kb-btn kb-btn-simpan" onclick="simpanKunjunganBaru()">
                <i class="fa-solid fa-floppy-disk"></i> Simpan
            </button>
            <button type="button" class="kb-btn kb-btn-reset" onclick="resetForm()">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </button>
        </div>
    </div>

    <div id="tab-pathway" class="kb-tab-content">
        <p style="color:#888;">Clinical Pathway belum tersedia.</p>
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

        // Pindahkan area resep jadi anak langsung <body> agar elemen lain bisa disembunyikan total
        document.body.appendChild(area);
        document.body.classList.add('cetak-resep-mode');

        window.print();
    }

    // Kembalikan tampilan normal setelah dialog cetak ditutup
    window.addEventListener('afterprint', function () {
        document.body.classList.remove('cetak-resep-mode');
    });

    function simpanKunjunganBaru() {
        alert('Data kunjungan baru ini belum tersimpan ke database — masih dummy front-end. Beri tahu saya kalau mau disambungkan ke tabel kunjungan.');
    }

    // Teks tanda vital yang terakhir dimasukkan otomatis ke Objective
    let vitalTerakhir = '';

    function resetForm() {
        document.querySelectorAll('#tab-assesment input[type=text], #tab-assesment textarea').forEach(el => el.value = '');
        document.getElementById('chkVital').checked = false;
        vitalTerakhir = '';

        diagnosaTerpilih.penyakit = [];
        diagnosaTerpilih.tindakan = [];
        renderDiagnosa('penyakit');
        renderDiagnosa('tindakan');
        updateCentang('penyakit');
        updateCentang('tindakan');
        document.getElementById('dropdownPenyakit').style.display = 'none';
        document.getElementById('dropdownTindakan').style.display = 'none';
    }

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

    // ===== Dummy data Penyakit & Tindakan (nanti diganti dari database) =====
    const daftarPenyakit = [
        'Migrain', 'Vertigo', 'Epilepsi', 'Stroke Iskemik', 'Neuropati Perifer',
        'Parkinson', "Bell's Palsy", 'Meningitis', 'Trigeminal Neuralgia', 'Tension Type Headache',
    ];

    const daftarTindakan = [
        'Pemeriksaan EEG', 'Pemeriksaan EMG', 'CT Scan Kepala', 'MRI Otak',
        'Fisioterapi Syaraf', 'Terapi Injeksi', 'Konsultasi Lanjutan', 'Rawat Inap', 'Rujukan Spesialis',
    ];

    // Centang hijau menyala selama kolom terisi, abu-abu jika kosong
    function updateCentang(jenis) {
        const inputId = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const iconId  = jenis === 'penyakit' ? 'checkPenyakit' : 'checkTindakan';
        const terisi  = document.getElementById(inputId).value.trim() !== '';
        document.getElementById(iconId).classList.toggle('aktif', terisi);
    }

    function cariItem(jenis) {
        const inputId    = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const dropdownId = jenis === 'penyakit' ? 'dropdownPenyakit' : 'dropdownTindakan';
        const daftar     = jenis === 'penyakit' ? daftarPenyakit : daftarTindakan;

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
            dropdown.innerHTML = '<div class="kb-dropdown-item kb-dropdown-empty">Tidak ditemukan</div>';
        } else {
            hasil.forEach(item => {
                const div = document.createElement('div');
                div.className = 'kb-dropdown-item';
                div.textContent = item;
                div.onclick = () => pilihItem(jenis, item);
                dropdown.appendChild(div);
            });
        }

        dropdown.style.display = 'block';
    }

    function pilihItem(jenis, item) {
        const inputId    = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const dropdownId = jenis === 'penyakit' ? 'dropdownPenyakit' : 'dropdownTindakan';

        document.getElementById(inputId).value = item;
        document.getElementById(dropdownId).style.display = 'none';
        updateCentang(jenis);
    }

    // ===== Daftar Penyakit & Tindakan yang sudah dicentang (tampil di bawah kolom) =====
    const diagnosaTerpilih = { penyakit: [], tindakan: [] };

    function tambahDiagnosa(jenis) {
        const inputId    = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
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
            baris.className = 'kb-diagnosa-item';
            baris.textContent = item;
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