@extends('layouts.app')

@section('title', 'Pendaftaran - Klinik Utama Merah Putih')
@section('header-title', 'Pendaftaran')

@section('header-icon')
    <i class="fa-solid fa-address-book"></i>
@endsection

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-input.css') }}">
@endsection

@section('content')

<div class="panel">

    @if (session('success'))
        <div class="form-alert" style="background:#eafaf1;border-color:#a9dfbf;color:#1e8449;">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="form-alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER MASTER DATA --}}
    <div class="panel-head">
        <a href="{{ route('pendaftaran.index') }}" class="master-data-title">
            Master Data
        </a>

        <a href="{{ route('pendaftaran.create') }}" class="btn-input">
            + Input
        </a>
    </div>

    {{-- FILTER DATA --}}
    <form
        method="GET"
        action="{{ route('pendaftaran.index') }}"
        class="filter-row"
        id="filterForm"
    >
        <div class="filter-item">
            <label>Periode</label>

            <input
                type="date"
                name="dari"
                id="filterDari"
                value="{{ $dari ?? request('dari') }}"
            >

            <span>s.d</span>

            <input
                type="date"
                name="sampai"
                id="filterSampai"
                value="{{ $sampai ?? request('sampai') }}"
            >
        </div>

        <div class="filter-item filter-keyword">
            <label>Keyword</label>

            <input
                type="text"
                name="keyword"
                id="keywordInput"
                value="{{ $keyword ?? request('keyword') }}"
                placeholder="Cari nama, NIK, No. RM, No. HP, atau alamat"
                autocomplete="off"
            >
        </div>
    </form>

    {{-- TABEL MASTER DATA PASIEN --}}
    <div style="width: 100%; overflow-x: auto;">
        <table class="table-pendaftaran">

            <thead>
                <tr>
                    <th>No</th>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>NIK</th>
                    <th>Tgl. Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($dataPasien as $index => $item)

                    @php
                        $petaJenisKelamin = [
                            0 => 'Tidak diketahui',
                            1 => 'Laki-laki',
                            2 => 'Perempuan',
                            3 => 'Tidak dapat ditentukan',
                            4 => 'Tidak mengisi',
                        ];

                        $petaAgama = [
                            1 => 'Islam',
                            2 => 'Protestan',
                            3 => 'Katolik',
                            4 => 'Hindu',
                            5 => 'Buddha',
                            6 => 'Khonghucu',
                            7 => 'Penghayat',
                            8 => 'Lain-lain',
                        ];

                        $petaPendidikan = [
                            0 => 'Tidak sekolah',
                            1 => 'SD',
                            2 => 'SLTP sederajat',
                            3 => 'SLTA sederajat',
                            4 => 'D1-D3 sederajat',
                            5 => 'D4',
                            6 => 'S1',
                            7 => 'S2',
                            8 => 'S3',
                        ];

                        $petaPekerjaan = [
                            0 => 'Tidak bekerja',
                            1 => 'PNS',
                            2 => 'TNI/POLRI',
                            3 => 'BUMN',
                            4 => 'Pegawai Swasta/Wirausaha',
                            5 => 'Lain-lain',
                        ];

                        $jk = $petaJenisKelamin[(int) $item->jenis_kelamin]
                            ?? 'Tidak diketahui';

                        $agama = $petaAgama[(int) $item->agama] ?? '-';

                        $pendidikan = $petaPendidikan[(int) $item->pendidikan]
                            ?? '-';

                        $pekerjaan = $petaPekerjaan[(int) $item->pekerjaan]
                            ?? '-';

                        $umur = $item->tgl_lahir
                            ? \Carbon\Carbon::parse($item->tgl_lahir)->age . ' tahun'
                            : '-';

                        $dataDetail = [
                            'no_rm' => $item->no_rm ?? '',
                            'nama_pasien' => $item->nama_pasien ?? '',
                            'nik' => $item->nik ?? '',
                            'tgl_lahir' => $item->tgl_lahir ?? '',
                            'jenis_kelamin' => $jk,
                            'agama' => $agama,
                            'pendidikan' => $pendidikan,
                            'pekerjaan' => $pekerjaan,
                            'umur' => $umur,
                            'alamat' => $item->alamat ?? '',
                            'no_hp' => $item->no_hp ?? '',
                            'tempat_lahir' => $item->tempat_lahir ?? '',
                            'suku' => $item->suku ?? '',
                            'bangsa' => $item->bangsa ?? '',
                            'bahasa' => $item->bahasa ?? '',
                            'kelurahan' => $item->kelurahan ?? '',
                            'kecamatan' => $item->kecamatan ?? '',
                            'kabupaten' => $item->kabupaten ?? '',
                            'provinsi' => $item->provinsi ?? '',
                            'kode_pos' => $item->kode_pos ?? '',
                            'pembayaran' => match (strtolower($item->pembayaran ?? '')) {
                                'umum' => 'Umum',
                                'asuransi swasta' => 'Asuransi swasta',
                                'bpjs' => 'BPJS',
                                default => $item->pembayaran ?? '',
                            },
                            'poli' => $item->nama_poli ?? '',
                            'dokter' => $item->dokter ?? '',
                            'pj_nama' => $item->pj_nama ?? '',
                            'pj_hubungan' => $item->pj_hubungan ?? '',
                            'pj_jenis_kelamin' =>
                                $petaJenisKelamin[(int) $item->pj_jenis_kelamin] ?? '-',
                            'pj_no_hp' => $item->pj_no_hp ?? '',
                        ];
                    @endphp

                    <tr
                        class="row-pasien"
                        data-pasien="{{ json_encode($dataDetail, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}"
                        title="Klik dua kali untuk melihat detail"
                    >
                        <td>{{ $index + 1 }}</td>

                        <td>{{ $item->no_rm ?? '-' }}</td>

                        <td>{{ $item->nama_pasien ?? '-' }}</td>

                        <td>{{ $item->nik ?? '-' }}</td>

                        <td>
                            @if (!empty($item->tgl_lahir))
                                {{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $jk }}</td>

                        <td>{{ $item->alamat ?? '-' }}</td>

                        <td class="aksi-cell">

                            <button
                                type="button"
                                class="btn-aksi"
                                title="Lihat Detail"
                                onclick="bukaDetail(this)"
                            >
                                <svg viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>

                            <button
                                type="button"
                                class="btn-aksi"
                                title="Tambah Kunjungan"
                                onclick="bukaKunjungan(this)"
                            >
                                +
                            </button>

                            <button
                                type="button"
                                class="btn-aksi"
                                title="Edit Data"
                                onclick="bukaEdit(this)"
                            >
                                ✎
                            </button>

                            <button
                                type="button"
                                class="btn-aksi"
                                title="Hapus Data"
                                onclick="hapusPasien(this)"
                            >
                                🗑
                            </button>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="8" class="td-kosong">
                            Data pasien tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

