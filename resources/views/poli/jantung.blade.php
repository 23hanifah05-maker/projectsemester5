@extends('layouts.app')

@section('title', 'Poli Jantung - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-stethoscope"></i>
@endsection
@section('header-title', 'Poli Jantung')

@section('extra-css')
    {{-- Pakai CSS yang sama dengan Poli Obgyn --}}
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/poli-jantung.css') }}?v={{ @filemtime(public_path('css/poli-jantung.css')) }}">

    <style>
        /* STATUS PASIEN: 2 BULATAN
           Kuning = selesai koding, Hijau = selesai pelayanan, Abu-abu = belum */
        .status-dots {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-left: 10px;
            vertical-align: middle;
        }
        .status-dot {
            display: inline-block;
            flex: 0 0 auto;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #d9d9d9;
            border: 1px solid #c4c4c4;
            box-sizing: border-box;
            transition: background .15s, border-color .15s, box-shadow .15s;
        }
        .status-dot.koding.on {
            background: #f5b700;
            border-color: #d99f00;
            box-shadow: 0 0 0 2px rgba(245, 183, 0, .25);
        }
        .status-dot.assesment.on {
            background: #28a745;
            border-color: #1e8e3a;
            box-shadow: 0 0 0 2px rgba(40, 167, 69, .25);
        }
    </style>
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai hanya bila controller belum mengirim data pasien.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien) || count($pasien) === 0) {
            $pasien = collect([
                ['no_rm' => 'E000031', 'nama_pasien' => 'Hendra Wijaya',     'nik' => '3501071504700001', 'tgl_lahir' => '1970-04-15', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Gajah Mada No. 7',    'pembayaran' => 'BPJS',            'status_periksa' => 'selesai', 'status_koding' => true],
                ['no_rm' => 'E000032', 'nama_pasien' => 'Lestari Wulandari', 'nik' => '3504216209680002', 'tgl_lahir' => '1968-09-22', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Cempaka No. 19',     'pembayaran' => 'umum',            'status_periksa' => 'selesai', 'status_koding' => false],
                ['no_rm' => 'E000033', 'nama_pasien' => 'Bambang Hartono',   'nik' => '3509100211750003', 'tgl_lahir' => '1975-11-02', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Veteran No. 33',     'pembayaran' => 'asuransi swasta', 'status_periksa' => 'periksa', 'status_koding' => true],
                ['no_rm' => 'E000034', 'nama_pasien' => 'Ratna Kusuma',      'nik' => '3512285307820004', 'tgl_lahir' => '1982-07-13', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Anggrek No. 4',      'pembayaran' => 'BPJS',            'status_periksa' => 'periksa', 'status_koding' => false],
                ['no_rm' => 'E000035', 'nama_pasien' => 'Yusuf Ramadhan',    'nik' => '3518140806600005', 'tgl_lahir' => '1960-06-08', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Kartini No. 26',     'pembayaran' => 'umum',            'status_periksa' => 'periksa', 'status_koding' => false],
                ['no_rm' => 'E000036', 'nama_pasien' => 'Mega Puspitasari',  'nik' => '3523196412900006', 'tgl_lahir' => '1990-12-24', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Imam Bonjol No. 11', 'pembayaran' => 'BPJS',            'status_periksa' => 'periksa', 'status_koding' => false],
            ])->map(fn ($p, $i) => (object) array_merge([
                'tempat_lahir' => 'Ngawi', 'agama' => 'Islam', 'pendidikan' => 'SMA', 'pekerjaan' => 'Swasta',
                'no_hp' => '08123456' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'suku' => 'Jawa', 'bangsa' => 'Indonesia', 'bahasa' => 'Indonesia',
                'kelurahan' => 'Ngawi', 'kecamatan' => 'Ngawi', 'kabupaten' => 'Ngawi',
                'provinsi' => 'Jawa Timur', 'kode_pos' => '63211',
                'poli' => 'Poli Jantung', 'dokter' => 'dr. Bagus Fitriadi, Sp.JP',
                'tgl_registrasi' => '2026-11-27',
                'umur' => \Carbon\Carbon::parse($p['tgl_lahir'])->age . ' Tahun',
                'pj_nama' => 'Andi Pratama', 'pj_hubungan' => 'Keluarga', 'pj_jenis_kelamin' => 'Laki-laki',
                'pj_no_hp' => '08523451289',
            ], $p));
        }
    @endphp

    <div class="panel">

        <div class="panel-head">
            <a href="#" class="master-data-title">Daftar Pasien</a>
        </div>

        <form method="GET" action="{{ route('poli.jantung') }}" class="filter-row">
            <div class="filter-item">
                <label>Periode</label>
                <input type="date" name="dari" id="tanggalDariJantung" value="{{ request('dari') }}">
                <span>s.d</span>
                <input type="date" name="sampai" id="tanggalSampaiJantung" value="{{ request('sampai') }}">
            </div>

            <div class="filter-item filter-keyword">
                <label for="inputKeyword">Keyword</label>
                <input type="text" id="inputKeyword" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama, NIK, atau No. RM">
            </div>
        </form>

        <script>
            (function () {
                const dari = document.getElementById('tanggalDariJantung');
                const sampai = document.getElementById('tanggalSampaiJantung');

                function aturBatasTanggal() {
                    if (dari.value) {
                        sampai.min = dari.value;
                        if (sampai.value && sampai.value < dari.value) {
                            sampai.value = dari.value;
                        }
                    } else {
                        sampai.removeAttribute('min');
                    }
                }

                aturBatasTanggal();
                dari.addEventListener('change', aturBatasTanggal);
            })();
        </script>

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
                    <th>Tgl. Kunjungan</th>
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pasien as $index => $item)
                    <tr class="row-pasien" data-pasien='@json($item)'
                        data-url="{{ url('/poli/jantung/detail/' . $item->no_rm) }}"
                        title="Klik untuk melihat riwayat pasien">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->no_rm }}</td>
                        <td>{{ $item->nama_pasien }}</td>

                        {{-- NIK disamarkan: 4 digit awal tampil, sisanya X --}}
                        <td>{{ \App\Helpers\Mask::nik($item->nik, 4, 'X') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $item->jenis_kelamin ?? '-' }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ !empty($item->tgl_registrasi) ? \Carbon\Carbon::parse($item->tgl_registrasi)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $item->pembayaran }}</td>
                        <td class="aksi-cell">
                            <div class="aksi-wrap">
                                <button type="button" class="btn-aksi" title="Lihat Detail"
                                        onclick="event.stopPropagation(); bukaDetail(this)">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>

                                @php
                                    $sudahKoding = in_array($item->status_koding ?? false, [true, 1, '1', 'selesai'], true);
                                    $sudahAssesment = strtolower((string) ($item->status_periksa ?? 'periksa')) === 'selesai';
                                @endphp

                                <span class="status-dots">
                                    <span class="status-dot koding {{ $sudahKoding ? 'on' : '' }}"
                                          title="{{ $sudahKoding ? 'Selesai koding' : 'Belum dikoding' }}"></span>
                                    <span class="status-dot assesment {{ $sudahAssesment ? 'on' : '' }}"
                                          title="{{ $sudahAssesment ? 'Selesai pelayanan (SOAP/Assessment disimpan)' : 'SOAP/Assessment belum disimpan' }}"></span>
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                            <td class="aksi-cell">
                                <div class="aksi-wrap">
                                    <span class="btn-aksi">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endfor
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- MODAL DETAIL PASIEN (sama dengan Poli Obgyn) --}}
    <div class="modal-overlay" id="pasienModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Data Pasien</h3>
                <button type="button" class="modal-close" onclick="closePasienModal()">&times;</button>
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

