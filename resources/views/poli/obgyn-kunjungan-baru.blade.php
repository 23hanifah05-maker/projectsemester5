@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Obgyn')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/obgyn-kunjungan-baru.css') }}?v={{ @filemtime(public_path('css/obgyn-kunjungan-baru.css')) }}">

    {{-- CSS kotak tanda tangan (langsung di sini, tidak perlu edit file CSS) --}}
    <style>
        .ob-ttd-wrap {
            display: flex;
            gap: 24px;
            margin: 24px 0 8px;
            padding: 20px;
            border: 1px solid #e5e5e5;
            border-radius: 6px;
        }
        .ob-ttd-item { flex: 1; text-align: center; }
        .ob-ttd-judul { font-weight: 600; font-size: 14px; margin-bottom: 8px; }
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
        .ob-ttd-aksi { text-align: right; margin-top: 16px; }
        @media (max-width: 900px) {
            .ob-ttd-wrap { flex-direction: column; }
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

        // Baris tabel Informed Consent: [kategori, isi informasi]
        $informedRows = [
            ['Diagnosis/ Tindakan',    'KB IUD'],
            ['Tindakan Kedokteran',    'Pemasangan IUD'],
            ['Indikasi Tindakan',      'Mengatur jumlah kelahiran bayi'],
            ['Tata Cara',              'Pengukuran rahim, penyiapan iud, persiapan alat'],
            ['Tujuan',                 'Mencegah kehamilan dan mengatur jarak kelahiran'],
            ['Manfaat',                'Mencegah kehamilan jangka panjang'],
            ['Risiko',                 'Kram perut, perdarahan (flek), atau infeksi rahim'],
            ['Komplikasi',             'IUD bergeser/ keluar, infeksi, panggul berat, robekan rahim dan barang hilang/ putus'],
            ['Prognosis',              'Dubia ad sanam'],
            ['Alternatif',             'KB yang lain'],
            ['Pertimbangan Pelayanan', '-'],
            ['Lain-lain',              '-'],
        ];
    @endphp

    {{-- ===== DATA PASIEN ===== --}}
    <div class="ob-card" id="kartu-pasien-utama">
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
            <p class="teks-kosong">Data pasien tidak ditemukan.</p>
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
        <div class="ob-tab" id="ob-tab-btn-informed" onclick="gantiTab('informed', event)">
            <i class="fa-solid fa-file-signature"></i> Informed Consent
        </div>
    </div>

    {{-- ===== AREA CETAK RESEP (tersembunyi, hanya muncul saat print, hasil 1 lembar) ===== --}}
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
            <button type="button" class="ob-btn ob-btn-reset ob-btn-rujuk" onclick="rujukPasien()">
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
                <input type="file" id="scanHasil" name="scan_hasil" accept=".jpg,.jpeg,.png,.pdf" class="sembunyi">
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

    {{-- ===== TAB INFORMED CONSENT ===== --}}
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
                            @php $bisaDiisi = in_array($kategori, ['Pertimbangan Pelayanan', 'Lain-lain']); @endphp
                            <tr>
                                <td>{{ $kategori }}</td>
                                <td>
                                    @if ($bisaDiisi)
                                        <input type="text" class="ob-ic-isi-bebas" name="informed_isi[{{ $kategori }}]"
                                               autocomplete="off" placeholder="Tuliskan di sini..."
                                               style="width:100%;box-sizing:border-box;padding:6px 10px;border:1px solid #ccc;border-radius:4px;font:inherit;">
                                    @else
                                        {{ $isi }}
                                    @endif
                                </td>
                                <td class="ob-ic-cek">
                                    <input type="checkbox" name="informed_cek[]" value="{{ $kategori }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="ob-ic-persetujuan">
                <div class="ob-ic-subtitle">Pernyataan Persetujuan dan/ Penolakan</div>
                <div class="ob-ic-radio">
                    <label><input type="radio" name="informed_keputusan" value="setuju" checked> Setuju</label>
                    <label><input type="radio" name="informed_keputusan" value="menolak"> Menolak</label>
                </div>
                <div class="ob-ic-pernyataan">
                    Dengan ini menyatakan telah memahami informasi yang telah diberikan.
                </div>

                <div class="ob-ic-form">
                    <div class="ob-ic-field">
                        <label>Nama Dokter</label>
                        <div class="ob-ic-input-icon">
                            <input type="text" id="icDokter" name="informed_dokter" autocomplete="off">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                    <div class="ob-ic-field">
                        <label>Nama Pasien/ Keluarga</label>
                        <input type="text" id="icPasien" name="informed_pasien" value="{{ $pasien->nama_pasien ?? '' }}" autocomplete="off">
                    </div>
                    <div class="ob-ic-field">
                        <label>Nama Saksi Klinik</label>
                        <input type="text" id="icSaksi" name="informed_saksi" autocomplete="off">
                    </div>
                    <div class="ob-ic-field">
                        <label>Tanggal Tindakan</label>
                        <input type="date" id="icTanggal" name="informed_tanggal">
                    </div>
                    <div class="ob-ic-field">
                        <label>Pukul</label>
                        <input type="time" id="icPukul" name="informed_pukul">
                    </div>
                </div>

                {{-- ===== KOTAK TANDA TANGAN ===== --}}
                <div class="ob-ttd-wrap">
                    <div class="ob-ttd-item">
                        <div class="ob-ttd-judul">Pemberi Informasi (Dokter)</div>
                        <canvas id="ttdDokter" class="ob-ttd-canvas" width="500" height="200"></canvas>
                        <a href="#" class="ob-ttd-hapus" onclick="hapusTtd('ttdDokter'); return false;">Hapus tanda tangan</a>
                    </div>
                    <div class="ob-ttd-item">
                        <div class="ob-ttd-judul">Tanda Tangan Pasien/ Keluarga</div>
                        <canvas id="ttdPasien" class="ob-ttd-canvas" width="500" height="200"></canvas>
                        <a href="#" class="ob-ttd-hapus" onclick="hapusTtd('ttdPasien'); return false;">Hapus tanda tangan</a>
                    </div>
                    <div class="ob-ttd-item">
                        <div class="ob-ttd-judul">Saksi Klinik</div>
                        <canvas id="ttdSaksi" class="ob-ttd-canvas" width="500" height="200"></canvas>
                        <a href="#" class="ob-ttd-hapus" onclick="hapusTtd('ttdSaksi'); return false;">Hapus tanda tangan</a>
                    </div>
                </div>

                <div class="ob-ttd-aksi">
                    <button type="button" class="ob-btn ob-btn-simpan" onclick="simpanInformedConsent()">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== FORM RUJUKAN (muncul saat tombol Rujuk diklik) ===== --}}
    <div id="view-rujukan">

        <div class="rj-box">
            <div class="rj-back">
                <button type="button" onclick="tutupRujukan()" aria-label="Kembali"><i class="fa-solid fa-arrow-left"></i></button>
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
                            <option>Poli Syaraf</option>
                            <option>Poli Jantung</option>
                            <option>Poli Jiwa</option>
                            <option>Radiologi</option>
                        </select>
                    </div>
                    <div class="rj-field">
                        <label>Dokter</label>
                        <input type="text" id="rjDokter" autocomplete="off">
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
                        <div class="rj-field"><label>No RM</label><input type="text" value="{{ $pasien->no_rm }}" readonly></div>
                        <div class="rj-field"><label>Tempat, Tgl Lahir</label><input type="text" value="{{ $pasien->tempat_lahir }}, {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') }}" readonly></div>
                        <div class="rj-field"><label>Nama</label><input type="text" value="{{ $pasien->nama_pasien }}" readonly></div>
                        <div class="rj-field"><label>Umur</label><input type="text" value="{{ $pasien->umur }}" readonly></div>
                        <div class="rj-field"><label>Jenis Kelamin</label><input type="text" value="{{ $pasien->jenis_kelamin }}" readonly></div>
                        <div class="rj-field"><label>No Hp</label><input type="text" value="{{ $pasien->no_hp }}" readonly></div>
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
                                <input type="checkbox" name="rujuk_radiologi[]" value="{{ $opt }}"> {{ $opt }}
                            </label>
                        @endforeach
                        <label class="rj-cek-item">
                            <input type="checkbox" id="rjCekLainnya" name="rujuk_radiologi[]" value="Lainnya"> Lainnya
                            <input type="text" id="rjLainnya" placeholder="(tuliskan)" autocomplete="off">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="rj-actions">
            <button type="button" class="rj-kirim" onclick="kirimRujukan()">KIRIM RUJUKAN</button>
        </div>
    </div>