{{-- MODAL DETAIL PASIEN --}}
<div class="modal-overlay" id="pasienModal">
    <div class="modal-box">

        <div class="modal-header">
            <h3>Data Pasien</h3>

            <button
                type="button"
                class="modal-close"
                onclick="closePasienModal()"
            >
                &times;
            </button>
        </div>

        <div class="modal-section-title">Identitas Pasien</div>

        {{-- Grid 2 kolom terisi selang-seling: kiri, kanan, kiri, kanan.
             Kolom kanan: Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos, Provinsi, Dokter --}}
        <div class="modal-grid">
            <div class="modal-row"><span class="modal-label">No. Rekam Medis</span><span>:</span><span id="m-no-rm"></span></div>
            <div class="modal-row"><span class="modal-label">No. HP</span><span>:</span><span id="m-hp"></span></div>
            <div class="modal-row"><span class="modal-label">Nama Lengkap</span><span>:</span><span id="m-nama"></span></div>
            <div class="modal-row"><span class="modal-label">Suku</span><span>:</span><span id="m-suku"></span></div>
            <div class="modal-row"><span class="modal-label">NIK</span><span>:</span><span id="m-nik"></span></div>
            <div class="modal-row"><span class="modal-label">Bangsa</span><span>:</span><span id="m-bangsa"></span></div>
            <div class="modal-row"><span class="modal-label">Tempat/Tgl Lahir</span><span>:</span><span id="m-ttl"></span></div>
            <div class="modal-row"><span class="modal-label">Bahasa</span><span>:</span><span id="m-bahasa"></span></div>
            <div class="modal-row"><span class="modal-label">Umur</span><span>:</span><span id="m-umur"></span></div>
            <div class="modal-row"><span class="modal-label">Alamat</span><span>:</span><span id="m-alamat"></span></div>
            <div class="modal-row"><span class="modal-label">Jenis Kelamin</span><span>:</span><span id="m-jk"></span></div>
            <div class="modal-row"><span class="modal-label">Kelurahan</span><span>:</span><span id="m-kelurahan"></span></div>
            <div class="modal-row"><span class="modal-label">Agama</span><span>:</span><span id="m-agama"></span></div>
            <div class="modal-row"><span class="modal-label">Kecamatan</span><span>:</span><span id="m-kecamatan"></span></div>
            <div class="modal-row"><span class="modal-label">Pendidikan</span><span>:</span><span id="m-pendidikan"></span></div>
            <div class="modal-row"><span class="modal-label">Kota/Kabupaten</span><span>:</span><span id="m-kabupaten"></span></div>
            <div class="modal-row"><span class="modal-label">Pekerjaan</span><span>:</span><span id="m-pekerjaan"></span></div>
            <div class="modal-row"><span class="modal-label">Kode Pos</span><span>:</span><span id="m-kodepos"></span></div>
            <div class="modal-row"><span class="modal-label">Poli</span><span>:</span><span id="m-poli"></span></div>
            <div class="modal-row"><span class="modal-label">Provinsi</span><span>:</span><span id="m-provinsi"></span></div>
            <div class="modal-row"><span class="modal-label">Pembayaran</span><span>:</span><span id="m-pembayaran"></span></div>
            <div class="modal-row"><span class="modal-label">Dokter</span><span>:</span><span id="m-dokter"></span></div>
        </div>

        <div class="modal-section-title">Penanggung Jawab</div>

        <div class="modal-grid">
            <div class="modal-row"><span class="modal-label">Nama</span><span>:</span><span id="m-pj-nama"></span></div>
            <div class="modal-row"><span class="modal-label">Hubungan</span><span>:</span><span id="m-pj-hubungan"></span></div>
            <div class="modal-row"><span class="modal-label">Jenis Kelamin</span><span>:</span><span id="m-pj-jk"></span></div>
            <div class="modal-row"><span class="modal-label">No HP</span><span>:</span><span id="m-pj-hp"></span></div>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH KUNJUNGAN --}}
