@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Obgyn')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/obgyn-kunjungan-baru.css') }}">
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
            ['S', 'subjective', 'input', 'Diagnosis Masuk :'],
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
        <div class="ob-tab active" onclick="gantiTab('assessment', event)">
            <i class="fa-solid fa-clipboard-list"></i> Assessment
        </div>
        <div class="ob-tab" onclick="gantiTab('radiologi', event)">
            <i class="fa-solid fa-x-ray"></i> Radiologi
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
                    <span class="ob-check-icon">✓</span>
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
                    @else
                        <textarea name="{{ $name }}" rows="1" placeholder="{{ $placeholder }}"></textarea>
                    @endif
                </div>
            @endforeach
        </div>

        <hr class="ob-divider">

        {{-- ===== DIAGNOSA ===== --}}
        <div class="ob-diagnosa-title">Diagnosa</div>
        <div class="ob-diagnosa-box">

            <div class="ob-diagnosa-actions">
                <button type="button" class="ob-cetak-btn" onclick="window.print()">
                    <i class="fa-solid fa-print"></i> Cetak
                </button>
                <button type="button" class="ob-rujuk-btn" onclick="alert('Fitur rujuk belum terhubung.')">
                    <i class="fa-solid fa-right-from-bracket"></i> Rujuk
                </button>
            </div>

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
                                       onfocus="cariItem('{{ $jenis }}')">
                                <span class="ob-search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <div class="ob-dropdown" id="dropdown{{ $label }}"></div>
                            </div>
                            <div class="ob-check-box">
                                <span class="ob-check-icon" id="check{{ $label }}" style="display:none;">✓</span>
                            </div>
                        </div>
                    </div>
                @endforeach
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

    function simpanKunjunganBaru() {
        alert('Data kunjungan baru ini belum tersimpan ke database — masih dummy front-end.');
    }

    function resetForm() {
        document.querySelectorAll('#tab-assessment input[type=text], #tab-assessment textarea')
            .forEach(el => el.value = '');
        document.getElementById('checkPenyakit').style.display = 'none';
        document.getElementById('checkTindakan').style.display = 'none';
        document.getElementById('dropdownPenyakit').style.display = 'none';
        document.getElementById('dropdownTindakan').style.display = 'none';
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

    function cariItem(jenis) {
        const inputId = jenis === 'penyakit' ? 'cariPenyakit' : 'cariTindakan';
        const dropdownId = jenis === 'penyakit' ? 'dropdownPenyakit' : 'dropdownTindakan';
        const daftar = jenis === 'penyakit' ? daftarPenyakit : daftarTindakan;

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
        const checkId = jenis === 'penyakit' ? 'checkPenyakit' : 'checkTindakan';

        document.getElementById(inputId).value = item;
        document.getElementById(dropdownId).style.display = 'none';
        document.getElementById(checkId).style.display = 'inline';
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