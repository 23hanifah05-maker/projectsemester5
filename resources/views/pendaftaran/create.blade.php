@extends('layouts.app')

@section('title', 'Input Data Pendaftaran - Klinik Utama Merah Putih')
@section('header-icon', '📋')
@section('header-title', 'Pendaftaran')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-input.css') }}">
    <link rel="stylesheet" href="{{ asset('css/general-consent.css') }}">
@endsection

@section('content')

    <div class="panel">

        @if ($errors->any())
            <div class="form-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            // $noRmBaru dikirim dari controller (nomor urut berikutnya). Fallback jika belum ada.
            $noRmOtomatis = $noRmBaru ?? old('no_rm', 'E000001');
            $tglOtomatis  = date('Y-m-d');
        @endphp

        <form method="POST" action="{{ route('pendaftaran.store') }}" id="formPendaftaran" autocomplete="off">
            @csrf

            {{-- ================= INPUT DATA ================= --}}
            <div class="title-underline">Input Data</div>
            <div class="box">
                <div class="top-grid">
                    <div class="field">
                        <label>Tgl Registrasi</label>
                        {{-- tampil dd-mm-yyyy, yang dikirim ke server yyyy-mm-dd --}}
                        <input type="text" value="{{ date('d-m-Y') }}" readonly>
                        <input type="hidden" name="tgl_registrasi" value="{{ $tglOtomatis }}">
                    </div>
                    <div class="field">
                        <label>Poli</label>
                        <select name="poli">
                            <option value="">JANTUNG</option>
                            <option value="saraf" {{ old('poli') == 'saraf' ? 'selected' : '' }}>Poli Saraf</option>
                            <option value="obgyn" {{ old('poli') == 'obgyn' ? 'selected' : '' }}>Poli Obgyn</option>
                            <option value="jantung" {{ old('poli') == 'jantung' ? 'selected' : '' }}>Poli Jantung</option>
                            <option value="jiwa" {{ old('poli') == 'jiwa' ? 'selected' : '' }}>Poli Jiwa</option>
                        </select>
                    </div>

                    <div class="field">
                        <label>No RM</label>
                        <input type="text" name="no_rm" id="no_rm" value="{{ $noRmOtomatis }}" readonly>
                    </div>
                    <div class="field">
                        <label>Dokter</label>
                        <select name="dokter">
                            <option value="">dr. INDAH</option>
                            <option value="dr_indah" {{ old('dokter') == 'dr_indah' ? 'selected' : '' }}>dr. Indah</option>
                            <option value="dr_bagus" {{ old('dokter') == 'dr_bagus' ? 'selected' : '' }}>dr. Bagus</option>
                            <option value="dr_wulan" {{ old('dokter') == 'dr_wulan' ? 'selected' : '' }}>dr. Wulan</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ================= IDENTITAS PASIEN ================= --}}
            <div class="box">
                <div class="box-title">Identitas Pasien</div>
                <div class="cols">

                    {{-- ---------- KOLOM KIRI ---------- --}}
                    <div class="col-left">
                        <div class="row">
                            <label>No. Rekam Medis</label>
                            <input type="text" name="no_rekam_medis" id="no_rekam_medis" value="{{ $noRmOtomatis }}" readonly>
                        </div>
                        <div class="row">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="NAILA SAFRINA">
                        </div>
                        <div class="row">
                            <label>NIK</label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                                   placeholder="3521124508900001" inputmode="numeric" maxlength="16" minlength="16" pattern="[0-9]{16}"
                                   title="NIK harus 16 angka"
                                   oninput="batasiAngka(this, 16, 'nikError', 'NIK harus 16 digit angka.', true)">
                            <small class="err" id="nikError"></small>
                        </div>
                        <div class="row">
                            <label>Tempat/Tgl Lahir</label>
                            <div class="split-2">
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="NGAWI">
                                <input type="date" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir') }}">
                            </div>
                        </div>
                        <div class="row">
                            <label>Umur</label>
                            <div class="umur-group">
                                <input type="number" name="umur_tahun" id="umur_tahun" value="{{ old('umur_tahun') }}" placeholder="12 Th" min="0" readonly>
                                <input type="number" name="umur_bulan" id="umur_bulan" value="{{ old('umur_bulan') }}" placeholder="11 Bl" min="0" max="11" readonly>
                                <input type="number" name="umur_hari" id="umur_hari" value="{{ old('umur_hari') }}" placeholder="30 Hr" min="0" max="30" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin">
                                <option value="">PEREMPUAN</option>
                                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="row">
                            <label>Agama</label>
                            <select name="agama">
                                <option value="">ISLAM</option>
                                <option value="islam" {{ old('agama') == 'islam' ? 'selected' : '' }}>Islam</option>
                                <option value="kristen" {{ old('agama') == 'kristen' ? 'selected' : '' }}>Kristen</option>
                                <option value="katolik" {{ old('agama') == 'katolik' ? 'selected' : '' }}>Katolik</option>
                                <option value="hindu" {{ old('agama') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="buddha" {{ old('agama') == 'buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="konghucu" {{ old('agama') == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                            </select>
                        </div>
                        <div class="row">
                            <label>Pendidikan</label>
                            <select name="pendidikan">
                                <option value="">SMA</option>
                                <option value="sd" {{ old('pendidikan') == 'sd' ? 'selected' : '' }}>SD</option>
                                <option value="smp" {{ old('pendidikan') == 'smp' ? 'selected' : '' }}>SMP</option>
                                <option value="sma" {{ old('pendidikan') == 'sma' ? 'selected' : '' }}>SMA</option>
                                <option value="d3" {{ old('pendidikan') == 'd3' ? 'selected' : '' }}>D3</option>
                                <option value="s1" {{ old('pendidikan') == 's1' ? 'selected' : '' }}>S1</option>
                                <option value="s2" {{ old('pendidikan') == 's2' ? 'selected' : '' }}>S2</option>
                            </select>
                        </div>
                        <div class="row">
                            <label>Pekerjaan</label>
                            <select name="pekerjaan">
                                <option value="">BELUM BEKERJA</option>
                                <option value="belum_bekerja" {{ old('pekerjaan') == 'belum_bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
                                <option value="pelajar" {{ old('pekerjaan') == 'pelajar' ? 'selected' : '' }}>Pelajar/Mahasiswa</option>
                                <option value="pns" {{ old('pekerjaan') == 'pns' ? 'selected' : '' }}>PNS</option>
                                <option value="wiraswasta" {{ old('pekerjaan') == 'wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                                <option value="lainnya" {{ old('pekerjaan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>

                    {{-- ---------- KOLOM KANAN ---------- --}}
                    <div class="col-right">
                        <div class="row">
                            <label>No Hp</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                                   placeholder="082534636626" inputmode="numeric" maxlength="13"
                                   oninput="batasiAngka(this, 13, 'noHpError', 'No HP hanya boleh angka, maksimal 13 digit.')">
                            <small class="err" id="noHpError"></small>
                        </div>
                        <div class="row">
                            <label>Suku</label>
                            <div class="pair-suku">
                                <input type="text" name="suku" id="suku" value="{{ old('suku') }}" placeholder="JAWA"
                                       oninput="cekHurufSaja(this, 'sukuError')">
                                <label>Bangsa</label>
                                <input type="text" name="bangsa" value="{{ old('bangsa', 'Indonesia') }}">
                            </div>
                            <small class="err" id="sukuError">Suku hanya boleh berisi huruf.</small>
                        </div>
                        <div class="row">
                            <label>Bahasa</label>
                            <select name="bahasa">
                                <option value="indonesia" {{ old('bahasa') == 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                                <option value="jawa" {{ old('bahasa') == 'jawa' ? 'selected' : '' }}>Jawa</option>
                                <option value="lainnya" {{ old('bahasa') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="row top">
                            <label>Alamat</label>
                            <textarea name="alamat" placeholder="JL. DIPONEGORO RT 5 RW 2 NO. 2">{{ old('alamat') }}</textarea>
                        </div>
                        <div class="row">
                            <label></label>
                            <div class="split-2">
                                <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" placeholder="Kelurahan">
                                <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" placeholder="Kecamatan">
                            </div>
                        </div>
                        <div class="row">
                            <label></label>
                            <div class="split-2">
                                <input type="text" name="kabupaten" value="{{ old('kabupaten') }}" placeholder="Kabupaten">
                                <input type="text" name="provinsi" value="{{ old('provinsi') }}" placeholder="Provinsi">
                            </div>
                        </div>
                        <div class="row">
                            <label>Kode Pos</label>
                            <input type="text" name="kode_pos" id="kode_pos" value="{{ old('kode_pos') }}"
                                   placeholder="63211" inputmode="numeric" maxlength="5"
                                   oninput="batasiAngka(this, 5, 'kodePosError', 'Kode Pos hanya boleh angka, maksimal 5 digit.')">
                            <small class="err" id="kodePosError"></small>
                        </div>
                        <div class="row">
                            <label>Pembayaran</label>
                            <select name="pembayaran">
                                <option value="">BPJS</option>
                                <option value="bpjs" {{ old('pembayaran') == 'bpjs' ? 'selected' : '' }}>BPJS</option>
                                <option value="umum" {{ old('pembayaran') == 'umum' ? 'selected' : '' }}>Umum</option>
                                <option value="asuransi" {{ old('pembayaran') == 'asuransi' ? 'selected' : '' }}>Asuransi</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= PENANGGUNG JAWAB ================= --}}
            <div class="box">
                <div class="box-title">Penanggung Jawab</div>
                <div class="cols">
                    <div class="col-left">
                        <div class="row">
                            <label>Nama</label>
                            <input type="text" name="pj_nama" value="{{ old('pj_nama') }}" placeholder="ANDI PRATAMA">
                        </div>
                        <div class="row">
                            <label>Jenis Kelamin</label>
                            <select name="pj_jenis_kelamin">
                                <option value="">LAKI-LAKI</option>
                                <option value="L" {{ old('pj_jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('pj_jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-right">
                        <div class="row">
                            <label>Hubungan</label>
                            <select name="pj_hubungan">
                                <option value="">AYAH</option>
                                <option value="ayah" {{ old('pj_hubungan') == 'ayah' ? 'selected' : '' }}>Ayah</option>
                                <option value="ibu" {{ old('pj_hubungan') == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                <option value="suami" {{ old('pj_hubungan') == 'suami' ? 'selected' : '' }}>Suami</option>
                                <option value="istri" {{ old('pj_hubungan') == 'istri' ? 'selected' : '' }}>Istri</option>
                                <option value="anak" {{ old('pj_hubungan') == 'anak' ? 'selected' : '' }}>Anak</option>
                                <option value="saudara" {{ old('pj_hubungan') == 'saudara' ? 'selected' : '' }}>Saudara</option>
                                <option value="lainnya" {{ old('pj_hubungan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div class="row">
                            <label>No Hp</label>
                            <input type="text" name="pj_no_hp" id="pj_no_hp" value="{{ old('pj_no_hp') }}"
                                   placeholder="085234512890" inputmode="numeric" maxlength="13"
                                   oninput="batasiAngka(this, 13, 'pjNoHpError', 'No HP hanya boleh angka, maksimal 13 digit.')">
                            <small class="err" id="pjNoHpError"></small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= ACTIONS ================= --}}
            <div class="form-actions">
                <button type="button" class="btn-consent" id="btnConsent" onclick="bukaConsent()">General Consent</button>
                <input type="hidden" name="general_consent" id="general_consent" value="0">
                <div class="form-actions-right">
                    <button type="submit" class="btn-simpan">💾 Simpan</button>
                    <button type="reset" class="btn-reset">↺ Reset</button>
                </div>
            </div>

        </form>

    </div>

    {{-- ================= VALIDASI INPUT (JS) ================= --}}
    <script>
    // Hanya angka, dengan batas panjang. Huruf/simbol langsung dihapus (termasuk saat paste).
    // wajibPenuh = true → tampilkan pesan bila angka belum mencapai batas (dipakai NIK).
    function batasiAngka(input, maks, errorId, pesan, wajibPenuh) {
        const error = document.getElementById(errorId);
        const adaNonAngka = /[^0-9]/.test(input.value);

        input.value = input.value.replace(/[^0-9]/g, '').slice(0, maks);

        const kurang = wajibPenuh && input.value.length > 0 && input.value.length < maks;

        if (adaNonAngka || kurang) {
            if (error) { error.textContent = pesan; error.style.display = 'block'; }
            input.style.borderColor = 'red';
        } else {
            if (error) error.style.display = 'none';
            input.style.borderColor = '';
        }
    }

    // Hanya huruf & spasi (Suku)
    function cekHurufSaja(input, errorId) {
        const error = document.getElementById(errorId);
        if (/[^a-zA-Z\s]/.test(input.value)) {
            if (error) error.style.display = 'block';
            input.style.borderColor = 'red';
            input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
        } else {
            if (error) error.style.display = 'none';
            input.style.borderColor = '';
        }
    }

    // Select yang masih kosong tampil abu-abu (contoh), hitam bila sudah dipilih
    document.querySelectorAll('.box select').forEach(function (sel) {
        const tandai = () => sel.classList.toggle('kosong', sel.value === '');
        sel.addEventListener('change', tandai);
        tandai();
    });
    document.getElementById('formPendaftaran').addEventListener('reset', function () {
        setTimeout(() => document.querySelectorAll('.box select').forEach(s => s.dispatchEvent(new Event('change'))), 0);
    });

    // Umur otomatis dari tanggal lahir
    function hitungUmur() {
        const val = document.getElementById('tgl_lahir').value;
        if (!val) return;
        const lahir = new Date(val), now = new Date();
        let th = now.getFullYear() - lahir.getFullYear();
        let bl = now.getMonth() - lahir.getMonth();
        let hr = now.getDate() - lahir.getDate();
        if (hr < 0) {
            bl--;
            hr += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
        }
        if (bl < 0) { th--; bl += 12; }
        if (th < 0) { th = bl = hr = 0; }
        document.getElementById('umur_tahun').value = th;
        document.getElementById('umur_bulan').value = bl;
        document.getElementById('umur_hari').value = Math.min(hr, 30);
    }
    document.getElementById('tgl_lahir').addEventListener('change', hitungUmur);
    hitungUmur();

    // Cegah simpan jika NIK belum 16 digit
    document.getElementById('formPendaftaran').addEventListener('submit', function (e) {
        const nik = document.getElementById('nik');
        if (nik.value.length > 0 && nik.value.length !== 16) {
            e.preventDefault();
            batasiAngka(nik, 16, 'nikError', 'NIK harus 16 digit angka.', true);
            nik.focus();
        }
    });

    // Wajib General Consent sebelum simpan
    document.getElementById('formPendaftaran').addEventListener('submit', function (e) {
        if (e.defaultPrevented) return;
        if (document.getElementById('general_consent').value !== '1') {
            e.preventDefault();
            alert('General Consent belum ditandatangani.');
            bukaConsent();
        }
    });

    // Saat Simpan: status pasien otomatis "Periksa" (kuning) di halaman Daftar Pasien tiap poli.
    document.getElementById('formPendaftaran').addEventListener('submit', function (e) {
        if (e.defaultPrevented) return;
        const poli = this.elements['poli'].value;
        const awalan = { obgyn: 'obgyn', saraf: 'syaraf', jantung: 'jantung', jiwa: 'jiwa' }[poli];
        if (!awalan) return;

        const noRm = (this.elements['no_rm'].value || this.elements['no_rekam_medis'].value || '')
            .trim().toUpperCase();
        if (noRm === '') return;

        try {
            const kunci = awalan + '_status_periksa';
            const status = JSON.parse(localStorage.getItem(kunci)) || {};
            status[noRm] = 'periksa';
            localStorage.setItem(kunci, JSON.stringify(status));

            // Daftar ulang = mulai periksa lagi, jadi waktu selesai sebelumnya dihapus
            const kunciWaktu = awalan + '_waktu_selesai';
            const waktu = JSON.parse(localStorage.getItem(kunciWaktu)) || {};
            delete waktu[noRm];
            localStorage.setItem(kunciWaktu, JSON.stringify(waktu));
        } catch (err) {
            console.error(err);
        }
    });

    // ================= GENERAL CONSENT =================
    let gcMenggambar = false, gcAdaTtd = false;

    function gcInitCanvas() {
        const c = document.getElementById('gcTtd');
        const ctx = c.getContext('2d');
        ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.strokeStyle = '#000';
        const pos = e => {
            const r = c.getBoundingClientRect();
            return { x: (e.clientX - r.left) * c.width / r.width, y: (e.clientY - r.top) * c.height / r.height };
        };
        c.onpointerdown = e => { gcMenggambar = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); c.setPointerCapture(e.pointerId); };
        c.onpointermove = e => { if (!gcMenggambar) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); gcAdaTtd = true; };
        c.onpointerup = () => { gcMenggambar = false; };
    }

    function gcHapusTtd() {
        const c = document.getElementById('gcTtd');
        c.getContext('2d').clearRect(0, 0, c.width, c.height);
        gcAdaTtd = false;
    }

    function bukaConsent() {
        const f = document.getElementById('formPendaftaran').elements;
        const jk = f['jenis_kelamin'].value;
        const tgl = f['tgl_lahir'].value ? f['tgl_lahir'].value.split('-').reverse().join('-') : '';
        const sekarang = new Date();

        document.getElementById('gc-norm').value = f['no_rm'].value || '-';
        document.getElementById('gc-nama').value = f['nama_lengkap'].value || '-';
        document.getElementById('gc-jk').value = jk === 'L' ? 'Laki-laki' : (jk === 'P' ? 'Perempuan' : '-');
        document.getElementById('gc-ttl').value = [f['tempat_lahir'].value, tgl].filter(Boolean).join(', ') || '-';
        document.getElementById('gc-umur').value = f['umur_tahun'].value ? f['umur_tahun'].value + ' Tahun' : '-';
        document.getElementById('gc-hp').value = f['no_hp'].value || '-';
        document.getElementById('gc-tgl').value = sekarang.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
        document.getElementById('gc-jam').value = sekarang.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        document.getElementById('consentModal').classList.add('show');
        gcInitCanvas();
    }

    function tutupConsent() {
        document.getElementById('consentModal').classList.remove('show');
    }

    function simpanConsent() {
        const semuaSetuju = Array.from(document.querySelectorAll('.gc-cek')).every(c => c.checked);

        if (!semuaSetuju) { alert('Semua butir harus dicentang "Setuju".'); return; }
        if (!document.getElementById('gc-semua').checked) { alert('Centang pernyataan persetujuan di bagian bawah.'); return; }
        if (!gcAdaTtd) { alert('Tanda tangan pasien/keluarga belum diisi.'); return; }

        document.getElementById('general_consent').value = '1';
        const btn = document.getElementById('btnConsent');
        btn.textContent = '✔ General Consent';
        btn.classList.add('sudah');
        tutupConsent();
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupConsent(); });

    document.getElementById('formPendaftaran').addEventListener('reset', function () {
        const btn = document.getElementById('btnConsent');
        btn.textContent = 'General Consent';
        btn.classList.remove('sudah');
        document.getElementById('general_consent').value = '0';
        gcHapusTtd();
        document.querySelectorAll('.gc-cek, #gc-semua').forEach(c => c.checked = false);
    });
    </script>

    {{-- ================= MODAL GENERAL CONSENT ================= --}}
    @include('pendaftaran.partials.general-consent')

@endsection