<div class="custom-modal-overlay" id="kunjunganModal">
    <div class="custom-modal-box modal-kunjungan">

        <div class="custom-modal-header">
            <h3>Tambah Kunjungan</h3>
            <button type="button" class="custom-modal-close" onclick="tutupKunjungan()">&times;</button>
        </div>

        <form method="POST" action="" id="formKunjungan" autocomplete="off"
              onsubmit="simpanStatusKunjungan(this)">
            @csrf

            <div class="custom-modal-body">

                {{-- INPUT DATA --}}
                <div class="title-underline">Input Data</div>
                <div class="box">
                    <div class="top-grid">
                        <div class="field">
                            <label>Tgl Registrasi</label>
                            <input type="text" id="kj-tgl" readonly>
                        </div>

                        <div class="field">
                            <label for="kj-poli">Poli</label>
                            <select name="poli" id="kj-poli" required>
                                <option value="">-- Pilih Poli --</option>
                                <option value="saraf">Poli Saraf</option>
                                <option value="obgyn">Poli Obgyn</option>
                                <option value="jantung">Poli Jantung</option>
                                <option value="jiwa">Poli Jiwa</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>No RM</label>
                            <input type="text" id="kj-no-rm" readonly>
                        </div>

                        <div class="field">
                            <label for="kj-dokter">Dokter</label>
                            <input type="text" name="dokter" id="kj-dokter" readonly required>
                        </div>
                    </div>
                </div>

                {{-- IDENTITAS PASIEN (terisi otomatis, terkunci) --}}
                <div class="box">
                    <div class="box-title">Identitas Pasien</div>
                    <div class="cols">
                        <div class="col-left">
                            <div class="row"><label>No. Rekam Medis</label><input type="text" id="kj-rm2" readonly></div>
                            <div class="row"><label>Nama Lengkap</label><input type="text" id="kj-nama" readonly></div>
                            <div class="row"><label>NIK</label><input type="text" id="kj-nik" readonly></div>
                            <div class="row"><label>Tempat/Tgl Lahir</label><input type="text" id="kj-ttl" readonly></div>
                            <div class="row"><label>Umur</label><input type="text" id="kj-umur" readonly></div>
                            <div class="row"><label>Jenis Kelamin</label><input type="text" id="kj-jk" readonly></div>
                            <div class="row"><label>Agama</label><input type="text" id="kj-agama" readonly></div>
                            <div class="row"><label>Pendidikan</label><input type="text" id="kj-pendidikan" readonly></div>
                            <div class="row"><label>Pekerjaan</label><input type="text" id="kj-pekerjaan" readonly></div>
                        </div>

                        <div class="col-right">
                            <div class="row"><label>No HP</label><input type="text" id="kj-hp" readonly></div>
                            <div class="row">
                                <label>Suku</label>
                                <div class="pair-suku">
                                    <input type="text" id="kj-suku" readonly>
                                    <label>Bangsa</label>
                                    <input type="text" id="kj-bangsa" readonly>
                                </div>
                            </div>
                            <div class="row"><label>Bahasa</label><input type="text" id="kj-bahasa" readonly></div>
                            <div class="row top"><label>Alamat</label><textarea id="kj-alamat" readonly></textarea></div>
                            <div class="row">
                                <label></label>
                                <div class="split-2">
                                    <input type="text" id="kj-kelurahan" readonly>
                                    <input type="text" id="kj-kecamatan" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <label></label>
                                <div class="split-2">
                                    <input type="text" id="kj-kabupaten" readonly>
                                    <input type="text" id="kj-provinsi" readonly>
                                </div>
                            </div>
                            <div class="row"><label>Kode Pos</label><input type="text" id="kj-kodepos" readonly></div>
                            <div class="row">
                                <label for="kj-pembayaran">Pembayaran</label>
                                <select name="pembayaran" id="kj-pembayaran" required>
                                    <option value="">-- Pilih Pembayaran --</option>
                                    <option value="bpjs">BPJS</option>
                                    <option value="umum">Umum</option>
                                    <option value="asuransi">Asuransi swasta</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PENANGGUNG JAWAB (dikosongkan) --}}
                <div class="box">
                    <div class="box-title">Penanggung Jawab</div>
                    <div class="cols">
                        <div class="col-left">
                            <div class="row">
                                <label for="kj-pj-nama">Nama</label>
                                <input type="text" name="pj_nama" id="kj-pj-nama"
                                       placeholder="Nama penanggung jawab" maxlength="100" required>
                            </div>
                            <div class="row">
                                <label for="kj-pj-jk">Jenis Kelamin</label>
                                <select name="pj_jenis_kelamin" id="kj-pj-jk" required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="0">Tidak diketahui</option>
                                    <option value="1">Laki-laki</option>
                                    <option value="2">Perempuan</option>
                                    <option value="3">Tidak dapat ditentukan</option>
                                    <option value="4">Tidak mengisi</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-right">
                            <div class="row">
                                <label for="kj-pj-hubungan">Hubungan</label>
                                <select name="pj_hubungan" id="kj-pj-hubungan" required>
                                    <option value="">-- Pilih Hubungan --</option>
                                    <option value="ayah">Ayah</option>
                                    <option value="ibu">Ibu</option>
                                    <option value="suami">Suami</option>
                                    <option value="istri">Istri</option>
                                    <option value="anak">Anak</option>
                                    <option value="saudara">Saudara</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div class="row">
                                <label for="kj-pj-hp">No HP</label>
                                <input type="text" name="pj_no_hp" id="kj-pj-hp" value="+62"
                                       inputmode="numeric" maxlength="15" required
                                       oninput="formatHpKj(this)">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="tutupKunjungan()">Batal</button>
                <button type="submit" class="btn-modal btn-modal-primary">Simpan Kunjungan</button>
            </div>
        </form>

    </div>