@endsection

@section('extra-js')
<script>
// Nilai dianggap "aktif" bila true / 1 / '1' / 'selesai'
function statusAktif(nilai) {
    return nilai === true || nilai === 1 || nilai === '1' || nilai === 'selesai';
}

// Atur 2 bulatan status, lalu urutkan: yang belum selesai di atas
function terapkanStatusPeriksa() {
    const rows = Array.from(document.querySelectorAll('.row-pasien'));
    if (rows.length === 0) return;

    rows.forEach(function (row, index) {
        if (row.dataset.urutAwal === undefined) row.dataset.urutAwal = index;

        let data;
        try {
            data = JSON.parse(row.getAttribute('data-pasien'));
        } catch (error) {
            return;
        }

        const selesaiAssesment = String(data.status_periksa || 'periksa').toLowerCase() === 'selesai';
        const selesaiKoding = statusAktif(data.status_koding);

        row.dataset.statusPeriksa = selesaiAssesment ? 'selesai' : 'periksa';

        const dotKoding = row.querySelector('.status-dot.koding');
        const dotAssesment = row.querySelector('.status-dot.assesment');

        if (dotKoding) {
            dotKoding.classList.toggle('on', selesaiKoding);
            dotKoding.title = selesaiKoding ? 'Selesai koding' : 'Belum dikoding';
        }
        if (dotAssesment) {
            dotAssesment.classList.toggle('on', selesaiAssesment);
            dotAssesment.title = selesaiAssesment
                ? 'Selesai pelayanan (SOAP/Assessment disimpan)'
                : 'SOAP/Assessment belum disimpan';
        }
    });

    rows.sort(function (a, b) {
        if (a.dataset.statusPeriksa !== b.dataset.statusPeriksa) {
            return a.dataset.statusPeriksa === 'periksa' ? -1 : 1;
        }
        return Number(a.dataset.urutAwal) - Number(b.dataset.urutAwal);
    });

    const tbody = rows[0].parentNode;
    rows.forEach(function (row) { tbody.appendChild(row); });

    perbaruiNomor();
}

