@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Jantung')
@section('header-icon')
    <i class="fa-solid fa-stethoscope"></i>
@endsection
@section('header-title', 'Poli Jantung')

@section('extra-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/jantung-kunjungan-baru.css') }}?v={{ @filemtime(public_path('css/jantung-kunjungan-baru.css')) }}">
@endsection

@section('content')

    @php
        // Data pasien dikirim controller. Bila belum ada, pakai contoh dari desain.
        $pasien = $pasien ?? (object) [
            'no_rm' => 'N123456', 'nama_pasien' => 'Naila Safrina', 'jenis_kelamin' => 'Perempuan',
            'tempat_lahir' => 'Ngawi', 'tgl_lahir' => '2013-09-18', 'umur' => '13 Tahun', 'no_hp' => '082534636626',
        ];

        $tglLahir = !empty($pasien->tgl_lahir) ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') : '-';

        // Isian TTV (kosong bila belum ada)
        $nilaiTtv = fn ($k) => old($k, data_get($ttv ?? null, $k, ''));

        // Isian SOAP dibaca dari kolom assesmen masing-masing
        $nilai = fn ($k) => old($k, data_get($assesmen ?? null, $k, ''));

        $subjective = [
            'keluhan_utama' => 'Keluhan utama',
            'riwayat_penyakit_sekarang' => 'Riwayat penyakit sekarang',
            'riwayat_penyakit_dahulu' => 'Riwayat penyakit dahulu',
            'riwayat_penyakit_keluarga' => 'Riwayat penyakit keluarga',
            'riwayat_pengobatan' => 'Riwayat pengobatan',
            'riwayat_sosial' => 'Riwayat sosial',
            'riwayat_operasi' => 'Riwayat operasi',
            'riwayat_alergi' => 'Riwayat alergi',
            'riwayat_imunisasi' => 'Riwayat imunisasi',
        ];

        $nonFarmakologis = [
            'tindakan_medis' => 'Tindakan medis',
            'konseling_edukasi' => 'Konseling edukasi',
            'rencana_tindak_lanjut' => 'Rencana tindak lanjut',
            'diet' => 'Diet',
        ];

        // Data radiologi (opsional dari controller). Tab ini hanya untuk melihat.
        $radiologi = $radiologi ?? null;
        $radPilihan = ['Foto Thoraks', 'MRI', 'USG Abdomen', 'Rontgen', 'CT Scan'];
        $radPeriksa = (array) data_get($radiologi, 'pemeriksaan', []);
        $radLainnya = trim((string) data_get($radiologi, 'lainnya', ''));
        $radFile    = data_get($radiologi, 'file_scan');
        $radHasil   = (string) data_get($radiologi, 'hasil', '');
    @endphp

    {{-- ===== DATA PASIEN ===== --}}
    <div class="jt-card">
        <div class="jt-card-title">Data Pasien</div>

        <div class="jt-fields">
            <div class="jt-field"><span class="jt-label">No RM</span><span class="jt-value">{{ $pasien->no_rm ?? '-' }}</span></div>
            <div class="jt-field"><span class="jt-label">Tempat, Tgl Lahir</span><span class="jt-value">{{ $pasien->tempat_lahir ?? '-' }}/{{ $tglLahir }}</span></div>
            <div class="jt-field"><span class="jt-label">Nama</span><span class="jt-value">{{ $pasien->nama_pasien ?? '-' }}</span></div>
            <div class="jt-field"><span class="jt-label">Umur</span><span class="jt-value">{{ $pasien->umur ?? '-' }}</span></div>
            <div class="jt-field"><span class="jt-label">Jenis Kelamin</span><span class="jt-value">{{ $pasien->jenis_kelamin ?? '-' }}</span></div>
            <div class="jt-field"><span class="jt-label">No Hp</span><span class="jt-value">{{ $pasien->no_hp ?? '-' }}</span></div>
        </div>
    </div>

    {{-- ===== TAB ===== --}}
    <div class="jt-tabs" role="tablist">
        <button type="button" class="jt-tab active" data-tab="assesment" role="tab" onclick="gantiTab('assesment')">
            <i class="fa-solid fa-clipboard-list"></i> Assesment
        </button>
        <button type="button" class="jt-tab" data-tab="radiologi" role="tab" onclick="gantiTab('radiologi')">
            <i class="fa-solid fa-x-ray"></i> Radiologi
        </button>
        <button type="button" class="jt-tab" data-tab="resume" role="tab" onclick="gantiTab('resume')">
            <i class="fa-solid fa-file-medical"></i> Resume Medis
        </button>
    </div>

    <div class="jt-panel">

        {{-- ============ TAB ASSESMENT (bergulir) ============ --}}
        <div id="tab-assesment" class="jt-tab-content active">
            <input type="hidden" id="id_pendaftaran" value="{{ $id_pendaftaran ?? ($pendaftaran->id_pendaftaran ?? '') }}">

            {{-- TTV --}}
            <div class="jt-vitals">
                <div class="jt-vital"><label for="tekanan_darah">TD</label>
                    <input type="text" id="tekanan_darah" value="{{ $nilaiTtv('tekanan_darah') }}" placeholder="mis. 120/80" autocomplete="off">
                    <span class="jt-vital-unit">mmHg</span></div>
                <div class="jt-vital"><label for="heart_rate">HR</label>
                    <input type="number" id="heart_rate" value="{{ $nilaiTtv('heart_rate') }}" min="0" max="300" autocomplete="off">
                    <span class="jt-vital-unit">x/menit</span></div>
                <div class="jt-vital"><label for="spo2">SpO2</label>
                    <input type="number" id="spo2" value="{{ $nilaiTtv('spo2') }}" min="0" max="100" autocomplete="off">
                    <span class="jt-vital-unit">%</span></div>
                <div class="jt-vital"><label for="suhu">Suhu</label>
                    <input type="number" id="suhu" value="{{ $nilaiTtv('suhu') }}" min="20" max="50" step="0.1" autocomplete="off">
                    <span class="jt-vital-unit">°C</span></div>
                <div class="jt-vital"><label for="respiratory_rate">RR</label>
                    <input type="number" id="respiratory_rate" value="{{ $nilaiTtv('respiratory_rate') }}" min="0" max="100" autocomplete="off">
                    <span class="jt-vital-unit">x/menit</span></div>
                <div class="jt-vital"><label for="vital_lainnya">Lainnya</label>
                    <input type="text" id="vital_lainnya" placeholder="mis. GDS 110 mg/dL" autocomplete="off"></div>
                <input type="checkbox" id="chkVital" class="jt-vital-chk"
                       title="Masukkan TTV ke O (Objective) - Pemeriksaan tanda vital"
                       aria-label="Masukkan TTV ke Objective" onchange="toggleVital(this)">
            </div>

            {{-- SOAP --}}
            <div class="jt-soap-title">SOAP</div>

            {{-- S --}}
            <div class="jt-soap-row">
                <div class="jt-badge">S</div>
                <div class="jt-sub">
                    @foreach ($subjective as $kolom => $label)
                        <div class="jt-item">
                            <label for="f_{{ $kolom }}">{{ $loop->iteration }}. {{ $label }}</label>
                            <textarea id="f_{{ $kolom }}" data-soap="{{ $kolom }}" rows="1">{{ $nilai($kolom) }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- O --}}
            <div class="jt-soap-row">
                <div class="jt-badge">O</div>
                <div class="jt-sub">
                    <div class="jt-item">
                        <label for="f_ttv_objektif">1. Pemeriksaan tanda vital</label>
                        <textarea id="f_ttv_objektif" data-soap-ttv rows="1" placeholder="Klik centang di kotak TTV untuk menarik data TTV"></textarea>
                    </div>
                    <div class="jt-item">
                        <label for="f_pemeriksaan_fisik_lainnya">2. Pemeriksaan fisik lainnya</label>
                        <textarea id="f_pemeriksaan_fisik_lainnya" data-soap="pemeriksaan_fisik_lainnya" rows="1">{{ $nilai('pemeriksaan_fisik_lainnya') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- A --}}
            <div class="jt-soap-row">
                <div class="jt-badge">A</div>
                <div class="jt-sub">
                    <div class="jt-item">
                        <label for="f_dx_utama">1. Dx utama</label>
                        <textarea id="f_dx_utama" data-soap="dx_utama" rows="1">{{ $nilai('dx_utama') }}</textarea>
                    </div>
                    <div class="jt-item">
                        <label for="f_dx_sekunder">2. Dx Sekunder</label>
                        <textarea id="f_dx_sekunder" data-soap="dx_sekunder" rows="1">{{ $nilai('dx_sekunder') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- P --}}
            <div class="jt-soap-row">
                <div class="jt-badge">P</div>
                <div class="jt-sub">
                    <div class="jt-item">
                        <label for="f_resep_obat">1. Resep obat</label>
                        <div class="jt-resep">
                            <textarea id="f_resep_obat" data-soap="resep_obat" rows="2">{{ $nilai('resep_obat') }}</textarea>
                            <button type="button" class="jt-cetak-btn" onclick="cetakResep()">
                                <i class="fa-solid fa-print"></i> Cetak
                            </button>
                        </div>
                    </div>

                    <div class="jt-subhead">2. Non Farmakologis</div>
                    @foreach ($nonFarmakologis as $kolom => $label)
                        <div class="jt-item jt-bullet">
                            <label for="f_{{ $kolom }}">{{ $label }}</label>
                            <textarea id="f_{{ $kolom }}" data-soap="{{ $kolom }}" rows="1">{{ $nilai($kolom) }}</textarea>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- KODE ICD --}}
            <div class="jt-icd">
                <div class="jt-card-title">Kode ICD</div>

                <div class="jt-icd-grid">
                    <div class="jt-icd-col">
                        <label for="cariPenyakit">Penyakit</label>
                        <div class="jt-icd-row">
                            <div class="jt-search">
                                <input type="text" id="cariPenyakit" placeholder="Cari Kode Penyakit ...." autocomplete="off"
                                       oninput="cariItem('penyakit')"
                                       onkeydown="if (event.key === 'Enter') { event.preventDefault(); tambahItem('penyakit'); }">
                                <div class="jt-dropdown" id="dropdownPenyakit"></div>
                            </div>

                            <div class="jt-simbol" role="group" aria-label="Simbol ICD">
                                <button type="button" data-simbol="†" title="Dagger (†)" aria-pressed="false" onclick="ubahSimbol(this)">†</button>
                                <button type="button" data-simbol="*" title="Asterisk (*)" aria-pressed="false" onclick="ubahSimbol(this)">*</button>
                            </div>

                            <button type="button" class="jt-check" id="checkPenyakit" title="Tambahkan penyakit" aria-label="Tambahkan penyakit" onclick="tambahItem('penyakit')">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                        <div class="jt-dx-list" id="listPenyakit"></div>
                    </div>

                    <div class="jt-icd-col">
                        <label for="cariTindakan">Tindakan</label>
                        <div class="jt-icd-row">
                            <div class="jt-search">
                                <input type="text" id="cariTindakan" placeholder="Cari Kode Tindakan ...." autocomplete="off"
                                       oninput="cariItem('tindakan')"
                                       onkeydown="if (event.key === 'Enter') { event.preventDefault(); tambahItem('tindakan'); }">
                                <div class="jt-dropdown" id="dropdownTindakan"></div>
                            </div>

                            <button type="button" class="jt-check" id="checkTindakan" title="Tambahkan tindakan" aria-label="Tambahkan tindakan" onclick="tambahItem('tindakan')">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                        <div class="jt-dx-list" id="listTindakan"></div>
                    </div>
                </div>
            </div>

            <div class="jt-actions">
                <button type="button" class="jt-btn jt-btn-simpan" onclick="simpanKunjungan()">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
                <button type="button" class="jt-btn" onclick="bukaRujukan()">Rujuk</button>
            </div>
        </div>

        {{-- ============ TAB RADIOLOGI (hanya lihat) ============ --}}
        <div id="tab-radiologi" class="jt-tab-content">
            <p class="jt-note">Halaman ini hanya untuk melihat hasil radiologi.</p>

            <div class="jt-sub-title">Ceklist Periksa Radiologi</div>
            <div class="jt-rd-grid">
                @foreach ($radPilihan as $opt)
                    <label class="jt-rd-item">
                        <input type="checkbox" disabled {{ in_array($opt, $radPeriksa) ? 'checked' : '' }}> {{ $opt }}
                    </label>
                @endforeach
                <label class="jt-rd-item">
                    <input type="checkbox" disabled {{ $radLainnya !== '' ? 'checked' : '' }}> Lainnya
                    <input type="text" value="{{ $radLainnya }}" placeholder="-" readonly disabled>
                </label>
            </div>

            <div class="jt-sub-title">Scan Hasil</div>
            <div class="jt-rd-box">
                @if ($radFile)
                    @if (preg_match('/\.(jpe?g|png)$/i', $radFile))
                        <a href="{{ $radFile }}" target="_blank" rel="noopener">
                            <img src="{{ $radFile }}" alt="Scan hasil radiologi" class="jt-rd-scan">
                        </a>
                    @endif
                    <a href="{{ $radFile }}" target="_blank" rel="noopener">Buka scan hasil</a>
                @else
                    Belum ada scan hasil.
                @endif
            </div>

            <div class="jt-sub-title">Hasil Pemeriksaan Radiologi</div>
            <textarea class="jt-rd-hasil" rows="5" readonly placeholder="Belum ada hasil pemeriksaan radiologi.">{{ $radHasil }}</textarea>
        </div>

        {{-- ============ TAB RESUME MEDIS ============ --}}
        <div id="tab-resume" class="jt-tab-content">
            <div class="jt-rm-grid">
                <div class="jt-rm-col">
                    <div class="jt-rm-blok"><label for="rm_keluhan">Keluhan saat masuk</label><textarea id="rm_keluhan"></textarea></div>
                    <div class="jt-rm-blok"><label for="rm_fisik">Pemeriksaan fisik (objektif)</label><textarea id="rm_fisik"></textarea></div>
                    <div class="jt-rm-blok"><label for="rm_dx_utama">Diagnosa utama / akhir</label><textarea id="rm_dx_utama"></textarea></div>
                    <div class="jt-rm-blok"><label for="rm_dx_sekunder">Diagnosa sekunder</label><textarea id="rm_dx_sekunder"></textarea></div>
                </div>

                <div class="jt-rm-col">
                    <div class="jt-rm-blok"><label for="rm_tindakan">Tindakan sesuai ICD-9-CM</label><textarea id="rm_tindakan"></textarea></div>
                    <div class="jt-rm-blok"><label for="rm_terapi">Terapi obat yang diberikan</label><textarea id="rm_terapi"></textarea></div>
                    <div class="jt-rm-blok"><label for="rm_instruksi">Instruksi atau rencana tindak lanjut</label><textarea id="rm_instruksi"></textarea></div>
                    <div class="jt-rm-blok">
                        <label for="rm_prognosis">Prognosis</label>
                        <select id="rm_prognosis">
                            <option value="">Pilih prognosis</option>
                            <option>Ad bonam</option><option>Dubia ad bonam</option>
                            <option>Dubia ad malam</option><option>Ad malam</option>
                        </select>
                    </div>
                    <div class="jt-rm-blok">
                        <label for="rm_kondisi">Kondisi saat meninggalkan klinik</label>
                        <select id="rm_kondisi">
                            <option value="">Pilih kondisi</option>
                            <option>Sembuh</option><option>Membaik</option>
                            <option>Belum sembuh</option><option>Meninggal</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="jt-rm-footer">
                <div class="jt-rm-waktu">
                    <div><label for="rm_tanggal">Tanggal</label><input type="date" id="rm_tanggal"></div>
                    <div><label for="rm_jam">Jam</label><input type="time" id="rm_jam"></div>
                </div>
                <button type="button" class="jt-btn jt-btn-simpan" onclick="simpanResume()">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- ===== POPUP RUJUKAN ===== --}}
    <div class="jt-modal-overlay" id="jtRujukan" role="dialog" aria-modal="true" aria-labelledby="jtRujukanJudul">
        <div class="jt-modal">
            <div class="jt-modal-head">
                <span id="jtRujukanJudul">Rujuk Pasien</span>
                <button type="button" class="jt-modal-close" onclick="tutupRujukan()" aria-label="Tutup">&times;</button>
            </div>
            <div class="jt-modal-body">
                <div>
                    <label for="rjTujuan">Tujuan</label>
                    <select id="rjTujuan">
                        <option value="">-- Pilih Poli --</option>
                        <option value="saraf">Poli Saraf</option>
                        <option value="obgyn">Poli Obgyn</option>
                        <option value="jiwa">Poli Jiwa</option>
                        <option value="radiologi">Radiologi</option>
                    </select>
                </div>
                <div>
                    <label for="rjAlasan">Alasan rujuk</label>
                    <textarea id="rjAlasan"></textarea>
                </div>
                <div class="jt-actions">
                    <button type="button" class="jt-btn" onclick="tutupRujukan()">Batal</button>
                    <button type="button" class="jt-btn jt-btn-simpan" onclick="kirimRujukan()">Kirim rujukan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== AREA CETAK RESEP (tersembunyi, tampil saat print) ===== --}}
    <div id="jt-cetak-area" class="jt-cetak-only">
        <div class="jt-cetak-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik">
            <div>
                <h2>KLINIK RAWAT INAP MERAH PUTIH</h2>
                <p>Jl. Ronggo Warsito No. 98 A, Ngawi</p>
                <p>Telp: (0351) 745596</p>
            </div>
        </div>

        <table class="jt-cetak-info">
            <tr><td width="15%">Nama Pasien</td><td width="2%">:</td><td>{{ $pasien->nama_pasien ?? '-' }}</td></tr>
            <tr><td>No. R.M.</td><td>:</td><td>{{ $pasien->no_rm ?? '-' }}</td></tr>
            <tr><td>Umur / JK</td><td>:</td><td>{{ $pasien->umur ?? '-' }} / {{ $pasien->jenis_kelamin ?? '-' }}</td></tr>
            <tr><td>Pemberi Resep</td><td>:</td><td>Dokter Poli Jantung</td></tr>
        </table>

        <div class="jt-cetak-garis"></div>
        <div class="jt-cetak-judul">RESEP</div>
        <div class="jt-cetak-rp">R/</div>
        <div class="jt-cetak-isi" id="jt-cetak-isi"></div>

        <div class="jt-cetak-ttd">
            <p>Ngawi, <span id="jt-cetak-tanggal"></span></p>
            <p>dr. Poli Jantung</p>
        </div>
    </div>