</div>

{{-- MODAL EDIT --}}
<div class="custom-modal-overlay" id="editModal">
    <div class="custom-modal-box modal-kunjungan">

        <div class="custom-modal-header">
            <h3>Edit Data Pasien</h3>

            <button type="button" class="custom-modal-close" onclick="tutupEdit()">&times;</button>
        </div>

        <div class="custom-modal-body">
            <input type="hidden" id="editRow">

            <div class="box">
                <div class="box-title">Identitas Pasien</div>
                <div class="cols">
                    <div class="col-left">
                        <div class="row"><label>No. Rekam Medis</label><input type="text" id="editRm" readonly class="input-terkunci"></div>
                        <div class="row"><label>Nama Lengkap</label><input type="text" id="editNama" maxlength="100"></div>
                        <div class="row"><label>NIK</label><input type="text" id="editNik" inputmode="numeric" maxlength="16"
                             oninput="this.value = this.value.replace(/[^0-9]/g, '')"></div>
                        <div class="row">
                            <label>Tempat/Tgl Lahir</label>
                            <div class="split-2">
                                <input type="text" id="editTempatLahir" maxlength="50" placeholder="Tempat lahir">
                                <input type="date" id="editTglLahir" onchange="hitungUmurEdit()">
                            </div>
                        </div>
                        <div class="row"><label>Umur</label><input type="text" id="editUmur" readonly class="input-terkunci"></div>
                        <div class="row">
                            <label>Jenis Kelamin</label>
                            <select id="editJk">
                                <option value="0">Tidak diketahui</option>
                                <option value="1">Laki-laki</option>
                                <option value="2">Perempuan</option>
                                <option value="3">Tidak dapat ditentukan</option>
                                <option value="4">Tidak mengisi</option>
                            </select>
                        </div>
                        <div class="row">
                            <label>Agama</label>
                            <select id="editAgama">
                                <option value="1">Islam</option>
                                <option value="2">Protestan</option>
                                <option value="3">Katolik</option>
                                <option value="4">Hindu</option>
                                <option value="5">Buddha</option>
                                <option value="6">Khonghucu</option>
                                <option value="7">Penghayat</option>
                                <option value="8">Lain-lain</option>
                            </select>
                        </div>
                        <div class="row">
                            <label>Pendidikan</label>
                            <select id="editPendidikan">
                                <option value="0">Tidak sekolah</option>
                                <option value="1">SD</option>
                                <option value="2">SLTP sederajat</option>
                                <option value="3">SLTA sederajat</option>
                                <option value="4">D1-D3 sederajat</option>
                                <option value="5">D4</option>
                                <option value="6">S1</option>
                                <option value="7">S2</option>
                                <option value="8">S3</option>
                            </select>
                        </div>
                        <div class="row">
                            <label>Pekerjaan</label>
                            <select id="editPekerjaan">
                                <option value="0">Tidak bekerja</option>
                                <option value="1">PNS</option>
                                <option value="2">TNI/POLRI</option>
                                <option value="3">BUMN</option>
                                <option value="4">Pegawai Swasta/Wirausaha</option>
                                <option value="5">Lain-lain</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-right">
                        <div class="row">
                            <label>No HP</label>
                            <input type="text" id="editHp" inputmode="numeric"
                                   oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                        </div>
                        <div class="row">
                            <label>Suku</label>
                            <div class="pair-suku">
                                <input type="text" id="editSuku">
                                <label>Bangsa</label>
                                <input type="text" id="editBangsa">
                            </div>
                        </div>
                        <div class="row"><label>Bahasa</label><input type="text" id="editBahasa"></div>
                        <div class="row top"><label>Alamat</label><textarea id="editAlamat"></textarea></div>
                        <div class="row">
                            <label></label>
                            <div class="split-2">
                                <input type="text" id="editKelurahan" placeholder="Kelurahan">
                                <input type="text" id="editKecamatan" placeholder="Kecamatan">
                            </div>
                        </div>
                        <div class="row">
                            <label></label>
                            <div class="split-2">
                                <input type="text" id="editKabupaten" placeholder="Kota/Kabupaten">
                                <input type="text" id="editProvinsi" placeholder="Provinsi">
                            </div>
                        </div>
                        <div class="row"><label>Kode Pos</label><input type="text" id="editKodePos" inputmode="numeric"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="custom-modal-footer">
            <button type="button" class="btn-modal btn-modal-secondary" onclick="tutupEdit()">
                Batal
            </button>

            <button type="button" class="btn-modal btn-modal-primary" onclick="simpanEdit()">
                Simpan Perubahan
            </button>
        </div>

    </div>
