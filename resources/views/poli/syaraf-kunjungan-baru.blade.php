@extends('layouts.app')

@section('title', 'Kunjungan Baru - Poli Syaraf')
@section('header-icon', '🩺')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/syaraf-kunjungan-baru.css') }}?v={{ @filemtime(public_path('css/syaraf-kunjungan-baru.css')) }}">
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

    <div class="kb-card" id="kartu-pasien-utama">
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
            <p class="kb-kosong">Data pasien tidak ditemukan.</p>
        @endif
    </div>

    <div class="kb-tabs">
        <div class="kb-tab active" onclick="gantiTab('assesment', event)"><i class="fa-solid fa-clipboard-list"></i> Assesment</div>
        <div class="kb-tab" onclick="gantiTab('radiologi', event)"><i class="fa-solid fa-x-ray"></i> Radiologi</div>
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
                    <td class="cp-print-aktivitas cp-print-total-label" colspan="3">Total</td>
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

    {{-- ===== TAB RADIOLOGI (isi sama dengan Poli Obgyn) ===== --}}
    @php
        $radiologiOptions = ['Foto Thoraks', 'MRI', 'USG Abdomen', 'Rontgen', 'CT Scan'];
    @endphp
    <div id="tab-radiologi" class="kb-tab-content">
        <div class="rd-card">
            <div class="rd-title">Form Pemeriksaan</div>
            <div class="rd-sub rd-sub-first">Ceklist Periksa Radiologi</div>

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
                <input type="file" id="scanHasil" name="scan_hasil" accept=".jpg,.jpeg,.png,.pdf" class="sembunyi"
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

    <div id="tab-pathway" class="kb-tab-content">
        <div class="cp-wrap">
            <div class="cp-scroll">
                <table class="cp-table">
                    <thead>
                        <tr>
                            <th class="cp-w-25">Aktivitas Pelayanan</th>
                            <th class="cp-w-40">Keterangan</th>
                            <th class="cp-w-20">Waktu</th>
                            <th class="cp-w-15">Tarif</th>
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
                            <td><div class="cp-rp">Rp <input type="text" id="cpTotal" class="cp-total-input" readonly></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="cp-footer">
                <button type="button" class="cp-cetak" onclick="cetakCP()"><i class="fa-solid fa-print"></i> Cetak</button>
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
                        <input type="text" id="rjPoliAsal" value="Poli Syaraf" readonly>
                    </div>
                    <div class="rj-field">
                        <label>Tujuan</label>
                        <select id="rjTujuan">
                            <option value="">-- Pilih Poli --</option>
                            <option>Poli Obgyn</option>
                            <option>Poli Jantung</option>
                            <option>Poli Jiwa</option>
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
                    <p class="kb-kosong">Data pasien tidak ditemukan.</p>
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
                            <input type="text" id="rjLainnya" placeholder="(tuliskan)" disabled>
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
    // Ditulis sebagai string biasa supaya editor tidak menandainya error;
    // string kosong dianggap null.
    const NO_RM_PASIEN = "{{ $pasien->no_rm ?? '' }}" || null;

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
        const tgl = String(d.getDate()).padStart(2, '0') + '-' +
                    String(d.getMonth() + 1).padStart(2, '0') + '-' +
                    d.getFullYear();
        const jam = String(d.getHours()).padStart(2, '0') + ':' +
                    String(d.getMinutes()).padStart(2, '0');
        return tgl + ' ' + jam;
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

    // ===== Rujukan: buka / tutup form =====
    function rujukPasien() {
        if (!NO_RM_PASIEN) {
            alert('Data pasien tidak ditemukan.');
            return;
        }

        // Tanggal rujuk otomatis hari ini (masih bisa diubah)
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

    // Kolom "Lainnya" pada form rujukan aktif hanya kalau dicentang
    const rjCekLainnya = document.getElementById('rjCekLainnya');
    const rjLainnya = document.getElementById('rjLainnya');
    if (rjCekLainnya) {
        rjCekLainnya.addEventListener('change', function () {
            rjLainnya.disabled = !this.checked;
            if (!this.checked) rjLainnya.value = '';
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

        // Reset form lalu kembali ke halaman pemeriksaan
        ['rjDokter', 'rjDiagnosa', 'rjAlasan', 'rjLainnya'].forEach(id => document.getElementById(id).value = '');
        document.getElementById('rjTujuan').value = '';
        document.querySelectorAll('#view-rujukan input[type=checkbox]').forEach(cb => cb.checked = false);
        rjLainnya.disabled = true;
        tutupRujukan();
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