function perbaruiNomor() {
    document.querySelectorAll('.row-pasien').forEach(function (row, index) {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

function formatTanggal(tanggal) {
    if (!tanggal) return '-';
    const d = new Date(tanggal);
    if (isNaN(d.getTime())) return tanggal;
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function ambilDataBaris(button) {
    const row = button.closest('.row-pasien');
    if (!row) return null;
    try {
        return { row: row, data: JSON.parse(row.getAttribute('data-pasien')) };
    } catch (error) {
        return null;
    }
}

function isiDetail(data) {
    const set = (id, nilai) => {
        document.getElementById(id).textContent =
            (nilai === null || nilai === undefined || nilai === '') ? '-' : nilai;
    };

    set('m-no-rm', data.no_rm);
    set('m-hp', data.no_hp);
    set('m-nama', data.nama_pasien);
    set('m-suku', data.suku);
    set('m-nik', data.nik);
    set('m-bangsa', data.bangsa);
    set('m-ttl', (data.tempat_lahir ?? '-') + ' / ' + formatTanggal(data.tgl_lahir));
    set('m-bahasa', data.bahasa);
    set('m-umur', data.umur);
    set('m-alamat', data.alamat);
    set('m-jk', data.jenis_kelamin_label ?? data.jenis_kelamin);
    set('m-kelurahan', data.kelurahan);
    set('m-agama', data.agama_label ?? data.agama);
    set('m-kecamatan', data.kecamatan);
    set('m-pendidikan', data.pendidikan_label ?? data.pendidikan);
    set('m-kabupaten', data.kabupaten);
    set('m-pekerjaan', data.pekerjaan_label ?? data.pekerjaan);
    set('m-provinsi', data.provinsi);
    set('m-kodepos', data.kode_pos);
    set('m-pembayaran', data.pembayaran);
    set('m-poli', data.poli);
    set('m-dokter', data.dokter);
    set('m-pj-nama', data.pj_nama);
    set('m-pj-hubungan', data.pj_hubungan);
    set('m-pj-jk', data.pj_jenis_kelamin_label ?? data.pj_jenis_kelamin);
    set('m-pj-hp', data.pj_no_hp);
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

document.addEventListener('DOMContentLoaded', function () {
    terapkanStatusPeriksa();

    // Klik baris -> riwayat pasien (klik tombol mata tetap buka popup)
    document.querySelectorAll('.row-pasien').forEach(function (row) {
        row.addEventListener('click', function (event) {
            if (event.target.closest('button')) return;
            window.location.href = this.dataset.url;
        });
    });

    document.getElementById('pasienModal')?.addEventListener('click', function (event) {
        if (event.target === this) closePasienModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') closePasienModal();
    });
});

// Saat dibuka lewat tombol Back, status tetap diperbarui
window.addEventListener('pageshow', terapkanStatusPeriksa);

// Pencarian keyword di sisi klien
document.getElementById('inputKeyword')?.addEventListener('input', function () {
    const keyword = this.value.trim().toLowerCase();
    document.querySelectorAll('.row-pasien').forEach(function (row) {
        const data = JSON.parse(row.getAttribute('data-pasien'));
        const gabungan = ((data.nama_pasien || '') + ' ' + (data.nik || '') + ' ' + (data.no_rm || '')).toLowerCase();
        row.style.display = gabungan.includes(keyword) ? '' : 'none';
    });
});

document.querySelector('.filter-row')?.addEventListener('submit', e => e.preventDefault());
</script>
@endsection