</div>

@endsection

@section('extra-js')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('filterForm');
    const keyword = document.getElementById('keywordInput');
    const dari = document.getElementById('filterDari');
    const sampai = document.getElementById('filterSampai');

    if (!form) return;

    let timer;

    function kirimFilter() {
        clearTimeout(timer);
        timer = setTimeout(function () {
            form.requestSubmit();
        }, 400);
    }

    if (keyword) {
        keyword.addEventListener('input', kirimFilter);
    }

    // Batasi kalender: tanggal akhir tidak boleh sebelum tanggal awal
    function aturBatasPeriode() {
        if (sampai) sampai.min = dari && dari.value ? dari.value : '';
        if (dari) dari.max = sampai && sampai.value ? sampai.value : '';
    }

    // Peringatan jika rentang tidak relevan (mis. 10 s.d 9)
    function periodeValid(inputBerubah) {
        if (dari && sampai && dari.value && sampai.value && dari.value > sampai.value) {
            alert(
                'Rentang periode tidak valid.\n' +
                'Tanggal awal tidak boleh lebih besar dari tanggal akhir.'
            );
            inputBerubah.value = '';
            aturBatasPeriode();
            inputBerubah.focus();
            return false;
        }
        return true;
    }

    aturBatasPeriode();

    if (dari) {
        dari.addEventListener('change', function () {
            if (!periodeValid(dari)) return;
            aturBatasPeriode();
            form.requestSubmit();
        });
    }

    if (sampai) {
        sampai.addEventListener('change', function () {
            if (!periodeValid(sampai)) return;
            aturBatasPeriode();
            form.requestSubmit();
        });
    }
});


