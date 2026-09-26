@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Syaraf')
@section('header-icon', '🩺')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/syaraf-kunjungan-baru.css') }}">
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

    <div id="tab-assesment" class="kb-tab-content active">

        {{-- ===== Kotak Vital Signs ===== --}}
        <div class="kb-vitals-box">
            <div class="kb-vitals">
                <div class="kb-vital-row">
                    <label>TD</label>
                    <input type="text" placeholder="mis. 120/80">
                    <span class="kb-vital-unit">mmHg</span>
                </div>
                <div class="kb-vital-row">
                    <label>HR</label>
                    <input type="text">
                    <span class="kb-vital-unit">x/menit</span>
                </div>
                <div class="kb-vital-row">
                    <label>SpO2</label>
                    <input type="text">
                    <span class="kb-vital-unit">%</span>
                </div>
                <div class="kb-vital-row">
                    <label>Suhu</label>
                    <input type="text">
                    <span class="kb-vital-unit">°C</span>
                </div>
                <div class="kb-vital-row">
                    <label>RR</label>
                    <input type="text">
                    <span class="kb-vital-unit">x/menit</span>
                </div>
                <div class="kb-vital-row">
                    <label>Lainnya</label>
                    <input type="text" placeholder="Catatan lainnya...">
                </div>
            </div>
        </div>

        <hr class="kb-divider">

        {{-- ===== Kotak SOAP ===== --}}
        <div class="kb-soap-title">SOAP</div>
        <div class="kb-soap-box">
            <div class="kb-soap-row">
                <div class="kb-soap-badge">S</div>
                <input type="text" placeholder="Diagnosis Masuk : ">
            </div>

            <div class="kb-soap-row">
                <div class="kb-soap-badge">O</div>
                <textarea rows="2" placeholder="Hasil pemeriksaan objektif..."></textarea>
            </div>

            <div class="kb-soap-row">
                <div class="kb-soap-badge">A</div>
                <textarea rows="2" placeholder="Assessment / analisa..."></textarea>
            </div>

            <div class="kb-soap-row">
                <div class="kb-soap-badge">P</div>
                <textarea rows="2" placeholder="Rencana/plan..."></textarea>
            </div>

            <div class="kb-soap-cetak-wrap">
                <button type="button" class="kb-cetak-btn" onclick="alert('Cetak belum terhubung ke fitur cetak.')">
                    <i class="fa-solid fa-print"></i> Cetak
                </button>
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
                               oninput="cariItem('penyakit')" onfocus="cariItem('penyakit')">
                        <span class="kb-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <div class="kb-dropdown" id="dropdownPenyakit"></div>
                    </div>
                    <div class="kb-check-box">
                        <span class="kb-check-icon" id="checkPenyakit" style="display:none;">✓</span>
                    </div>
                </div>
            </div>
            <div class="kb-search-field">
                <label>Tindakan</label>
                <div class="kb-search-inline">
                    <div class="kb-search-box">
                        <input type="text" id="cariTindakan" placeholder="Cari tindakan..." autocomplete="off"
                               oninput="cariItem('tindakan')" onfocus="cariItem('tindakan')">
                        <span class="kb-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <div class="kb-dropdown" id="dropdownTindakan"></div>
                    </div>
                    <div class="kb-check-box">
                        <span class="kb-check-icon" id="checkTindakan" style="display:none;">✓</span>
                    </div>
                </div>
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

    function simpanKunjunganBaru() {
        alert('Data kunjungan baru ini belum tersimpan ke database — masih dummy front-end. Beri tahu saya kalau mau disambungkan ke tabel kunjungan.');
    }

    function resetForm() {
        document.querySelectorAll('#tab-assesment input[type=text], #tab-assesment textarea').forEach(el => el.value = '');
        document.getElementById('checkPenyakit').style.display = 'none';
        document.getElementById('checkTindakan').style.display = 'none';
    }

    // ===== Dummy data Penyakit & Tindakan (nanti diganti dari database) =====
    const daftarPenyakit = [
        'Migrain', 'Vertigo', 'Epilepsi', 'Stroke Iskemik', 'Neuropati Perifer',
        'Parkinson', "Bell's Palsy", 'Meningitis', 'Trigeminal Neuralgia', 'Tension Type Headache',
    ];

    const daftarTindakan = [
        'Pemeriksaan EEG', 'Pemeriksaan EMG', 'CT Scan Kepala', 'MRI Otak',
        'Fisioterapi Syaraf', 'Terapi Injeksi', 'Konsultasi Lanjutan', 'Rawat Inap', 'Rujukan Spesialis',
    ];

    function cariItem(jenis) {
        const inputId    = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const dropdownId = jenis === 'penyakit' ? 'dropdownPenyakit' : 'dropdownTindakan';
        const daftar     = jenis === 'penyakit' ? daftarPenyakit : daftarTindakan;

        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        const keyword = input.value.trim().toLowerCase();

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
        const checkId    = jenis === 'penyakit' ? 'checkPenyakit' : 'checkTindakan';

        document.getElementById(inputId).value = item;
        document.getElementById(dropdownId).style.display = 'none';
        document.getElementById(checkId).style.display = 'inline';
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