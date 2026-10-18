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
        $noRmOtomatis = $noRmBaru ?? old('no_rm', '000001');
        $tglOtomatis = date('Y-m-d');
    @endphp

    <form method="POST"
          action="{{ route('pendaftaran.store') }}"
          id="formPendaftaran"
          autocomplete="off">
        @csrf

        <div class="title-underline">Input Data</div>

        <div class="box">
            <div class="top-grid">
                <div class="field">
                    <label for="tgl_registrasi_tampil">Tgl Registrasi</label>
                    <input type="text" id="tgl_registrasi_tampil"
                           value="{{ date('d-m-Y') }}" readonly>
                    <input type="hidden" name="tgl_registrasi"
                           value="{{ $tglOtomatis }}">
                </div>

                <div class="field">
                    <label for="poli">Poli</label>
                    <select name="poli" id="poli" required>
                        <option value="">-- Pilih Poli --</option>
                        <option value="saraf" {{ old('poli') == 'saraf' ? 'selected' : '' }}>Poli Saraf</option>
                        <option value="obgyn" {{ old('poli') == 'obgyn' ? 'selected' : '' }}>Poli Obgyn</option>
                        <option value="jantung" {{ old('poli') == 'jantung' ? 'selected' : '' }}>Poli Jantung</option>
                        <option value="jiwa" {{ old('poli') == 'jiwa' ? 'selected' : '' }}>Poli Jiwa</option>
                    </select>
                </div>

                <div class="field">
                    <label for="no_rm">No RM</label>
                    <input type="text" name="no_rm" id="no_rm"
                           value="{{ $noRmOtomatis }}"
                           maxlength="6" pattern="[0-9]{6}"
                           inputmode="numeric" readonly>
                </div>

                <div class="field">
                    <label for="dokter">Dokter</label>
                    <input type="text" name="dokter" id="dokter"
                           value="{{ old('dokter') }}"
                           readonly required>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-title">Identitas Pasien</div>

            <div class="cols">
                <div class="col-left">

                    <div class="row">
                        <label for="no_rekam_medis">No. Rekam Medis</label>
                        <input type="text" name="no_rekam_medis"
                               id="no_rekam_medis"
                               value="{{ $noRmOtomatis }}"
                               maxlength="6" pattern="[0-9]{6}"
                               inputmode="numeric" readonly>
                    </div>

                    <div class="row">
                        <label for="nama_pasien">Nama Lengkap</label>
                        <input type="text" name="nama_pasien"
                               id="nama_pasien"
                               value="{{ old('nama_pasien') }}"
                               placeholder="Masukkan nama"
                               maxlength="100" required>
                    </div>

                    <div class="row">
                        <label for="nik">NIK</label>
                        <input type="text" name="nik" id="nik"
                               value="{{ old('nik') }}"
                               placeholder="16 digit NIK"
                               inputmode="numeric" maxlength="16"
                               minlength="16" pattern="[0-9]{16}" required
                               title="NIK harus 16 angka"
                               oninput="batasiAngka(this,16,'nikError','NIK harus 16 digit angka.',true)">
                        <small class="err" id="nikError"></small>
                    </div>

                    <div class="row">
                        <label>Tempat/Tgl Lahir</label>
                        <div class="split-2">
                            <input type="text" name="tempat_lahir"
                                   value="{{ old('tempat_lahir') }}"
                                   placeholder="Tempat lahir"
                                   maxlength="50" required>
                            <input type="date" name="tgl_lahir" id="tgl_lahir"
                                   value="{{ old('tgl_lahir') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <label>Umur</label>
                        <div class="umur-group">
                            <input type="number" name="umur_tahun" id="umur_tahun"
                                   value="{{ old('umur_tahun') }}"
                                   placeholder="Tahun" min="0" readonly>
                            <input type="number" name="umur_bulan" id="umur_bulan"
                                   value="{{ old('umur_bulan') }}"
                                   placeholder="Bulan" min="0" max="11" readonly>
                            <input type="number" name="umur_hari" id="umur_hari"
                                   value="{{ old('umur_hari') }}"
                                   placeholder="Hari" min="0" max="30" readonly>
                        </div>
                    </div>

                    <div class="row">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="0" {{ old('jenis_kelamin') === '0' ? 'selected' : '' }}>Tidak diketahui</option>
                            <option value="1" {{ old('jenis_kelamin') === '1' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="2" {{ old('jenis_kelamin') === '2' ? 'selected' : '' }}>Perempuan</option>
                            <option value="3" {{ old('jenis_kelamin') === '3' ? 'selected' : '' }}>Tidak dapat ditentukan</option>
                            <option value="4" {{ old('jenis_kelamin') === '4' ? 'selected' : '' }}>Tidak mengisi</option>
                        </select>
                    </div>

                    <div class="row">
                        <label for="agama">Agama</label>
                        <select name="agama" id="agama" required>
                            <option value="">-- Pilih Agama --</option>
                            <option value="1" {{ old('agama') === '1' ? 'selected' : '' }}>Islam</option>
                            <option value="2" {{ old('agama') === '2' ? 'selected' : '' }}>Kristen (Protestan)</option>
                            <option value="3" {{ old('agama') === '3' ? 'selected' : '' }}>Katolik</option>
                            <option value="4" {{ old('agama') === '4' ? 'selected' : '' }}>Hindu</option>
                            <option value="5" {{ old('agama') === '5' ? 'selected' : '' }}>Buddha</option>
                            <option value="6" {{ old('agama') === '6' ? 'selected' : '' }}>Konghucu</option>
                            <option value="7" {{ old('agama') === '7' ? 'selected' : '' }}>Penghayat</option>
                            <option value="8" {{ old('agama') === '8' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        <input type="text" name="agama_lainnya" id="agama_lainnya"
                               value="{{ old('agama_lainnya') }}"
                               placeholder="Tuliskan agama"
                               maxlength="100"
                               @if (old('agama') !== '8') style="display:none;" @endif>
                    </div>

                    <div class="row">
                        <label for="pendidikan">Pendidikan</label>
                        <select name="pendidikan" id="pendidikan" required>
                            <option value="">-- Pilih Pendidikan --</option>
                            <option value="0" {{ old('pendidikan') === '0' ? 'selected' : '' }}>Tidak sekolah</option>
                            <option value="1" {{ old('pendidikan') === '1' ? 'selected' : '' }}>SD</option>
                            <option value="2" {{ old('pendidikan') === '2' ? 'selected' : '' }}>SLTP sederajat</option>
                            <option value="3" {{ old('pendidikan') === '3' ? 'selected' : '' }}>SLTA sederajat</option>
                            <option value="4" {{ old('pendidikan') === '4' ? 'selected' : '' }}>D1-D3 sederajat</option>
                            <option value="5" {{ old('pendidikan') === '5' ? 'selected' : '' }}>D4</option>
                            <option value="6" {{ old('pendidikan') === '6' ? 'selected' : '' }}>S1</option>
                            <option value="7" {{ old('pendidikan') === '7' ? 'selected' : '' }}>S2</option>
                            <option value="8" {{ old('pendidikan') === '8' ? 'selected' : '' }}>S3</option>
                        </select>
                    </div>

                    <div class="row pekerjaan-row">
                        <label for="pekerjaan">Pekerjaan</label>
                        <select name="pekerjaan" id="pekerjaan" required>
                            <option value="">-- Pilih Pekerjaan --</option>
                            <option value="0" {{ old('pekerjaan') === '0' ? 'selected' : '' }}>Tidak bekerja</option>
                            <option value="1" {{ old('pekerjaan') === '1' ? 'selected' : '' }}>PNS</option>
                            <option value="2" {{ old('pekerjaan') === '2' ? 'selected' : '' }}>TNI/POLRI</option>
                            <option value="3" {{ old('pekerjaan') === '3' ? 'selected' : '' }}>BUMN</option>
                            <option value="4" {{ old('pekerjaan') === '4' ? 'selected' : '' }}>Pegawai Swasta/Wirausaha</option>
                            <option value="5" {{ old('pekerjaan') === '5' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                        <input type="text" name="pekerjaan_lainnya"
                               id="pekerjaan_lainnya"
                               value="{{ old('pekerjaan_lainnya') }}"
                               placeholder="Tuliskan pekerjaan"
                               maxlength="100"
                               @if (old('pekerjaan') !== '5') style="display:none;" @endif>
                    </div>
                </div>

                <div class="col-right">

                    <div class="row">
                        <label for="no_hp">No HP</label>
                        <input type="text" name="no_hp" id="no_hp"
                               value="{{ old('no_hp', '+62') }}"
                               inputmode="numeric" maxlength="15" required
                               oninput="formatHp(this,'noHpError')">
                        <small class="err" id="noHpError"></small>
                    </div>

                    <div class="row">
                        <label>Suku</label>
                        <div class="pair-suku">
                            <input type="text" name="suku" id="suku"
                                   value="{{ old('suku') }}"
                                   placeholder="Suku" maxlength="30" required
                                   oninput="cekHurufSaja(this,'sukuError')">
                            <label for="bangsa">Bangsa</label>
                            <input type="text" name="bangsa" id="bangsa"
                                   value="{{ old('bangsa', 'Indonesia') }}"
                                   maxlength="30" required>
                        </div>
                        <small class="err" id="sukuError"></small>
                    </div>

                    <div class="row">
                        <label for="bahasa">Bahasa</label>
                        <select name="bahasa" id="bahasa" required>
                            <option value="">-- Pilih Bahasa --</option>
                            <option value="indonesia" {{ old('bahasa', 'indonesia') == 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                            <option value="jawa" {{ old('bahasa') == 'jawa' ? 'selected' : '' }}>Jawa</option>
                            <option value="lainnya" {{ old('bahasa') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div class="row top">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat"
                                  placeholder="Alamat lengkap"
                                  required>{{ old('alamat') }}</textarea>
                    </div>

                    <div class="row">
                        <label></label>
                        <div class="split-2">
                            <input type="text" name="kelurahan"
                                   value="{{ old('kelurahan') }}"
                                   placeholder="Kelurahan" maxlength="100" required>
                            <input type="text" name="kecamatan"
                                   value="{{ old('kecamatan') }}"
                                   placeholder="Kecamatan" required>
                        </div>
                    </div>

                    <div class="row">
                        <label></label>
                        <div class="split-2">
                            <input type="text" name="kabupaten" id="kabupaten"
                                   list="daftarKabupaten"
                                   value="{{ old('kabupaten', 'Ngawi') }}"
                                   placeholder="Kabupaten" required>
                            <input type="text" name="provinsi"
                                   value="{{ old('provinsi', 'Jawa Timur') }}"
                                   placeholder="Provinsi">
                        </div>
                        <datalist id="daftarKabupaten">
                            <option value="Bangkalan"><option value="Banyuwangi">
                            <option value="Blitar"><option value="Bojonegoro">
                            <option value="Bondowoso"><option value="Gresik">
                            <option value="Jember"><option value="Jombang">
                            <option value="Kediri"><option value="Lamongan">
                            <option value="Lumajang"><option value="Madiun">
                            <option value="Magetan"><option value="Malang">
                            <option value="Mojokerto"><option value="Nganjuk">
                            <option value="Ngawi"><option value="Pacitan">
                            <option value="Pamekasan"><option value="Pasuruan">
                            <option value="Ponorogo"><option value="Probolinggo">
                            <option value="Sampang"><option value="Sidoarjo">
                            <option value="Situbondo"><option value="Sumenep">
                            <option value="Trenggalek"><option value="Tuban">
                            <option value="Tulungagung"><option value="Kota Batu">
                            <option value="Kota Blitar"><option value="Kota Kediri">
                            <option value="Kota Madiun"><option value="Kota Malang">
                            <option value="Kota Mojokerto"><option value="Kota Pasuruan">
                            <option value="Kota Probolinggo"><option value="Kota Surabaya">
                        </datalist>
                    </div>

                    <div class="row">
                        <label for="kode_pos">Kode Pos</label>
                        <input type="text" name="kode_pos" id="kode_pos"
                               value="{{ old('kode_pos') }}"
                               placeholder="Kode pos"
                               inputmode="numeric" maxlength="5"
                               pattern="[0-9]{5}" required
                               oninput="batasiAngka(this,5,'kodePosError','Kode pos harus 5 digit angka.')">
                        <small class="err" id="kodePosError"></small>
                    </div>

                    <div class="row">
                        <label for="pembayaran">Pembayaran</label>
                        <select name="pembayaran" id="pembayaran" required>
                            <option value="">-- Pilih Pembayaran --</option>
                            <option value="bpjs" {{ old('pembayaran') == 'bpjs' ? 'selected' : '' }}>BPJS</option>
                            <option value="umum" {{ old('pembayaran') == 'umum' ? 'selected' : '' }}>Umum</option>
                            <option value="asuransi" {{ old('pembayaran') == 'asuransi' ? 'selected' : '' }}>Asuransi swasta</option>
                        </select>
                    </div>

                    <div class="row">
                        <label for="no_bpjs">No. BPJS</label>
                        <input type="text" name="no_bpjs" id="no_bpjs"
                               value="{{ old('no_bpjs') }}"
                               inputmode="numeric" maxlength="13"
                               oninput="batasiAngka(this,13,'noBpjsError','Nomor BPJS hanya boleh angka, maksimal 13 digit.')">
                        <small class="err" id="noBpjsError"></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="box">
            <div class="box-title">Penanggung Jawab</div>
            <div class="cols">

                <div class="col-left">
                    <div class="row">
                        <label for="pj_nama">Nama</label>
                        <input type="text" name="pj_nama" id="pj_nama"
                               value="{{ old('pj_nama') }}"
                               placeholder="Nama penanggung jawab"
                               maxlength="100" required>
                    </div>

                    <div class="row">
                        <label for="pj_jenis_kelamin">Jenis Kelamin</label>
                        <select name="pj_jenis_kelamin" id="pj_jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="0" {{ old('pj_jenis_kelamin') === '0' ? 'selected' : '' }}>Tidak diketahui</option>
                            <option value="1" {{ old('pj_jenis_kelamin') === '1' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="2" {{ old('pj_jenis_kelamin') === '2' ? 'selected' : '' }}>Perempuan</option>
                            <option value="3" {{ old('pj_jenis_kelamin') === '3' ? 'selected' : '' }}>Tidak dapat ditentukan</option>
                            <option value="4" {{ old('pj_jenis_kelamin') === '4' ? 'selected' : '' }}>Tidak mengisi</option>
                        </select>
                    </div>
                </div>

                <div class="col-right">
                    <div class="row">
                        <label for="pj_hubungan">Hubungan</label>
                        <select name="pj_hubungan" id="pj_hubungan" required>
                            <option value="">-- Pilih Hubungan --</option>
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
                        <label for="pj_no_hp">No HP</label>
                        <input type="text" name="pj_no_hp" id="pj_no_hp"
                               value="{{ old('pj_no_hp', '+62') }}"
                               inputmode="numeric" maxlength="15" required
                               oninput="formatHp(this,'pjNoHpError')">
                        <small class="err" id="pjNoHpError"></small>
                    </div>
                </div>
            </div>
        </div>

        {{-- TOMBOL DAN DATA GENERAL CONSENT --}}
        <div class="form-actions">
            <button type="button"
                    class="btn-consent"
                    id="btnConsent"
                    onclick="bukaConsentDariPendaftaran()">
                General Consent
            </button>

            <input type="hidden" name="general_consent" id="general_consent" value="0">
            <input type="hidden" name="gc_anggota_keluarga" id="gc-h-keluarga" value="">
            <input type="hidden" name="gc_ttd_pemberi" id="gc-h-ttd-pemberi" value="">
            <input type="hidden" name="gc_nama_pemberi" id="gc-h-nama-pemberi" value="">
            <input type="hidden" name="gc_ttd_pasien" id="gc-h-ttd-pasien" value="">
            <input type="hidden" name="gc_nama_penandatangan" id="gc-h-nama-pasien" value="">

            <div class="form-actions-right">
                <button type="submit" class="btn-simpan">Simpan</button>
                <button type="reset" class="btn-reset">↺ Reset</button>
            </div>
        </div>
    </form>
</div>

{{-- Modal General Consent --}}
@include('pendaftaran.partials.general-consent')

<script>
/* =========================================================
   UTILITAS INPUT PENDAFTARAN
   ========================================================= */

function batasiAngka(input, maks, errorId, pesan, wajibPenuh = false) {
    const error = document.getElementById(errorId);
    const adaNonAngka = /[^0-9]/.test(input.value);

    input.value = input.value.replace(/[^0-9]/g, '').slice(0, maks);

    const kurang = wajibPenuh &&
        input.value.length > 0 &&
        input.value.length < maks;

    if (adaNonAngka || kurang) {
        if (error) {
            error.textContent = pesan;
            error.style.display = 'block';
        }
        input.style.borderColor = 'red';
    } else {
        if (error) error.style.display = 'none';
        input.style.borderColor = '';
    }
}

function formatHp(input, errorId) {
    const error = document.getElementById(errorId);
    let sisa = input.value.startsWith('+62')
        ? input.value.slice(3)
        : input.value;

    const adaNonAngka = /[^0-9]/.test(sisa);
    let digit = sisa.replace(/[^0-9]/g, '').replace(/^0+/, '');

    if (digit.startsWith('62')) digit = digit.slice(2);

    input.value = '+62' + digit.slice(0, 10);

    if (adaNonAngka) {
        if (error) {
            error.textContent = 'No HP hanya boleh angka.';
            error.style.display = 'block';
        }
        input.style.borderColor = 'red';
    } else {
        if (error) error.style.display = 'none';
        input.style.borderColor = '';
    }
}

function cekHurufSaja(input, errorId) {
    const error = document.getElementById(errorId);

    if (/[^a-zA-Z\s]/.test(input.value)) {
        input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
        if (error) {
            error.textContent = 'Suku hanya boleh berisi huruf.';
            error.style.display = 'block';
        }
        input.style.borderColor = 'red';
    } else {
        if (error) error.style.display = 'none';
        input.style.borderColor = '';
    }
}

function aturInputLainnya(selectId, inputId, kode) {
    const select = document.getElementById(selectId);
    const input = document.getElementById(inputId);

    if (!select || !input) return;

    function perbarui() {
        const tampil = select.value === kode;
        input.style.display = tampil ? '' : 'none';
        input.required = tampil;

        if (!tampil) input.value = '';
    }

    select.addEventListener('change', perbarui);
    perbarui();
}

function hitungUmur() {
    const tanggal = document.getElementById('tgl_lahir');
    const val = tanggal.value;

    if (!val) {
        ['umur_tahun', 'umur_bulan', 'umur_hari'].forEach(id => {
            document.getElementById(id).value = '';
        });
        tanggal.setCustomValidity('');
        return;
    }

    const lahir = new Date(val + 'T00:00:00');
    const now = new Date();

    if (lahir > now) {
        tanggal.setCustomValidity('Tanggal lahir tidak boleh di masa depan.');
        return;
    }

    tanggal.setCustomValidity('');

    let th = now.getFullYear() - lahir.getFullYear();
    let bl = now.getMonth() - lahir.getMonth();
    let hr = now.getDate() - lahir.getDate();

    if (hr < 0) {
        bl--;
        hr += new Date(now.getFullYear(), now.getMonth(), 0).getDate();
    }

    if (bl < 0) {
        th--;
        bl += 12;
    }

    document.getElementById('umur_tahun').value = th;
    document.getElementById('umur_bulan').value = bl;
    document.getElementById('umur_hari').value = Math.min(hr, 30);
}

/* =========================================================
   BUKA MODAL GENERAL CONSENT DENGAN DATA PASIEN
   Fungsi bukaConsent() disediakan oleh partial.
   ========================================================= */

function bukaConsentDariPendaftaran() {
    const form = document.getElementById('formPendaftaran');

    if (!form) {
        alert('Form pendaftaran tidak ditemukan.');
        return;
    }

    const ambil = nama => {
        const field = form.elements.namedItem(nama);
        return field ? String(field.value || '').trim() : '';
    };

    const norm = ambil('no_rm');

    if (!/^[0-9]{6}$/.test(norm)) {
        alert('Nomor RM harus tersedia dan terdiri dari 6 digit angka.');
        return;
    }

    if (typeof window.bukaConsent !== 'function') {
        alert('Fungsi bukaConsent belum ditemukan. Periksa JavaScript di file partial general-consent.blade.php.');
        return;
    }

    const tanggalLahir = ambil('tgl_lahir');
    const tempatLahir = ambil('tempat_lahir');

    const ttl = [
        tempatLahir,
        tanggalLahir ? tanggalLahir.split('-').reverse().join('-') : ''
    ].filter(Boolean).join(', ');

    const jkValue = ambil('jenis_kelamin');

    const dataPasien = {
        norm: norm,
        nama: ambil('nama_pasien'),
        ttl: ttl,
        tempat_lahir: tempatLahir,
        tgl_lahir: tanggalLahir,
        umur: ambil('umur_tahun')
            ? ambil('umur_tahun') + ' Tahun'
            : '',
        jk: jkValue === '1'
            ? 'Laki-laki'
            : jkValue === '2'
                ? 'Perempuan'
                : '',
        hp: ambil('no_hp')
    };

    window.bukaConsent(dataPasien);
}

/* =========================================================
   INISIALISASI FORM
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    aturInputLainnya('agama', 'agama_lainnya', '8');
    aturInputLainnya('pekerjaan', 'pekerjaan_lainnya', '5');

    const poli = document.getElementById('poli');
    const dokter = document.getElementById('dokter');

    const dokterPerPoli = {
        saraf: 'dr. Ratna Wijayanti, Sp.S',
        obgyn: 'dr. Aditya Iqbal M, Sp.OG',
        jantung: 'dr. Bagus Fitriadi, Sp.JP',
        jiwa: 'dr. Devi Anggraeni P, Sp.KJ'
    };

    function sesuaikanDokter() {
        dokter.value = dokterPerPoli[poli.value] || '';
    }

    poli.addEventListener('change', sesuaikanDokter);
    sesuaikanDokter(); // isi ulang saat halaman dimuat (mis. setelah validasi gagal)

    document.querySelectorAll('.box select').forEach(function (sel) {
        const tandai = () => sel.classList.toggle('kosong', sel.value === '');
        sel.addEventListener('change', tandai);
        tandai();
    });

    document.getElementById('tgl_lahir').addEventListener('change', hitungUmur);
    hitungUmur();

    const form = document.getElementById('formPendaftaran');

    form.addEventListener('submit', function (e) {
        const nik = document.getElementById('nik');

        if (nik.value.length !== 16 || !/^[0-9]{16}$/.test(nik.value)) {
            e.preventDefault();
            batasiAngka(nik, 16, 'nikError', 'NIK harus 16 digit angka.', true);
            nik.focus();
            return;
        }

        if (!/^[0-9]{6}$/.test(form.elements.namedItem('no_rm').value)) {
            e.preventDefault();
            alert('No RM harus 6 digit angka.');
            return;
        }

        if (document.getElementById('general_consent').value !== '1') {
            e.preventDefault();
            alert('General Consent belum disetujui. Lengkapi General Consent terlebih dahulu.');

            // Gunakan fungsi pembuka yang mengirim data pasien ke modal.
            bukaConsentDariPendaftaran();
            return;
        }

        // Status browser lokal; tidak menggantikan penyimpanan status ke database.
        const slug = form.elements.namedItem('poli').value;
        const awalan = {
            obgyn: 'obgyn',
            saraf: 'syaraf',
            jantung: 'jantung',
            jiwa: 'jiwa'
        }[slug];

        const noRm = String(form.elements.namedItem('no_rm').value || '')
            .trim()
            .toUpperCase();

        if (awalan && noRm) {
            try {
                const kunci = awalan + '_status_periksa';
                const status = JSON.parse(localStorage.getItem(kunci)) || {};
                status[noRm] = 'periksa';
                localStorage.setItem(kunci, JSON.stringify(status));

                const kunciWaktu = awalan + '_waktu_selesai';
                const waktu = JSON.parse(localStorage.getItem(kunciWaktu)) || {};
                delete waktu[noRm];
                localStorage.setItem(kunciWaktu, JSON.stringify(waktu));
            } catch (err) {
                console.error('Gagal memperbarui status browser:', err);
            }
        }
    });

    form.addEventListener('reset', function () {
        setTimeout(function () {
            document.querySelectorAll('.box select').forEach(function (select) {
                select.dispatchEvent(new Event('change'));
            });

            const btn = document.getElementById('btnConsent');
            btn.textContent = 'General Consent';
            btn.classList.remove('sudah');

            document.getElementById('general_consent').value = '0';

            [
                'gc-h-keluarga',
                'gc-h-ttd-pemberi',
                'gc-h-nama-pemberi',
                'gc-h-ttd-pasien',
                'gc-h-nama-pasien'
            ].forEach(function (id) {
                const field = document.getElementById(id);
                if (field) field.value = '';
            });

            hitungUmur();

            // Reset data di modal jika fungsi dari partial tersedia.
            if (typeof window.gcReset === 'function') {
                window.gcReset();
            }
        }, 0);
    });
});
</script>
@endsection