function ambilDataBaris(button) {
    const row = button.closest('.row-pasien');

    if (!row) return null;

    try {
        return {
            row: row,
            data: JSON.parse(row.getAttribute('data-pasien'))
        };
    } catch (error) {
        console.error('Gagal membaca data pasien:', error);
        alert('Data pasien gagal dibaca.');
        return null;
    }
}

function formatTanggal(tanggal) {
    if (!tanggal) return '-';

    const bagian = String(tanggal).substring(0, 10).split('-');

    if (bagian.length !== 3) return tanggal;

    return `${bagian[2]}-${bagian[1]}-${bagian[0]}`;
}

function isiDetail(data) {
    const peta = {
        'm-no-rm': data.no_rm,
        'm-hp': data.no_hp,
        'm-nama': data.nama_pasien,
        'm-suku': data.suku,
        'm-nik': data.nik,
        'm-bangsa': data.bangsa,
        'm-bahasa': data.bahasa,
        'm-umur': data.umur,
        'm-alamat': data.alamat,
        'm-jk': data.jenis_kelamin,
        'm-kelurahan': data.kelurahan,
        'm-agama': data.agama,
        'm-kecamatan': data.kecamatan,
        'm-pendidikan': data.pendidikan,
        'm-kabupaten': data.kabupaten,
        'm-pekerjaan': data.pekerjaan,
        'm-provinsi': data.provinsi,
        'm-kodepos': data.kode_pos,
        'm-pembayaran': data.pembayaran,
        'm-poli': data.poli,
        'm-dokter': data.dokter,
        'm-pj-nama': data.pj_nama,
        'm-pj-hubungan': data.pj_hubungan,
        'm-pj-jk': data.pj_jenis_kelamin,
        'm-pj-hp': data.pj_no_hp
    };

    Object.entries(peta).forEach(([id, nilai]) => {
        const elemen = document.getElementById(id);
        if (elemen) elemen.textContent = nilai || '-';
    });

    const ttl = document.getElementById('m-ttl');

    if (ttl) {
        ttl.textContent =
            `${data.tempat_lahir || '-'} / ${formatTanggal(data.tgl_lahir)}`;
    }
}

function bukaDetail(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;

    isiDetail(hasil.data);
    document.getElementById('pasienModal').classList.add('show');
}

function closePasienModal() {
    document.getElementById('pasienModal').classList.remove('show');
}

/* ===== TAMBAH KUNJUNGAN ===== */
const dokterPerPoliKj = {
    saraf: 'dr. Ratna Wijayanti, Sp.S',
    obgyn: 'dr. Aditya Iqbal M, Sp.OG',
    jantung: 'dr. Bagus Fitriadi, Sp.JP',
    jiwa: 'dr. Devi Anggraeni P, Sp.KJ'
};

document.addEventListener('DOMContentLoaded', function () {
    const poli = document.getElementById('kj-poli');
    if (poli) {
        poli.addEventListener('change', function () {
            document.getElementById('kj-dokter').value = dokterPerPoliKj[poli.value] || '';
        });
    }
});

function formatHpKj(input) {
    let sisa = input.value.startsWith('+62') ? input.value.slice(3) : input.value;
    let digit = sisa.replace(/[^0-9]/g, '').replace(/^0+/, '');
    if (digit.startsWith('62')) digit = digit.slice(2);
    input.value = '+62' + digit.slice(0, 10);
}

