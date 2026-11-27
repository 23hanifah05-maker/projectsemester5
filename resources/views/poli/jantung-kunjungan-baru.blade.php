@extends('layouts.app')

@section('title', 'Kunjungan Poli Jantung - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-stethoscope"></i>
@endsection
@section('header-title', 'Poli Jantung')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/jantung-kunjungan-baru.css') }}?v={{ @filemtime(public_path('css/jantung-kunjungan-baru.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/resume-medis.css') }}?v={{ @filemtime(public_path('css/resume-medis.css')) }}">
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai bila controller belum mengirim $pasien.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien)) {
            $dummy = [
                'E000031' => ['Hendra Wijaya',     'Laki-laki', '1970-04-15', '081234560001'],
                'E000032' => ['Lestari Wulandari', 'Perempuan', '1968-09-22', '081234560002'],
                'E000033' => ['Bambang Hartono',   'Laki-laki', '1975-11-02', '081234560003'],
                'E000034' => ['Ratna Kusuma',      'Perempuan', '1982-07-13', '081234560004'],
                'E000035' => ['Yusuf Ramadhan',    'Laki-laki', '1960-06-08', '081234560005'],
                'E000036' => ['Mega Puspitasari',  'Perempuan', '1990-12-24', '081234560006'],
            ];
            $d = $dummy[$no_rm] ?? $dummy['E' . $no_rm] ?? null;

            $pasien = (object) [
                'no_rm'         => $no_rm,
                'nama'          => $d[0] ?? '-',
                'jenis_kelamin' => $d[1] ?? '-',
                'tempat_lahir'  => 'Ngawi',
                'tgl_lahir'     => $d[2] ?? null,
                'no_hp'         => $d[3] ?? '-',
                'alamat'        => 'Jl. Contoh No. 1, Ngawi',
            ];
        }

        $id_pendaftaran = $id_pendaftaran ?? null;

        $tglLahirTampil = !empty($pasien->tgl_lahir)
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') : '-';
        $umurTampil = !empty($pasien->tgl_lahir)
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age : '-';

        // ===== HAK AKSES (sesuaikan nama kolom role di tabel users) =====
        $role = strtolower(auth()->user()->role ?? 'admin');
        $bisaTtv = in_array($role, ['admin', 'perawat']);
        $bisaSO  = in_array($role, ['admin', 'dokter', 'perawat']);
        $bisaAP  = in_array($role, ['admin', 'dokter']);
        $bisaIcd = in_array($role, ['admin', 'pmik']);
        $bisaRujuk = in_array($role, ['admin', 'dokter']);

        // Resume Medis (sama seperti Poli Obgyn)
        $bolehResume       = in_array($role, ['admin', 'dokter']);          // edit resume
        $bolehSimpanResume = in_array($role, ['admin', 'dokter', 'pmik']);
        $bolehSnomed       = in_array($role, ['admin', 'pmik']);            // kotak kode SNOMED CT

        $ro = fn ($boleh) => $boleh ? '' : 'readonly';

        // ===== DAFTAR ISIAN SOAP =====
        $soap = [
            'S' => [
                's_keluhan_utama'       => 'Keluhan utama',
                's_riwayat_sekarang'    => 'Riwayat penyakit sekarang',
                's_riwayat_dahulu'      => 'Riwayat penyakit dahulu',
                's_riwayat_keluarga'    => 'Riwayat penyakit keluarga',
                's_riwayat_pengobatan'  => 'Riwayat pengobatan',
                's_riwayat_sosial'      => 'Riwayat sosial',
                's_riwayat_operasi'     => 'Riwayat operasi',
                's_riwayat_alergi'      => 'Riwayat alergi',
                's_riwayat_imunisasi'   => 'Riwayat imunisasi',
            ],
            'O' => [
                'o_tanda_vital'  => 'Pemeriksaan tanda vital',
                'o_fisik_lain'   => 'Pemeriksaan fisik lainnya',
            ],
            'A' => [
                'a_dx_utama'     => 'Dx utama',
                'a_dx_sekunder'  => 'Dx Sekunder',
            ],
        ];
        $boleh = ['S' => $bisaSO, 'O' => $bisaSO, 'A' => $bisaAP];

        $nonFarmakologis = [
            'p_tindakan_medis'    => 'Tindakan medis',
            'p_konseling_edukasi' => 'Konseling edukasi',
            'p_rencana_lanjut'    => 'Rencana tindak lanjut',
            'p_diet'              => 'Diet',
        ];

        // ===== RESUME MEDIS (sama dengan Poli Obgyn) =====
        $snomed = fn ($kunci) => !$bolehSnomed ? '' : (
            '<div class="rm-snomed" data-kunci="' . $kunci . '">' .
                '<div class="rm-snomed-isi">' .
                    '<div class="rm-snomed-judul"><i class="fa-solid fa-tag"></i> Kode SNOMED CT</div>' .
                    '<div class="rm-snomed-daftar"><span class="rm-snomed-kosong">Belum ada kode SNOMED CT</span></div>' .
                '</div>' .
                '<button type="button" class="rm-snomed-btn" onclick="tambahKodeSnomed(this)">Tambah Kode +</button>' .
            '</div>'
        );

        // [id, label, placeholder, kunci snomed]
        $rmKiri = [
            ['rmKeluhan', 'Keluhan Saat Masuk', 'Tuliskan keluhan saat masuk...', 'keluhan'],
            ['rmKesadaran', 'Tingkat Kesadaran Pasien Masuk', '', 'kesadaran'],
            ['rmFisik', 'Pemeriksaan Fisik (Objektif)', '', 'fisik'],
            ['rmPenunjang', 'Pemeriksaan Penunjang (Radiologi/Lab)', '', 'penunjang'],
            ['rmDxUtama', 'Diagnosa Utama / Akhir', '', 'dx_utama'],
            ['rmDxSekunder', 'Diagnosa Sekunder', '', 'dx_sekunder'],
        ];

        $rmKanan = [
            ['rmTindakan', 'Tindakan Sesuai ICD-9-CM', '', 'tindakan'],
            ['rmTerapi', 'Terapi Obat yang Diberikan', '', 'terapi'],
            ['rmInstruksi', 'Instruksi atau Rencana Tindak Lanjut', '', 'instruksi'],
        ];

        $rmEdukasi = [
            'Proses Penyakit, Diagnosis, Rencana Asuhan',
            'Obat',
            'Rehabilitasi Medis',
            'Manajemen Nyeri',
            'Gizi',
            'Cuci Tangan',
            'Penggunaan Alat Medis',
        ];

        // [id, label, placeholder, pilihan]
        $rmSelect = [
            ['rmPrognosis', 'Prognosis', 'Pilih prognosis',
                ['Ad bonam', 'Dubia ad bonam', 'Dubia ad malam', 'Ad malam']],
            ['rmKondisiKeluar', 'Kondisi Saat Meninggalkan Klinik', 'Pilih kondisi',
                ['Sembuh', 'Membaik', 'Belum sembuh', 'Meninggal']],
            ['rmCaraKeluar', 'Cara Keluar', 'Pilih cara keluar',
                ['Atas persetujuan dokter', 'Dirujuk', 'Atas permintaan sendiri', 'Pulang paksa', 'Meninggal']],
        ];
    @endphp

    <div class="kb-wrap">

        @if (session('sukses'))
            <div class="kb-alert">{{ session('sukses') }}</div>
        @endif

        {{-- ===== DATA PASIEN (tetap di atas, tidak ikut scroll) ===== --}}
        <div class="kb-card">
            <div class="kb-card-title">
                <span>Data Pasien</span>
                <a href="{{ route('poli.jantung.riwayat', $pasien->no_rm) }}" class="kb-kembali">&larr; Riwayat Kunjungan</a>
            </div>

            <div class="kb-pasien-grid">
                <div>
                    <div class="kb-field"><label>No RM</label><input type="text" value="{{ $pasien->no_rm }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>Nama</label><input type="text" value="{{ $pasien->nama }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>Jenis Kelamin</label><input type="text" value="{{ $pasien->jenis_kelamin }}" readonly></div>
                </div>
                <div>
                    <div class="kb-field"><label>Tempat, Tgl Lahir</label><input type="text" value="{{ ($pasien->tempat_lahir ?? '-') . '/' . $tglLahirTampil }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>Umur</label><input type="text" value="{{ $umurTampil }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>No Hp</label><input type="text" value="{{ $pasien->no_hp }}" readonly></div>
                </div>
            </div>
        </div>

        {{-- ===== TAB ===== --}}
        <div class="kb-tabs" role="tablist">
            <button type="button" class="kb-tab aktif" data-tab="assesmen" role="tab"><i class="fa-solid fa-file-pen"></i> Assesment</button>
            <button type="button" class="kb-tab" data-tab="radiologi" role="tab"><i class="fa-solid fa-x-ray"></i> Radiologi</button>
            <button type="button" class="kb-tab" data-tab="resume" role="tab"><i class="fa-solid fa-file-lines"></i> Resume Medis</button>
        </div>

        <form method="POST" action="{{ route('poli.jantung.assesmen.store') }}" class="kb-form" id="formAssesmen">
            @csrf
            <input type="hidden" name="no_rm" value="{{ $pasien->no_rm }}">
            <input type="hidden" name="id_pendaftaran" value="{{ $id_pendaftaran }}">

            {{-- ===== TAB ASSESMEN (area scroll) ===== --}}
            <div class="kb-pane aktif" id="tab-assesmen">

                {{-- TTV --}}
                <div class="kb-ttv">
                    <div class="kb-ttv-item"><label for="ttv_td">TD</label><input type="text" id="ttv_td" name="ttv_td" {{ $ro($bisaTtv) }} value="{{ old('ttv_td') }}"><small>mmHg</small></div>
                    <div class="kb-ttv-item"><label for="ttv_hr">HR</label><input type="text" id="ttv_hr" name="ttv_hr" {{ $ro($bisaTtv) }} value="{{ old('ttv_hr') }}"><small>x/menit</small></div>
                    <div class="kb-ttv-item"><label for="ttv_spo2">SpO2</label><input type="text" id="ttv_spo2" name="ttv_spo2" {{ $ro($bisaTtv) }} value="{{ old('ttv_spo2') }}"><small>%</small></div>

                    <div class="kb-ttv-item"><label for="ttv_suhu">Suhu</label><input type="text" id="ttv_suhu" name="ttv_suhu" {{ $ro($bisaTtv) }} value="{{ old('ttv_suhu') }}"><small>C</small></div>
                    <div class="kb-ttv-item"><label for="ttv_rr">RR</label><input type="text" id="ttv_rr" name="ttv_rr" {{ $ro($bisaTtv) }} value="{{ old('ttv_rr') }}"><small>x/menit</small></div>
                    <div class="kb-ttv-item"><label for="ttv_lainnya">Lainnya</label><input type="text" id="ttv_lainnya" name="ttv_lainnya" {{ $ro($bisaTtv) }} value="{{ old('ttv_lainnya') }}"></div>

                    <div class="kb-ttv-cek"><input type="checkbox" name="ttv_selesai" value="1" title="Centang bila TTV selesai" {{ $bisaTtv ? '' : 'disabled' }}></div>
                </div>

                <h3 class="kb-judul-soap">SOAP</h3>

                {{-- S, O, A --}}
                @foreach ($soap as $huruf => $items)
                    <div class="kb-soap" data-soap="{{ $huruf }}">
                        <div class="kb-badge">{{ $huruf }}</div>
                        <div class="kb-soap-card">
                            @foreach ($items as $nama => $label)
                                <div class="kb-row">
                                    <label for="{{ $nama }}">{{ $loop->iteration }}. {{ $label }}</label>
                                    <textarea class="kb-input" rows="1" id="{{ $nama }}" name="{{ $nama }}" {{ $ro($boleh[$huruf]) }}>{{ old($nama) }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- P --}}
                <div class="kb-soap" data-soap="P">
                    <div class="kb-badge">P</div>
                    <div class="kb-soap-card">
                        <div class="kb-row">
                            <label for="p_resep_obat">1. Resep obat</label>
                            <div class="kb-input-wrap">
                                <textarea class="kb-input" rows="1" id="p_resep_obat" name="p_resep_obat" style="padding-right:90px" {{ $ro($bisaAP) }}>{{ old('p_resep_obat') }}</textarea>
                                <button type="button" class="kb-btn-cetak" onclick="cetakResep()"><i class="fa-solid fa-print"></i> Cetak</button>
                            </div>
                        </div>

                        <div class="kb-grup-label">2. Non Farmakologis</div>
                        @foreach ($nonFarmakologis as $nama => $label)
                            <div class="kb-row sub">
                                <label for="{{ $nama }}">{{ $label }}</label>
                                <textarea class="kb-input" rows="1" id="{{ $nama }}" name="{{ $nama }}" {{ $ro($bisaAP) }}>{{ old($nama) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kode ICD --}}
                <div class="kb-icd">
                    <h3 class="kb-judul-soap" style="margin-top:4px">Kode ICD</h3>
                    <div class="kb-icd-grid">
                        <div>
                            <div class="kb-icd-label">Penyakit</div>
                            <div class="kb-icd-baris">
                                <input type="text" class="kb-icd-input" id="icd_penyakit" name="icd_penyakit" placeholder="Cari Kode Penyakit ..." autocomplete="off" {{ $ro($bisaIcd) }} value="{{ old('icd_penyakit') }}">
                                <div class="kb-simbol">
                                    <button type="button" data-simbol="icd_dagger" title="Dagger" {{ $bisaIcd ? '' : 'disabled' }}>&dagger;</button>
                                    <button type="button" data-simbol="icd_asterisk" title="Asterisk" {{ $bisaIcd ? '' : 'disabled' }}>&lowast;</button>
                                </div>
                                <i class="fa-solid fa-check kb-centang" data-centang="icd_penyakit"></i>
                            </div>
                            <input type="hidden" name="icd_dagger" id="icd_dagger" value="0">
                            <input type="hidden" name="icd_asterisk" id="icd_asterisk" value="0">
                        </div>
                        <div>
                            <div class="kb-icd-label">Tindakan</div>
                            <div class="kb-icd-baris">
                                <input type="text" class="kb-icd-input" id="icd_tindakan" name="icd_tindakan" placeholder="Cari Kode Tindakan ..." autocomplete="off" {{ $ro($bisaIcd) }} value="{{ old('icd_tindakan') }}">
                                <i class="fa-solid fa-check kb-centang" data-centang="icd_tindakan"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kb-aksi">
                    <button type="submit" class="kb-btn simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    @if ($bisaRujuk)
                        <button type="submit" class="kb-btn" formaction="{{ route('poli.jantung.rujukan') }}"
                                onclick="return confirm('Kirim rujukan untuk pasien ini?')">Rujuk</button>
                    @endif
                </div>
            </div>

            {{-- ===== TAB RADIOLOGI ===== --}}
            <div class="kb-pane" id="tab-radiologi">
                <div class="kb-kosong">Belum ada hasil radiologi untuk kunjungan ini.</div>
            </div>
        </form>

        {{-- ===== TAB RESUME MEDIS (sama dengan Poli Obgyn, sementara tanpa database) =====
             Ditaruh di luar <form> asesmen supaya isiannya tidak ikut terkirim saat Simpan asesmen. --}}
        <div class="kb-pane" id="tab-resume">
            <div class="rm-wrap">

                {{-- ALAMAT, KUNJUNGAN, PENANGGUNG JAWAB --}}
                <div class="rm-top">
                    <div class="rm-top-col">
                        <div class="rm-line rm-start">
                            <label for="rmAlamat">Alamat</label>
                            <textarea id="rmAlamat" class="rm-input" rows="2">{{ $pasien->alamat ?? '' }}</textarea>
                        </div>

                        <div class="rm-line">
                            <span></span>
                            <div class="rm-split">
                                <input type="text" id="rmKelurahan" class="rm-input" placeholder="Kelurahan / Desa">
                                <input type="text" id="rmKecamatan" class="rm-input" placeholder="Kecamatan">
                            </div>
                        </div>

                        <div class="rm-line">
                            <span></span>
                            <div class="rm-split">
                                <input type="text" id="rmKota" class="rm-input" placeholder="Kota / Kabupaten">
                                <input type="text" id="rmProvinsi" class="rm-input" placeholder="Provinsi">
                            </div>
                        </div>

                        <div class="rm-line">
                            <label for="rmKodePos">Kode Pos</label>
                            <input type="text" id="rmKodePos" class="rm-input" inputmode="numeric" maxlength="5">
                        </div>

                        <div class="rm-line">
                            <label for="rmPembayaran">Pembayaran</label>
                            <input type="text" id="rmPembayaran" class="rm-input" placeholder="mis. BPJS / Umum">
                        </div>
                    </div>

                    <div class="rm-top-col">
                        <div class="rm-line">
                            <label for="rmTglKunjungan">Tgl. Kunjungan</label>
                            <input type="date" id="rmTglKunjungan" class="rm-input">
                        </div>

                        <div class="rm-line">
                            <label for="rmTglKeluar">Tgl. Keluar</label>
                            <input type="date" id="rmTglKeluar" class="rm-input">
                        </div>

                        <div class="rm-pj">
                            <div class="rm-pj-judul">Penanggung Jawab</div>

                            <div class="rm-pj-grid">
                                <label for="rmPjNama">Nama</label>
                                <input type="text" id="rmPjNama" class="rm-input">

                                <label for="rmPjHubungan">Hubungan</label>
                                <input type="text" id="rmPjHubungan" class="rm-input">

                                <label for="rmPjJk">Jenis Kelamin</label>
                                <select id="rmPjJk" class="rm-input">
                                    <option value=""></option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>

                                <label for="rmPjHp">No. HP</label>
                                <input type="text" id="rmPjHp" class="rm-input" inputmode="tel">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RINGKASAN KLINIS & PENATALAKSANAAN --}}
                <div class="rm-bar">Ringkasan Klinis &amp; Penatalaksanaan</div>

                <div class="rm-cols">
                    {{-- KOLOM KIRI --}}
                    <div class="rm-col">
                        @foreach ($rmKiri as [$id, $label, $placeholder, $kunci])
                            <div class="rm-blok rm-grow">
                                <label class="rm-lbl" for="{{ $id }}">{{ $label }}</label>
                                <textarea id="{{ $id }}" class="rm-input" rows="2" placeholder="{{ $placeholder }}"></textarea>
                                {!! $snomed($kunci) !!}
                            </div>
                        @endforeach
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="rm-col">
                        @foreach ($rmKanan as [$id, $label, $placeholder, $kunci])
                            <div class="rm-blok rm-grow">
                                <label class="rm-lbl" for="{{ $id }}">{{ $label }}</label>
                                <textarea id="{{ $id }}" class="rm-input" rows="2" placeholder="{{ $placeholder }}"></textarea>
                                {!! $snomed($kunci) !!}
                            </div>
                        @endforeach

                        <div class="rm-blok">
                            <div class="rm-lbl">Edukasi</div>

                            <div class="rm-cek">
                                @foreach ($rmEdukasi as $opt)
                                    <label>
                                        <input type="checkbox" name="rm_edukasi[]" value="{{ $opt }}">
                                        {{ $opt }}
                                    </label>
                                @endforeach
                            </div>

                            {!! $snomed('edukasi') !!}
                        </div>

                        @foreach ($rmSelect as [$id, $label, $placeholder, $opsi])
                            <div class="rm-blok">
                                <label class="rm-lbl" for="{{ $id }}">{{ $label }}</label>
                                <select id="{{ $id }}" class="rm-input">
                                    <option value="">{{ $placeholder }}</option>
                                    @foreach ($opsi as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- TANDA TANGAN DPJP, TANGGAL, JAM, TOMBOL --}}
                <div class="rm-footer">
                    <div>
                        <div class="rm-ttd-label">Tanda tangan DPJP</div>
                        <canvas id="ttdDpjp" class="rm-ttd-canvas" width="320" height="140"></canvas>
                        <a href="#" class="ob-ttd-hapus" onclick="hapusTtd('ttdDpjp'); return false;">Hapus</a>
                    </div>

                    <div class="rm-waktu">
                        <label for="rmTglTtd">Tanggal</label>
                        <input type="date" id="rmTglTtd" class="rm-input">
                    </div>

                    <div class="rm-waktu">
                        <label for="rmJamTtd">Jam</label>
                        <input type="time" id="rmJamTtd" class="rm-input">
                    </div>

                    <div class="rm-btns">
                        @if ($bolehSimpanResume)
                            <button type="button" class="kb-btn simpan" onclick="simpanResume()">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan
                            </button>
                        @endif

                        <button type="button" class="kb-btn simpan" onclick="cetakResumeMedis()">
                            <i class="fa-solid fa-print"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const NO_RM_PASIEN = @json($pasien->no_rm ?? null);
        const ID_PENDAFTARAN = @json($id_pendaftaran);
        const BOLEH_RESUME = @json($bolehResume);

        // ----- Pindah tab -----
        document.querySelectorAll('.kb-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.kb-tab').forEach(function (t) { t.classList.remove('aktif'); });
                document.querySelectorAll('.kb-pane').forEach(function (p) { p.classList.remove('aktif'); });
                tab.classList.add('aktif');
                document.getElementById('tab-' + tab.dataset.tab).classList.add('aktif');

                // Segarkan isi resume dari SOAP / kode ICD setiap kali tabnya dibuka
                if (tab.dataset.tab === 'resume') isiResumeOtomatis();
            });
        });

        // ----- Textarea otomatis membesar -----
        function sesuaikanTinggi(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight + 2, 200) + 'px';
            el.style.overflowY = el.scrollHeight > 200 ? 'auto' : 'hidden';
        }
        document.querySelectorAll('textarea.kb-input').forEach(function (el) {
            sesuaikanTinggi(el);
            el.addEventListener('input', function () { sesuaikanTinggi(el); });
        });

        // ----- Tanda centang hijau kode ICD -----
        function perbaruiCentang(input) {
            var c = document.querySelector('[data-centang="' + input.id + '"]');
            if (c) c.classList.toggle('tampil', input.value.trim() !== '');
        }
        ['icd_penyakit', 'icd_tindakan'].forEach(function (id) {
            var el = document.getElementById(id);
            perbaruiCentang(el);
            el.addEventListener('input', function () { perbaruiCentang(el); });
        });

        // ----- Tombol dagger / asterisk -----
        document.querySelectorAll('.kb-simbol button').forEach(function (b) {
            b.addEventListener('click', function () {
                var hidden = document.getElementById(b.dataset.simbol);
                var aktif = hidden.value === '1';
                hidden.value = aktif ? '0' : '1';
                b.classList.toggle('aktif', !aktif);
            });
        });

        // ----- Cetak resep obat -----
        function cetakResep() {
            var isi = document.getElementById('p_resep_obat').value || '-';
            var w = window.open('', '_blank', 'width=600,height=700');
            w.document.write('<html><head><title>Resep Obat</title></head><body style="font-family:sans-serif">'
                + '<h3>Resep Obat</h3><p><b>No. RM:</b> {{ $pasien->no_rm }}<br><b>Nama:</b> {{ $pasien->nama }}</p>'
                + '<pre style="white-space:pre-wrap;font-family:inherit"></pre></body></html>');
            w.document.querySelector('pre').textContent = isi;
            w.document.close();
            w.focus();
            w.print();
        }

        // ===========================================================
        // RESUME MEDIS (sama dengan Poli Obgyn, sementara tanpa database)
        // ===========================================================

        // Kolom yang diisi otomatis dari SOAP / kode ICD.
        // Selama belum diketik manual, isinya ikut diperbarui setiap tab
        // Resume Medis dibuka; setelah diketik, tidak ditimpa lagi.
        const FIELD_RESUME_OTOMATIS = [
            'rmKeluhan', 'rmFisik', 'rmTerapi',
            'rmDxUtama', 'rmDxSekunder', 'rmTindakan'
        ];

        FIELD_RESUME_OTOMATIS.forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', function () {
                    this.dataset.otomatis = '';
                });
            }
        });

        // Gabungkan isian satu blok SOAP (S / O / A / P) menjadi "Label: isi" per baris
        function gabungSoapHuruf(huruf) {
            return Array.from(document.querySelectorAll('[data-soap="' + huruf + '"] .kb-row'))
                .map(function (row) {
                    const label = (row.querySelector('label')?.textContent || '')
                        .replace(/^\s*\d+\.\s*/, '').trim();
                    const isi = (row.querySelector('textarea')?.value || '').trim();
                    return isi ? label + ': ' + isi : '';
                })
                .filter(Boolean)
                .join('\n');
        }

        function isiResumeOtomatis() {
            const nilai = id => (document.getElementById(id)?.value ?? '').trim();

            const isi = function (id, teks) {
                const el = document.getElementById(id);
                if (!el) return;

                if (el.value.trim() === '' || el.dataset.otomatis === '1') {
                    el.value = teks;
                    el.dataset.otomatis = teks ? '1' : '';
                }
            };

            isi('rmKeluhan', gabungSoapHuruf('S'));
            isi('rmFisik', gabungSoapHuruf('O'));
            isi('rmTerapi', gabungSoapHuruf('P'));
            isi('rmDxUtama', nilai('icd_penyakit') || nilai('a_dx_utama'));
            isi('rmDxSekunder', nilai('a_dx_sekunder'));
            isi('rmTindakan', nilai('icd_tindakan'));
        }

        // Disiapkan untuk nanti saat disambungkan ke database.
        function kumpulkanResume() {
            isiResumeOtomatis();

            const n = id => (document.getElementById(id)?.value ?? '').trim();

            const snomed = {};
            document.querySelectorAll('#tab-resume .rm-snomed').forEach(function (kotak) {
                snomed[kotak.dataset.kunci] = Array.from(
                    kotak.querySelectorAll('.rm-snomed-chip')
                ).map(chip => chip.dataset.kode);
            });

            const edukasi = Array.from(
                document.querySelectorAll('#tab-resume input[name="rm_edukasi[]"]:checked')
            ).map(c => c.value);

            return {
                id_pendaftaran: ID_PENDAFTARAN,
                no_rm: NO_RM_PASIEN,
                alamat: n('rmAlamat'),
                kelurahan: n('rmKelurahan'),
                kecamatan: n('rmKecamatan'),
                kota: n('rmKota'),
                provinsi: n('rmProvinsi'),
                kode_pos: n('rmKodePos'),
                pembayaran: n('rmPembayaran'),
                tanggal_kunjungan: n('rmTglKunjungan') || null,
                tanggal_keluar: n('rmTglKeluar') || null,
                pj_nama: n('rmPjNama'),
                pj_hubungan: n('rmPjHubungan'),
                pj_jenis_kelamin: n('rmPjJk'),
                pj_no_hp: n('rmPjHp'),
                keluhan: n('rmKeluhan'),
                kesadaran: n('rmKesadaran'),
                pemeriksaan_fisik: n('rmFisik'),
                penunjang: n('rmPenunjang'),
                diagnosa_utama: n('rmDxUtama'),
                diagnosa_sekunder: n('rmDxSekunder'),
                tindakan: n('rmTindakan'),
                terapi: n('rmTerapi'),
                instruksi: n('rmInstruksi'),
                edukasi: edukasi,
                prognosis: n('rmPrognosis'),
                kondisi_keluar: n('rmKondisiKeluar'),
                cara_keluar: n('rmCaraKeluar'),
                snomed: snomed,
                tanggal_ttd: n('rmTglTtd') || null,
                jam_ttd: n('rmJamTtd') || null,
                ttd_dpjp: ttdTerisi['ttdDpjp']
                    ? document.getElementById('ttdDpjp').toDataURL('image/png')
                    : null
            };
        }

        // Simpan resume: SEMENTARA hanya menampilkan data (belum ke database)
        function simpanResume() {
            const data = kumpulkanResume();
            console.log('Resume Medis (dummy, belum disimpan ke database):', data);
            alert('Resume Medis siap disimpan.\nSementara masih tampilan saja, belum tersambung ke database.');
        }

        // Tombol "Tambah Kode +" (sementara pakai kotak isian sederhana)
        function tambahKodeSnomed(tombol) {
            const kotak = tombol.closest('.rm-snomed');
            const daftar = kotak.querySelector('.rm-snomed-daftar');

            const kode = (prompt('Masukkan kode SNOMED CT:') || '').trim();
            if (!kode) return;

            const kosong = daftar.querySelector('.rm-snomed-kosong');
            if (kosong) kosong.remove();

            const chip = document.createElement('span');
            chip.className = 'rm-snomed-chip';
            chip.dataset.kode = kode;
            chip.appendChild(document.createTextNode(kode));

            const hapus = document.createElement('button');
            hapus.type = 'button';
            hapus.textContent = '×';
            hapus.setAttribute('aria-label', 'Hapus kode');

            hapus.addEventListener('click', function () {
                chip.remove();

                if (!daftar.querySelector('.rm-snomed-chip')) {
                    const teks = document.createElement('span');
                    teks.className = 'rm-snomed-kosong';
                    teks.textContent = 'Belum ada kode SNOMED CT';
                    daftar.appendChild(teks);
                }
            });

            chip.appendChild(hapus);
            daftar.appendChild(chip);
        }

        function cetakResumeMedis() {
            alert('Cetak Resume Medis belum dihubungkan. Tampilan dulu, cetak menyusul.');
        }

        // Tanggal kunjungan/keluar/tanda tangan dan jam terisi hari ini
        (function () {
            const d = new Date();
            const dua = n => String(n).padStart(2, '0');
            const hariIni = d.getFullYear() + '-' + dua(d.getMonth() + 1) + '-' + dua(d.getDate());

            ['rmTglKunjungan', 'rmTglKeluar', 'rmTglTtd'].forEach(function (id) {
                const el = document.getElementById(id);
                if (el && !el.value) el.value = hariIni;
            });

            const jam = document.getElementById('rmJamTtd');
            if (jam && !jam.value) jam.value = dua(d.getHours()) + ':' + dua(d.getMinutes());
        })();

        // ----- Tanda tangan DPJP (kanvas) -----
        const ttdTerisi = { ttdDpjp: false };

        (function () {
            const canvas = document.getElementById('ttdDpjp');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            let gambar = false;

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
                ttdTerisi.ttdDpjp = true;
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
        })();

        function hapusTtd(id) {
            const canvas = document.getElementById(id);
            if (!canvas) return;

            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            ttdTerisi[id] = false;
        }

        // Resume medis: hanya dokter / admin yang mengedit
        if (!BOLEH_RESUME) {
            document.querySelectorAll('#tab-resume input, #tab-resume textarea, #tab-resume select')
                .forEach(function (el) { el.disabled = true; });
            document.querySelectorAll('#tab-resume canvas').forEach(function (el) { el.style.pointerEvents = 'none'; });
            document.querySelectorAll('#tab-resume .ob-ttd-hapus').forEach(function (el) { el.style.display = 'none'; });
        }
    </script>

@endsection