@endsection

@section('extra-js')
<script>
    // No. RM pasien yang sedang diperiksa (null kalau pasien tidak ditemukan)
    const NO_RM_PASIEN = "{{ $pasien->no_rm ?? '' }}" || null;

    // Kunci penyimpanan status periksa
    const KUNCI_STATUS_PERIKSA = 'obgyn_status_periksa';

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

        document.body.appendChild(area);
        document.body.classList.add('cetak-resep-mode');

        window.print();
    }

    window.addEventListener('afterprint', function () {
        document.body.classList.remove('cetak-resep-mode');
    });

    // ===== Rujukan: buka / tutup form =====
    function rujukPasien() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        const tgl = document.getElementById('rjTanggal');
        if (!tgl.value) {
            const d = new Date();
            tgl.value = d.getFullYear() + '-' +
                        String(d.getMonth() + 1).padStart(2, '0') + '-' +
                        String(d.getDate()).padStart(2, '0');
        }

        document.body.classList.add('mode-rujukan');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function tutupRujukan() {
        document.body.classList.remove('mode-rujukan');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ===== Rujukan: kolom "Lainnya" bisa langsung diketik =====
    const rjCekLainnya = document.getElementById('rjCekLainnya');
    const rjLainnya = document.getElementById('rjLainnya');

    if (rjCekLainnya && rjLainnya) {
        // centang "Lainnya" -> kursor langsung ke kolom; hapus centang -> kolom dikosongkan
        rjCekLainnya.addEventListener('change', function () {
            if (this.checked) {
                rjLainnya.focus();
            } else {
                rjLainnya.value = '';
            }
        });

        // mengetik di kolom -> kotak "Lainnya" otomatis tercentang
        rjLainnya.addEventListener('input', function () {
            rjCekLainnya.checked = this.value.trim() !== '';
        });
    }

    function kirimRujukan() {
        if (document.getElementById('rjTujuan').value === '') {
            alert('Pilih poli tujuan rujukan terlebih dahulu.');
            return;
        }
        if (document.getElementById('rjAlasan').value.trim() === '') {
            alert('Isi alasan rujuk terlebih dahulu.');
            return;
        }

        // TODO: kirim ke backend. Sementara masih dummy front-end.
        alert('Rujukan berhasil dikirim.');

        // Reset form rujukan
        ['rjDokter', 'rjDiagnosa', 'rjAlasan', 'rjLainnya', 'rjTanggal'].forEach(function (id) {
            document.getElementById(id).value = '';
        });
        document.getElementById('rjTujuan').value = '';
        document.querySelectorAll('#view-rujukan input[type=checkbox]').forEach(function (cb) {
            cb.checked = false;
        });

        // Kembali ke halaman Assessment (pastikan tab Assessment yang aktif)
        document.querySelectorAll('.ob-tab').forEach(function (el, i) {
            el.classList.toggle('active', i === 0);
        });
        document.querySelectorAll('.ob-tab-content').forEach(function (el) {
            el.classList.toggle('active', el.id === 'tab-assessment');
        });

        tutupRujukan();
    }

    // Klik Simpan: status pasien berubah dari "Periksa" menjadi "Selesai"
    function simpanKunjunganBaru() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        try {
            const status = JSON.parse(localStorage.getItem(KUNCI_STATUS_PERIKSA)) || {};
            const kunci = String(NO_RM_PASIEN).trim().toUpperCase();
            status[kunci] = 'selesai';
            localStorage.setItem(KUNCI_STATUS_PERIKSA, JSON.stringify(status));

            const waktu = JSON.parse(localStorage.getItem('obgyn_waktu_selesai')) || {};
            waktu[kunci] = Date.now();
            localStorage.setItem('obgyn_waktu_selesai', JSON.stringify(waktu));
        } catch (e) {
            console.error(e);
        }

        alert('Data kunjungan berhasil disimpan. Status pasien berubah menjadi Selesai.');

        window.location.href = "{{ route('poli.obgyn') }}";
    }

    // ===== Informed Consent =====
    // Tanggal tindakan otomatis hari ini (masih bisa diubah)
    (function () {
        const d = new Date();
        const tgl = document.getElementById('icTanggal');
        if (tgl && !tgl.value) {
            tgl.value = d.getFullYear() + '-' +
                        String(d.getMonth() + 1).padStart(2, '0') + '-' +
                        String(d.getDate()).padStart(2, '0');
        }
    })();

    // Baris Informed Consent yang bisa diisi: centang otomatis saat diisi
    document.querySelectorAll('.ob-ic-isi-bebas').forEach(function (input) {
        input.addEventListener('input', function () {
            const cek = this.closest('tr').querySelector('input[type=checkbox]');
            if (cek) cek.checked = this.value.trim() !== '';
        });
    });

    // ===== Tanda tangan (gambar di kotak pakai mouse / jari / pen) =====
    const ID_TTD = ['ttdDokter', 'ttdPasien', 'ttdSaksi'];
    const ttdTerisi = {};   // true = sudah ada coretan

    ID_TTD.forEach(function (id) {
        const canvas = document.getElementById(id);
        const ctx = canvas.getContext('2d');
        let gambar = false;
        ttdTerisi[id] = false;

        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#000';

        // posisi kursor -> koordinat canvas
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
            canvas.addEventListener(ev, function () { gambar = false; });
        });
    });

    function hapusTtd(id) {
        const canvas = document.getElementById(id);
        canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
        ttdTerisi[id] = false;
    }

    function simpanInformedConsent() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        const wajib = [
            ['icDokter',  'Nama Dokter'],
            ['icPasien',  'Nama Pasien/ Keluarga'],
            ['icSaksi',   'Nama Saksi Klinik'],
            ['icTanggal', 'Tanggal Tindakan'],
            ['icPukul',   'Pukul'],
        ];
        for (const [id, nama] of wajib) {
            const el = document.getElementById(id);
            if (el.value.trim() === '') {
                alert('Isi ' + nama + ' terlebih dahulu.');
                el.focus();
                return;
            }
        }

        // Tanda tangan dokter & pasien/keluarga wajib (saksi opsional)
        if (!ttdTerisi['ttdDokter']) {
            alert('Tanda tangan dokter (pemberi informasi) belum diisi.');
            return;
        }
        if (!ttdTerisi['ttdPasien']) {
            alert('Tanda tangan pasien/ keluarga belum diisi.');
            return;
        }

        // Hasil tanda tangan dalam bentuk gambar (siap dikirim ke backend nanti)
        const dataTtd = {};
        ID_TTD.forEach(function (id) {
            dataTtd[id] = ttdTerisi[id] ? document.getElementById(id).toDataURL('image/png') : '';
        });

        const keputusan = document.querySelector('input[name="informed_keputusan"]:checked').value;

        // TODO: kirim ke backend (termasuk dataTtd). Sementara masih dummy front-end.
        alert('Informed Consent berhasil disimpan (' + (keputusan === 'setuju' ? 'Setuju' : 'Menolak') + ').');
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

        // Reset tab Informed Consent
        document.querySelectorAll('#tab-informed input[type=checkbox]').forEach(cb => cb.checked = false);
        ['icDokter', 'icSaksi', 'icPukul'].forEach(id => document.getElementById(id).value = '');
        document.querySelectorAll('.ob-ic-isi-bebas').forEach(el => el.value = '');
        ID_TTD.forEach(hapusTtd);
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

        let sisa = objektif.value;
        if (vitalTerakhir && sisa.startsWith(vitalTerakhir)) {
            sisa = sisa.slice(vitalTerakhir.length).replace(/^\n/, '');
        }

        objektif.value = sisa ? teks + '\n' + sisa : teks;
        vitalTerakhir = teks;

        objektif.focus();
    }

    // ===== Pencarian ICD dari database (kode_diagnosis & kode_tindakan) =====
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

        // Teks masih sama dengan item yang dipilih (mis. saat fokus ulang): jangan batalkan pilihan
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

        diagnosaTerpilih[jenis].forEach(function (item, urutan) {
            const baris = document.createElement('div');
            baris.className = 'ob-diagnosa-item';

            const teks = document.createElement('span');
            const kode = document.createElement('b');
            kode.textContent = item.kode;
            teks.appendChild(kode);
            teks.appendChild(document.createTextNode(' — ' + item.nama));

            // Diagnosa pertama yang dicentang otomatis menjadi Diagnosa Utama
            if (jenis === 'penyakit' && urutan === 0) {
                const badge = document.createElement('span');
                badge.textContent = 'Diagnosa Utama';
                badge.style.cssText = 'display:inline-block;margin-left:10px;padding:2px 10px;background:#c1121f;color:#fff;border-radius:10px;font-size:11px;font-weight:600;white-space:nowrap;vertical-align:middle;';
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
            btn.onclick = function () { hapusDiagnosa(jenis, item.id); };
            aksi.appendChild(btn);
            baris.appendChild(aksi);

            list.appendChild(baris);
        });
    }

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