function bukaKunjungan(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;

    const d = hasil.data;
    const now = new Date();
    const tgl = [
        String(now.getDate()).padStart(2, '0'),
        String(now.getMonth() + 1).padStart(2, '0'),
        now.getFullYear()
    ].join('-');

    const peta = {
        'kj-tgl': tgl,
        'kj-no-rm': d.no_rm,
        'kj-rm2': d.no_rm,
        'kj-nama': d.nama_pasien,
        'kj-nik': d.nik,
        'kj-ttl': `${d.tempat_lahir || '-'}, ${formatTanggal(d.tgl_lahir)}`,
        'kj-umur': d.umur,
        'kj-jk': d.jenis_kelamin,
        'kj-agama': d.agama,
        'kj-pendidikan': d.pendidikan,
        'kj-pekerjaan': d.pekerjaan,
        'kj-hp': d.no_hp,
        'kj-suku': d.suku,
        'kj-bangsa': d.bangsa,
        'kj-bahasa': d.bahasa,
        'kj-alamat': d.alamat,
        'kj-kelurahan': d.kelurahan,
        'kj-kecamatan': d.kecamatan,
        'kj-kabupaten': d.kabupaten,
        'kj-provinsi': d.provinsi,
        'kj-kodepos': d.kode_pos
    };

    Object.entries(peta).forEach(([id, nilai]) => {
        const el = document.getElementById(id);
        if (el) el.value = nilai || '';
    });

    // Pembayaran terakhir pasien dijadikan nilai awal (masih bisa diubah)
    const bayar = String(d.pembayaran || '').toLowerCase();
    document.getElementById('kj-pembayaran').value =
        bayar === 'bpjs' ? 'bpjs' :
        bayar === 'umum' ? 'umum' :
        bayar.includes('asuransi') ? 'asuransi' : '';

    // Poli, dokter, dan penanggung jawab dikosongkan
    ['kj-poli', 'kj-dokter', 'kj-pj-nama', 'kj-pj-jk', 'kj-pj-hubungan']
        .forEach(id => document.getElementById(id).value = '');
    document.getElementById('kj-pj-hp').value = '+62';

    document.getElementById('formKunjungan').action =
        "{{ url('pendaftaran') }}/" + encodeURIComponent(d.no_rm) + "/kunjungan";

    document.getElementById('kunjunganModal').classList.add('show');
}

function tutupKunjungan() {
    document.getElementById('kunjunganModal').classList.remove('show');
}

// Status "periksa" di browser, sama seperti saat pendaftaran baru
function simpanStatusKunjungan(form) {
    const awalan = {
        obgyn: 'obgyn', saraf: 'syaraf', jantung: 'jantung', jiwa: 'jiwa'
    }[form.elements['poli'].value];

    const noRm = (document.getElementById('kj-no-rm').value || '').trim().toUpperCase();
    if (!awalan || !noRm) return;

    try {
        const status = JSON.parse(localStorage.getItem(awalan + '_status_periksa')) || {};
        status[noRm] = 'periksa';
        localStorage.setItem(awalan + '_status_periksa', JSON.stringify(status));

        const waktu = JSON.parse(localStorage.getItem(awalan + '_waktu_selesai')) || {};
        delete waktu[noRm];
        localStorage.setItem(awalan + '_waktu_selesai', JSON.stringify(waktu));
    } catch (err) {
        console.error(err);
    }
}

// Pilih <option> berdasarkan teks (data di tabel disimpan sebagai teks, mis. "Perempuan")
function pilihOpsiByTeks(idSelect, teks) {
    const sel = document.getElementById(idSelect);
    if (!sel) return;

    const opsi = Array.from(sel.options).find(
        o => o.text.trim().toLowerCase() === String(teks || '').trim().toLowerCase()
    );

    sel.value = opsi ? opsi.value : sel.options[0].value;
}

function hitungUmurEdit() {
    const tgl = document.getElementById('editTglLahir').value;
    const out = document.getElementById('editUmur');

    if (!tgl) {
        out.value = '-';
        return;
    }

    const lahir = new Date(tgl + 'T00:00:00');
    const now = new Date();
    let umur = now.getFullYear() - lahir.getFullYear();
    const belumUlangTahun =
        now.getMonth() < lahir.getMonth() ||
        (now.getMonth() === lahir.getMonth() && now.getDate() < lahir.getDate());

    if (belumUlangTahun) umur--;

    out.value = umur >= 0 ? umur + ' tahun' : '-';
}

