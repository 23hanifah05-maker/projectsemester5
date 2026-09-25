@extends('layouts.app')

@section('title', 'Input Data Pendaftaran - Klinik Utama Merah Putih')
@section('header-icon', '📋')
@section('header-title', 'Pendaftaran')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-input.css') }}">
@endsection

@section('content')

    <div class="panel">

        <div class="panel-head">
            <a href="{{ route('pendaftaran.index') }}" class="master-data-title">&larr; Master Data</a>
        </div>

        @if ($errors->any())
            <div class="form-alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pendaftaran.store') }}">
            @csrf

            {{-- ================= INPUT DATA ================= --}}
            <div class="section-title">Input Data</div>
            <div class="form-grid">
                <div class="field">
                    <label>Tgl Registrasi</label>
                    <input type="date" name="tgl_registrasi" value="{{ old('tgl_registrasi', date('Y-m-d')) }}">
                </div>
                <div class="field">
                    <label>Poli</label>
                    <select name="poli">
                        <option value="">-- Pilih Poli --</option>
                        <option value="saraf" {{ old('poli') == 'saraf' ? 'selected' : '' }}>Poli Saraf</option>
                        <option value="obgyn" {{ old('poli') == 'obgyn' ? 'selected' : '' }}>Poli Obgyn</option>
                        <option value="jantung" {{ old('poli') == 'jantung' ? 'selected' : '' }}>Poli Jantung</option>
                        <option value="jiwa" {{ old('poli') == 'jiwa' ? 'selected' : '' }}>Poli Jiwa</option>
                    </select>
                </div>

                <div class="field">
                    <label>No RM</label>
                    <input type="text" name="no_rm" value="{{ old('no_rm') }}" placeholder="Contoh: 123456">
                </div>
                <div class="field">
                    <label>Dokter</label>
                    <select name="dokter">
                        <option value="">-- Pilih Dokter --</option>
                        <option value="dr_indah" {{ old('dokter') == 'dr_indah' ? 'selected' : '' }}>dr. Indah</option>
                        <option value="dr_bagus" {{ old('dokter') == 'dr_bagus' ? 'selected' : '' }}>dr. Bagus</option>
                        <option value="dr_wulan" {{ old('dokter') == 'dr_wulan' ? 'selected' : '' }}>dr. Wulan</option>
                    </select>
                </div>
            </div>

            {{-- ================= IDENTITAS PASIEN ================= --}}
            <div class="section-title">Identitas Pasien</div>
            <div class="form-grid">
                <div class="field">
                    <label>No. Rekam Medis</label>
                    <input type="text" name="no_rekam_medis" value="{{ old('no_rekam_medis') }}">
                </div>
                <div class="field">
                    <label>No Hp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}">
                </div>

                <div class="field">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}">
                </div>
                <div class="field">
                    <label>Suku</label>
                    <input type="text" name="suku" value="{{ old('suku') }}">
                </div>

                <div class="field">
                    <label>NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16">
                </div>
                <div class="field">
                    <label>Bangsa</label>
                    <input type="text" name="bangsa" value="{{ old('bangsa', 'Indonesia') }}">
                </div>

                <div class="field">
                    <label>Tempat/Tgl Lahir</label>
                    <div class="split-2">
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Tempat lahir">
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}">
                    </div>
                </div>
                <div class="field">
                    <label>Bahasa</label>
                    <select name="bahasa">
                        <option value="indonesia" {{ old('bahasa') == 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                        <option value="jawa" {{ old('bahasa') == 'jawa' ? 'selected' : '' }}>Jawa</option>
                        <option value="lainnya" {{ old('bahasa') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="field">
                    <label>Umur</label>
                    <div class="umur-group">
                        <input type="number" name="umur_tahun" value="{{ old('umur_tahun') }}" placeholder="Th" min="0">
                        <input type="number" name="umur_bulan" value="{{ old('umur_bulan') }}" placeholder="Bl" min="0" max="11">
                        <input type="number" name="umur_hari" value="{{ old('umur_hari') }}" placeholder="Hr" min="0" max="30">
                    </div>
                </div>
                <div class="field">
                    <label>Alamat</label>
                    <textarea name="alamat">{{ old('alamat') }}</textarea>
                </div>

                <div class="field">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="field">
                    <div class="split-2">
                        <div class="field">
                            <label>Kelurahan</label>
                            <input type="text" name="kelurahan" value="{{ old('kelurahan') }}">
                        </div>
                        <div class="field">
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Agama</label>
                    <select name="agama">
                        <option value="">-- Pilih --</option>
                        <option value="islam" {{ old('agama') == 'islam' ? 'selected' : '' }}>Islam</option>
                        <option value="kristen" {{ old('agama') == 'kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="katolik" {{ old('agama') == 'katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="hindu" {{ old('agama') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="buddha" {{ old('agama') == 'buddha' ? 'selected' : '' }}>Buddha</option>
                        <option value="konghucu" {{ old('agama') == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>
                <div class="field">
                    <div class="split-2">
                        <div class="field">
                            <label>Kabupaten</label>
                            <input type="text" name="kabupaten" value="{{ old('kabupaten') }}">
                        </div>
                        <div class="field">
                            <label>Provinsi</label>
                            <input type="text" name="provinsi" value="{{ old('provinsi') }}">
                        </div>
                    </div>
                </div>

                <div class="field">
                    <label>Pendidikan</label>
                    <select name="pendidikan">
                        <option value="">-- Pilih --</option>
                        <option value="sd" {{ old('pendidikan') == 'sd' ? 'selected' : '' }}>SD</option>
                        <option value="smp" {{ old('pendidikan') == 'smp' ? 'selected' : '' }}>SMP</option>
                        <option value="sma" {{ old('pendidikan') == 'sma' ? 'selected' : '' }}>SMA</option>
                        <option value="d3" {{ old('pendidikan') == 'd3' ? 'selected' : '' }}>D3</option>
                        <option value="s1" {{ old('pendidikan') == 's1' ? 'selected' : '' }}>S1</option>
                        <option value="s2" {{ old('pendidikan') == 's2' ? 'selected' : '' }}>S2</option>
                    </select>
                </div>
                <div class="field">
                    <label>Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ old('kode_pos') }}">
                </div>

                <div class="field">
                    <label>Pekerjaan</label>
                    <select name="pekerjaan">
                        <option value="">-- Pilih --</option>
                        <option value="belum_bekerja" {{ old('pekerjaan') == 'belum_bekerja' ? 'selected' : '' }}>Belum Bekerja</option>
                        <option value="pelajar" {{ old('pekerjaan') == 'pelajar' ? 'selected' : '' }}>Pelajar/Mahasiswa</option>
                        <option value="pns" {{ old('pekerjaan') == 'pns' ? 'selected' : '' }}>PNS</option>
                        <option value="wiraswasta" {{ old('pekerjaan') == 'wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                        <option value="lainnya" {{ old('pekerjaan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
                <div class="field">
                    <label>Pembayaran</label>
                    <select name="pembayaran">
                        <option value="">-- Pilih --</option>
                        <option value="bpjs" {{ old('pembayaran') == 'bpjs' ? 'selected' : '' }}>BPJS</option>
                        <option value="umum" {{ old('pembayaran') == 'umum' ? 'selected' : '' }}>Umum</option>
                        <option value="asuransi" {{ old('pembayaran') == 'asuransi' ? 'selected' : '' }}>Asuransi</option>
                    </select>
                </div>
            </div>

            {{-- ================= PENANGGUNG JAWAB ================= --}}
            <div class="section-title">Penanggung Jawab</div>
            <div class="form-grid">
                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="pj_nama" value="{{ old('pj_nama') }}">
                </div>
                <div class="field">
                    <label>Hubungan</label>
                    <select name="pj_hubungan">
                        <option value="">-- Pilih --</option>
                        <option value="ayah" {{ old('pj_hubungan') == 'ayah' ? 'selected' : '' }}>Ayah</option>
                        <option value="ibu" {{ old('pj_hubungan') == 'ibu' ? 'selected' : '' }}>Ibu</option>
                        <option value="suami" {{ old('pj_hubungan') == 'suami' ? 'selected' : '' }}>Suami</option>
                        <option value="istri" {{ old('pj_hubungan') == 'istri' ? 'selected' : '' }}>Istri</option>
                        <option value="anak" {{ old('pj_hubungan') == 'anak' ? 'selected' : '' }}>Anak</option>
                        <option value="saudara" {{ old('pj_hubungan') == 'saudara' ? 'selected' : '' }}>Saudara</option>
                        <option value="lainnya" {{ old('pj_hubungan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>

                <div class="field">
                    <label>Jenis Kelamin</label>
                    <select name="pj_jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="L" {{ old('pj_jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('pj_jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="field">
                    <label>No Hp</label>
                    <input type="text" name="pj_no_hp" value="{{ old('pj_no_hp') }}">
                </div>
            </div>

            {{-- ================= ACTIONS ================= --}}
            <div class="form-actions">
                <button type="button" class="btn-consent">General Consent</button>
                <div class="form-actions-right">
                    <button type="submit" class="btn-simpan">💾 Simpan</button>
                    <button type="reset" class="btn-reset">↺ Reset</button>
                </div>
            </div>

        </form>

    </div>

@endsection