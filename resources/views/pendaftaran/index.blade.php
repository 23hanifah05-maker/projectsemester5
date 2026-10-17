@extends('layouts.app')

@section('title', 'Pendaftaran - Klinik Utama Merah Putih')
@section('header-title', 'Pendaftaran')

@section('header-icon')
    <i class="fa-solid fa-address-book"></i>
@endsection

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
@endsection

@section('content')

<div class="panel">

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
            <div class="modal-row"><span class="modal-label">Provinsi</span><span>:</span><span id="m-provinsi"></span></div>
            <div class="modal-row"><span class="modal-label">Kode Pos</span><span>:</span><span id="m-kodepos"></span></div>
            <div class="modal-row"><span class="modal-label">Pembayaran</span><span>:</span><span id="m-pembayaran"></span></div>
            <div class="modal-row"><span class="modal-label">Poli</span><span>:</span><span id="m-poli"></span></div>
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
    <div class="custom-modal-box">

        <div class="custom-modal-header">
            <h3>Tambah Kunjungan</h3>

            <button
                type="button"
                class="custom-modal-close"
                onclick="tutupKunjungan()"
            >
                &times;
            </button>
        </div>

        <div class="custom-modal-body">
            <div class="form-group">
                <label>No. Rekam Medis</label>
                <input type="text" id="kunjunganRm" readonly>
            </div>

            <div class="form-group">
                <label>Nama Pasien</label>
                <input type="text" id="kunjunganNama" readonly>
            </div>

            <div class="form-group">
                <label>Tanggal Kunjungan</label>
                <input type="date" id="kunjunganTanggal">
            </div>

            <div class="form-group">
                <label>Poli</label>
                <select id="kunjunganPoli">
                    <option value="">-- Pilih Poli --</option>
                    <option value="Poli Umum">Poli Umum</option>
                    <option value="Poli Syaraf">Poli Syaraf</option>
                    <option value="Poli Anak">Poli Anak</option>
                    <option value="Poli Gigi">Poli Gigi</option>
                    <option value="Poli Mata">Poli Mata</option>
                </select>
            </div>

            <div class="form-group">
                <label>Keluhan</label>
                <textarea id="kunjunganKeluhan" placeholder="Masukkan keluhan pasien..."></textarea>
            </div>
        </div>

        <div class="custom-modal-footer">
            <button type="button" class="btn-modal btn-modal-secondary" onclick="tutupKunjungan()">
                Batal
            </button>

            <button type="button" class="btn-modal btn-modal-primary" onclick="simpanKunjungan()">
                Simpan Kunjungan
            </button>
        </div>

    </div>
</div>

{{-- MODAL EDIT --}}
<div class="custom-modal-overlay" id="editModal">
    <div class="custom-modal-box">

        <div class="custom-modal-header">
            <h3>Edit Data Pasien</h3>

            <button
                type="button"
                class="custom-modal-close"
                onclick="tutupEdit()"
            >
                &times;
            </button>
        </div>

        <div class="custom-modal-body">
            <input type="hidden" id="editRow">

            <div class="form-group">
                <label>No. Rekam Medis</label>
                <input type="text" id="editRm" readonly class="input-terkunci">
            </div>

            <div class="form-group">
                <label>Nama Pasien</label>
                <input type="text" id="editNama" readonly class="input-terkunci">
            </div>

            <div class="form-group">
                <label>NIK</label>
                <input type="text" id="editNik" readonly class="input-terkunci">
            </div>

            <div class="form-group">
                <label>No. HP</label>
                <input
                    type="text"
                    id="editHp"
                    inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea id="editAlamat"></textarea>
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

    if (dari) {
        dari.addEventListener('change', function () {
            form.requestSubmit();
        });
    }

    if (sampai) {
        sampai.addEventListener('change', function () {
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

function bukaKunjungan(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;

    const data = hasil.data;
    const sekarang = new Date();
    const tanggal = [
        sekarang.getFullYear(),
        String(sekarang.getMonth() + 1).padStart(2, '0'),
        String(sekarang.getDate()).padStart(2, '0')
    ].join('-');

    document.getElementById('kunjunganRm').value = data.no_rm || '';
    document.getElementById('kunjunganNama').value = data.nama_pasien || '';
    document.getElementById('kunjunganTanggal').value = tanggal;
    document.getElementById('kunjunganPoli').value = data.poli || '';
    document.getElementById('kunjunganKeluhan').value = '';

    document.getElementById('kunjunganModal').classList.add('show');
}

function tutupKunjungan() {
    document.getElementById('kunjunganModal').classList.remove('show');
}

function simpanKunjungan() {
    const tanggal = document.getElementById('kunjunganTanggal').value;
    const poli = document.getElementById('kunjunganPoli').value;

    if (!tanggal) {
        alert('Tanggal kunjungan wajib diisi.');
        return;
    }

    if (!poli) {
        alert('Silakan pilih poli.');
        return;
    }

    alert(
        'Validasi kunjungan berhasil.\n\n' +
        'Perlu dihubungkan ke controller kunjungan agar data benar-benar tersimpan.'
    );
}

function bukaEdit(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;

    const data = hasil.data;

    document.getElementById('editRow').value =
        Array.from(document.querySelectorAll('.row-pasien')).indexOf(hasil.row);

    document.getElementById('editRm').value = data.no_rm || '';
    document.getElementById('editNama').value = data.nama_pasien || '';
    document.getElementById('editNik').value = data.nik || '';
    document.getElementById('editHp').value = data.no_hp || '';
    document.getElementById('editAlamat').value = data.alamat || '';

    document.getElementById('editModal').classList.add('show');
}

function tutupEdit() {
    document.getElementById('editModal').classList.remove('show');
}

function simpanEdit() {
    alert(
        'Form edit sudah terbuka, tetapi penyimpanan ke database perlu dihubungkan ke route update pasien.'
    );
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

@endsection