function bukaEdit(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;

    const d = hasil.data;

    document.getElementById('editRow').value =
        Array.from(document.querySelectorAll('.row-pasien')).indexOf(hasil.row);

    const peta = {
        'editRm': d.no_rm,
        'editNama': d.nama_pasien,
        'editNik': d.nik,
        'editTempatLahir': d.tempat_lahir,
        'editTglLahir': String(d.tgl_lahir || '').substring(0, 10),
        'editHp': d.no_hp,
        'editSuku': d.suku,
        'editBangsa': d.bangsa,
        'editBahasa': d.bahasa,
        'editAlamat': d.alamat,
        'editKelurahan': d.kelurahan,
        'editKecamatan': d.kecamatan,
        'editKabupaten': d.kabupaten,
        'editProvinsi': d.provinsi,
        'editKodePos': d.kode_pos
    };

    Object.entries(peta).forEach(([id, nilai]) => {
        const el = document.getElementById(id);
        if (el) el.value = nilai || '';
    });

    pilihOpsiByTeks('editJk', d.jenis_kelamin);
    pilihOpsiByTeks('editAgama', d.agama);
    pilihOpsiByTeks('editPendidikan', d.pendidikan);
    pilihOpsiByTeks('editPekerjaan', d.pekerjaan);

    hitungUmurEdit();

    document.getElementById('editModal').classList.add('show');
}

function tutupEdit() {
    document.getElementById('editModal').classList.remove('show');
}

async function simpanEdit() {
    const noRm = document.getElementById('editRm').value.trim();

    if (!noRm) {
        alert('No. Rekam Medis tidak ditemukan.');
        return;
    }

    const nilai = id => (document.getElementById(id)?.value ?? '').trim();

    const payload = {
        nama_pasien: nilai('editNama'),
        nik: nilai('editNik'),
        tempat_lahir: nilai('editTempatLahir'),
        tgl_lahir: nilai('editTglLahir'),
        jenis_kelamin: nilai('editJk'),
        agama: nilai('editAgama'),
        pendidikan: nilai('editPendidikan'),
        pekerjaan: nilai('editPekerjaan'),
        no_hp: nilai('editHp'),
        suku: nilai('editSuku'),
        bangsa: nilai('editBangsa'),
        bahasa: nilai('editBahasa'),
        alamat: nilai('editAlamat'),
        kelurahan: nilai('editKelurahan'),
        kecamatan: nilai('editKecamatan'),
        kabupaten: nilai('editKabupaten'),
        provinsi: nilai('editProvinsi'),
        kode_pos: nilai('editKodePos')
    };

    const tombol = document.querySelector('#editModal .btn-modal-primary');
    const teksAwal = tombol.textContent;
    tombol.disabled = true;
    tombol.textContent = 'Menyimpan...';

    try {
        const response = await fetch(
            "{{ url('pendaftaran') }}/" + encodeURIComponent(noRm),
            {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify(payload)
            }
        );

        const teks = await response.text();
        let hasil;

        try {
            hasil = JSON.parse(teks);
        } catch (e) {
            console.error('Respons bukan JSON:', response.status, teks);
            throw new Error('Server tidak mengirim respons JSON (status ' + response.status + ').');
        }

        if (!response.ok || !hasil.success) {
            const pesanValidasi = Object.values(hasil.errors || {}).flat().join('\n');
            throw new Error(pesanValidasi || hasil.message || 'Gagal menyimpan perubahan.');
        }

        alert(hasil.message || 'Data pasien berhasil diperbarui.');
        window.location.reload();

    } catch (error) {
        console.error('Gagal menyimpan edit pasien:', error);
        alert('Perubahan belum tersimpan: ' + error.message);
    } finally {
        tombol.disabled = false;
        tombol.textContent = teksAwal;
    }
}

function hapusPasien(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;

    alert(
        'Data pasien tidak dihapus karena route hapus database belum dihubungkan.\n' +
        'Ini untuk mencegah data hanya hilang dari tampilan browser.'
    );
}

document.addEventListener('dblclick', function(event) {
    const row = event.target.closest('.row-pasien');
    if (!row) return;

    const tombolDetail = row.querySelector('.btn-aksi');
    if (tombolDetail) bukaDetail(tombolDetail);
});

document.addEventListener('click', function(event) {
    if (event.target === document.getElementById('pasienModal')) {
        closePasienModal();
    }

    if (event.target === document.getElementById('kunjunganModal')) {
        tutupKunjungan();
    }

    if (event.target === document.getElementById('editModal')) {
        tutupEdit();
    }
});

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePasienModal();
        tutupKunjungan();
        tutupEdit();
    }
});
</script>
<<<<<<< Updated upstream

@endsection
=======
@endsectionsS