@endsection

@section('extra-js')
<script>
    // ===== Data awal (dummy: belum ada penyimpanan ke database) =====
    const NO_RM_PASIEN = @json($pasien->no_rm ?? '');

    // Daftar kode contoh untuk pencarian. Ganti dengan fetch ke endpoint /cari-kode bila database siap.
    const DAFTAR_KODE = {
        penyakit: [
            { id: 9001, kode: 'I10',   nama: 'Essential (primary) hypertension' },
            { id: 9002, kode: 'I11.0', nama: 'Hypertensive heart disease with heart failure' },
            { id: 9003, kode: 'I20.0', nama: 'Unstable angina' },
            { id: 9004, kode: 'I20.9', nama: 'Angina pectoris, unspecified' },
            { id: 9005, kode: 'I21.9', nama: 'Acute myocardial infarction, unspecified' },
            { id: 9006, kode: 'I25.1', nama: 'Atherosclerotic heart disease' },
            { id: 9007, kode: 'I42.9', nama: 'Cardiomyopathy, unspecified' },
            { id: 9008, kode: 'I48',   nama: 'Atrial fibrillation and flutter' },
            { id: 9009, kode: 'I49.9', nama: 'Cardiac arrhythmia, unspecified' },
            { id: 9010, kode: 'I50.0', nama: 'Congestive heart failure' },
            { id: 9011, kode: 'R07.4', nama: 'Chest pain, unspecified' },
            { id: 9012, kode: 'R00.0', nama: 'Tachycardia, unspecified' }
        ],
        tindakan: [
            { id: 8001, kode: '89.52', nama: 'Electrocardiogram' },
            { id: 8002, kode: '88.72', nama: 'Diagnostic ultrasound of heart (echocardiography)' },
            { id: 8003, kode: '89.41', nama: 'Cardiovascular stress test using treadmill' },
            { id: 8004, kode: '89.50', nama: 'Ambulatory cardiac monitoring' },
            { id: 8005, kode: '99.60', nama: 'Cardiopulmonary resuscitation, not otherwise specified' },
            { id: 8006, kode: '37.22', nama: 'Left heart cardiac catheterization' }
        ]
    };

    const diagnosaTerpilih = {
        penyakit: @json($diagnosisTersimpan ?? []),
        tindakan: @json($tindakanTersimpan ?? [])
    };

    const konfig = {
        penyakit: { input: 'cariPenyakit', dropdown: 'dropdownPenyakit', check: 'checkPenyakit', list: 'listPenyakit' },
        tindakan: { input: 'cariTindakan',  dropdown: 'dropdownTindakan', check: 'checkTindakan', list: 'listTindakan' }
    };
    const pilihan = { penyakit: null, tindakan: null };

    // ===== Tab =====
    function gantiTab(tab) {
        document.querySelectorAll('.jt-tab').forEach(el => el.classList.toggle('active', el.dataset.tab === tab));
        document.querySelectorAll('.jt-tab-content').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.add('active');

        if (tab === 'resume') isiResumeOtomatis();
    }

    // ===== TTV -> Objective =====
    let vitalTerakhir = '';

    function bangunTeksVital() {
        const peta = [
            ['TD', 'tekanan_darah', 'mmHg'], ['HR', 'heart_rate', 'x/menit'], ['SpO2', 'spo2', '%'],
            ['Suhu', 'suhu', '°C'], ['RR', 'respiratory_rate', 'x/menit']
        ];
        const bagian = [];

        peta.forEach(function (p) {
            const v = document.getElementById(p[1]).value.trim();
            if (v !== '') bagian.push(p[0] + ': ' + v + ' ' + p[2]);
        });

        const lain = document.getElementById('vital_lainnya').value.trim();
        if (lain !== '') bagian.push(lain);

        return bagian.join(', ');
    }

    function terapkanVital(teks) {
        const kolom = document.getElementById('f_ttv_objektif');
        kolom.value = teks;
        vitalTerakhir = teks;
    }

    function toggleVital(cb) {
        const kolom = document.getElementById('f_ttv_objektif');

        if (!cb.checked) {
            if (kolom.value === vitalTerakhir) kolom.value = '';
            vitalTerakhir = '';
            return;
        }

        const teks = bangunTeksVital();
        if (teks === '') {
            alert('Isi minimal satu tanda vital terlebih dahulu.');
            cb.checked = false;
            return;
        }

        terapkanVital(teks);
        kolom.focus();
    }

    // Selama dicentang, isi O ikut berubah saat nilai TTV diubah
    document.addEventListener('input', function (e) {
        if (!e.target.closest('.jt-vitals') || e.target.type === 'checkbox') return;
        const chk = document.getElementById('chkVital');
        if (chk && chk.checked) terapkanVital(bangunTeksVital());
    });

    // ===== Simbol Dagger / Asterisk =====
    function simbolTerpilih() {
        return document.querySelector('.jt-simbol button.aktif')?.dataset.simbol || '';
    }

    function resetSimbol() {
        document.querySelectorAll('.jt-simbol button').forEach(function (b) {
            b.classList.remove('aktif');
            b.setAttribute('aria-pressed', 'false');
        });
    }

    function teksInputPenyakit(item) {
        return item.kode + simbolTerpilih() + ' — ' + item.nama;
    }

    function ubahSimbol(btn) {
        const aktifkan = !btn.classList.contains('aktif');
        resetSimbol();

        if (aktifkan) {
            btn.classList.add('aktif');
            btn.setAttribute('aria-pressed', 'true');
        }

        if (pilihan.penyakit) {
            document.getElementById('cariPenyakit').value = teksInputPenyakit(pilihan.penyakit);
        }
        document.getElementById('cariPenyakit').focus();
    }

    // ===== Pencarian & daftar kode ICD =====
    function perbaruiCentang(jenis) {
        document.getElementById(konfig[jenis].check).classList.toggle('aktif', pilihan[jenis] !== null);
    }

    function tampilPesan(dropdown, teks) {
        dropdown.innerHTML = '';
        if (teks === null) { dropdown.style.display = 'none'; return; }

        const div = document.createElement('div');
        div.className = 'jt-dropdown-item jt-dropdown-empty';
        div.textContent = teks;
        dropdown.appendChild(div);
        dropdown.style.display = 'block';
    }

    function cariItem(jenis) {
        const k = konfig[jenis];
        const dropdown = document.getElementById(k.dropdown);
        const kw = document.getElementById(k.input).value.trim().toLowerCase();

        pilihan[jenis] = null;
        perbaruiCentang(jenis);

        if (kw.length < 2) {
            tampilPesan(dropdown, kw === '' ? null : 'Ketik minimal 2 karakter');
            return;
        }

        const hasil = DAFTAR_KODE[jenis].filter(d => d.kode.toLowerCase().includes(kw) || d.nama.toLowerCase().includes(kw));

        if (!hasil.length) { tampilPesan(dropdown, 'Tidak ditemukan'); return; }

        dropdown.innerHTML = '';
        hasil.forEach(function (item) {
            const div = document.createElement('div');
            div.className = 'jt-dropdown-item';

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

        document.getElementById(k.input).value = (jenis === 'penyakit')
            ? teksInputPenyakit(item)
            : item.kode + ' — ' + item.nama;

        document.getElementById(k.dropdown).style.display = 'none';
        perbaruiCentang(jenis);
    }

    function tambahItem(jenis) {
        const k = konfig[jenis];
        const item = pilihan[jenis];

        if (!item) {
            alert('Pilih ' + jenis + ' dari daftar yang muncul terlebih dahulu.');
            return;
        }

        if (diagnosaTerpilih[jenis].some(d => String(d.id) === String(item.id))) {
            alert('Item ini sudah ada di daftar.');
        } else {
            diagnosaTerpilih[jenis].push(
                jenis === 'penyakit' ? Object.assign({}, item, { simbol: simbolTerpilih() }) : item
            );
        }

        pilihan[jenis] = null;
        document.getElementById(k.input).value = '';
        document.getElementById(k.dropdown).style.display = 'none';
        if (jenis === 'penyakit') resetSimbol();
        perbaruiCentang(jenis);
        renderDiagnosa(jenis);
    }

    function hapusItem(jenis, id) {
        diagnosaTerpilih[jenis] = diagnosaTerpilih[jenis].filter(d => String(d.id) !== String(id));
        renderDiagnosa(jenis);
    }

    function renderDiagnosa(jenis) {
        const list = document.getElementById(konfig[jenis].list);
        list.innerHTML = '';

        diagnosaTerpilih[jenis].forEach(function (item, index) {
            const baris = document.createElement('div');
            baris.className = 'jt-dx-item';

            const teks = document.createElement('span');
            const kode = document.createElement('b');
            kode.textContent = item.kode + (item.simbol || '');
            teks.appendChild(kode);
            teks.appendChild(document.createTextNode(' — ' + item.nama));

            // Penyakit pertama = Diagnosa Utama, berikutnya Sekunder
            if (jenis === 'penyakit') {
                const badge = document.createElement('span');
                badge.className = 'jt-badge-dx';
                badge.textContent = index === 0 ? 'Diagnosa Utama' : 'Diagnosa Sekunder';
                teks.appendChild(badge);
            }

            const hapus = document.createElement('button');
            hapus.type = 'button';
            hapus.className = 'jt-dx-hapus';
            hapus.title = 'Hapus';
            hapus.setAttribute('aria-label', 'Hapus');
            hapus.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
            hapus.onclick = function () { hapusItem(jenis, item.id); };

            baris.appendChild(teks);
            baris.appendChild(hapus);
            list.appendChild(baris);
        });
    }

    renderDiagnosa('penyakit');
    renderDiagnosa('tindakan');

    document.addEventListener('click', function (e) {
        if (e.target.closest('.jt-search')) return;
        ['dropdownPenyakit', 'dropdownTindakan'].forEach(function (id) {
            document.getElementById(id).style.display = 'none';
        });
    });

    // ===== Kumpulkan isian form =====
    function kumpulkanData() {
        const data = { no_rm: NO_RM_PASIEN, ttv: {}, soap: {}, penyakit: diagnosaTerpilih.penyakit, tindakan: diagnosaTerpilih.tindakan };

        ['tekanan_darah', 'heart_rate', 'spo2', 'suhu', 'respiratory_rate', 'vital_lainnya'].forEach(function (id) {
            data.ttv[id] = document.getElementById(id).value.trim();
        });

        document.querySelectorAll('textarea[data-soap]').forEach(function (el) {
            data.soap[el.dataset.soap] = el.value.trim();
        });
        data.soap.pemeriksaan_tanda_vital = document.getElementById('f_ttv_objektif').value.trim();

        return data;
    }

    // ===== Simpan (dummy: belum ke database) =====
    function simpanKunjungan() {
        if (!NO_RM_PASIEN) { alert('Data pasien tidak ditemukan.'); return; }

        console.log('Data kunjungan Poli Jantung (dummy):', kumpulkanData());
        alert('Data berhasil disimpan. (Dummy: belum tersimpan ke database)');
    }

    // ===== Cetak resep =====
    function cetakResep() {
        const resep = document.getElementById('f_resep_obat').value.trim();

        if (resep === '') {
            alert('Isi kolom Resep obat dulu sebelum mencetak resep.');
            return;
        }

        document.getElementById('jt-cetak-isi').innerText = resep;
        document.getElementById('jt-cetak-tanggal').innerText = new Date().toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });

        document.body.appendChild(document.getElementById('jt-cetak-area'));
        document.body.classList.add('jt-cetak-mode');
        window.print();
    }

    window.addEventListener('afterprint', function () {
        document.body.classList.remove('jt-cetak-mode');
    });

    // ===== Rujukan (dummy) =====
    function bukaRujukan() {
        if (!NO_RM_PASIEN) { alert('Data pasien tidak ditemukan.'); return; }
        document.getElementById('jtRujukan').classList.add('aktif');
    }

    function tutupRujukan() {
        document.getElementById('jtRujukan').classList.remove('aktif');
    }

    function kirimRujukan() {
        const tujuan = document.getElementById('rjTujuan').value;
        const alasan = document.getElementById('rjAlasan').value.trim();

        if (!tujuan) return alert('Pilih poli tujuan rujukan terlebih dahulu.');
        if (!alasan) return alert('Isi alasan rujuk terlebih dahulu.');

        console.log('Rujukan Poli Jantung (dummy):', { no_rm: NO_RM_PASIEN, tujuan: tujuan, alasan: alasan });
        alert('Rujukan berhasil dikirim. (Dummy: belum tersimpan ke database)');

        document.getElementById('rjTujuan').value = '';
        document.getElementById('rjAlasan').value = '';
        tutupRujukan();
    }

    document.getElementById('jtRujukan').addEventListener('click', function (e) {
        if (e.target === this) tutupRujukan();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') tutupRujukan();
    });

    // ===== Resume Medis =====
    // Terisi otomatis dari SOAP dan kode ICD selama belum diketik manual.
    const FIELD_RESUME = ['rm_keluhan', 'rm_fisik', 'rm_terapi', 'rm_instruksi', 'rm_dx_utama', 'rm_dx_sekunder', 'rm_tindakan'];

    FIELD_RESUME.forEach(function (id) {
        document.getElementById(id).addEventListener('input', function () { this.dataset.otomatis = ''; });
    });

    function isiResumeOtomatis() {
        const soap = id => (document.getElementById(id)?.value ?? '').trim();
        const teksKode = item => item.kode + (item.simbol || '') + ' — ' + item.nama;

        const isi = function (id, nilai) {
            const el = document.getElementById(id);
            if (el.value.trim() === '' || el.dataset.otomatis === '1') {
                el.value = nilai;
                el.dataset.otomatis = nilai ? '1' : '';
            }
        };

        const penyakit = diagnosaTerpilih.penyakit;

        isi('rm_keluhan', soap('f_keluhan_utama'));
        isi('rm_fisik', [soap('f_ttv_objektif'), soap('f_pemeriksaan_fisik_lainnya')].filter(Boolean).join('\n'));
        isi('rm_terapi', soap('f_resep_obat'));
        isi('rm_instruksi', soap('f_rencana_tindak_lanjut'));
        isi('rm_dx_utama', penyakit.length ? teksKode(penyakit[0]) : '');
        isi('rm_dx_sekunder', penyakit.slice(1).map(teksKode).join('\n'));
        isi('rm_tindakan', diagnosaTerpilih.tindakan.map(teksKode).join('\n'));
    }

    function simpanResume() {
        isiResumeOtomatis();

        const data = {};
        document.querySelectorAll('#tab-resume textarea, #tab-resume select, #tab-resume input').forEach(function (el) {
            data[el.id] = el.value.trim();
        });

        console.log('Resume Medis Poli Jantung (dummy):', data);
        alert('Resume Medis berhasil disimpan. (Dummy: belum tersimpan ke database)');
    }

    // Tanggal dan jam awal Resume Medis
    (function () {
        const d = new Date();
        const dua = n => String(n).padStart(2, '0');
        document.getElementById('rm_tanggal').value = d.getFullYear() + '-' + dua(d.getMonth() + 1) + '-' + dua(d.getDate());
        document.getElementById('rm_jam').value = dua(d.getHours()) + ':' + dua(d.getMinutes());
    })();
</script>
@endsection