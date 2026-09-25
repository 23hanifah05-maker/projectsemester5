@extends('layouts.app')

@section('title', 'Pendaftaran - Klinik Utama Merah Putih')
@section('header-icon', '📋')
@section('header-title', 'Pendaftaran')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
@endsection

@section('content')

    <div class="panel">

        <div class="panel-head">
            <a href="#" class="master-data-title">Master Data</a>
            <a href="{{ route('pendaftaran.create') }}" class="btn-input">+ Input</a>
        </div>

        <form method="GET" action="{{ route('pendaftaran.index') }}" class="filter-row">
            <div class="filter-item">
                <label>Periode</label>
                <input type="date" name="dari" value="{{ request('dari') }}">
                <span>s.d</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}">
            </div>

            <div class="filter-item filter-keyword">
                <label>Keyword</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama, NIK, atau No. RM">
            </div>
        </form>

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
                @forelse (($pasien ?? []) as $index => $item)
                    {{-- Double-click di baris ini untuk buka popup Data Pasien --}}
                    <tr class="row-pasien" data-pasien="{{ json_encode($item) }}" title="Klik dua kali untuk lihat detail">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->no_rm }}</td>
                        <td>{{ $item->nama_pasien }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td class="aksi-cell">
                            <span class="btn-aksi" title="Klik 2x baris untuk lihat">✔</span>
                            <a href="{{ route('pendaftaran.tambah', $index) }}" class="btn-aksi" title="Tambah Kunjungan" onclick="event.stopPropagation()">+</a>
                            <a href="{{ route('pendaftaran.edit', $index) }}" class="btn-aksi" title="Edit" onclick="event.stopPropagation()">✎</a>
                            <form action="{{ route('pendaftaran.destroy', $index) }}" method="POST" class="form-delete"
                                  onsubmit="return confirm('Hapus data pasien ini?')" onclick="event.stopPropagation()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-aksi" title="Hapus">🗑</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="aksi-cell">
                                <span class="btn-aksi">✔</span>
                                <span class="btn-aksi">+</span>
                                <span class="btn-aksi">✎</span>
                                <span class="btn-aksi">🗑</span>
                            </td>
                        </tr>
                    @endfor
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- ================= POPUP DATA PASIEN ================= --}}
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

                <div class="modal-row"></div>
                <div class="modal-row"><span class="modal-label">Kode Pos</span><span>:</span><span id="m-kodepos"></span></div>

                <div class="modal-row"></div>
                <div class="modal-row"><span class="modal-label">Pembayaran</span><span>:</span><span id="m-pembayaran"></span></div>
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
    // Double-click di baris (bukan di tombol Aksi) -> buka popup Data Pasien
    document.querySelectorAll('.row-pasien').forEach(function (row) {
        row.addEventListener('dblclick', function () {
            const data = JSON.parse(this.dataset.pasien);
            openPasienModal(data);
        });
    });

    function openPasienModal(data) {
        document.getElementById('m-no-rm').textContent      = data.no_rm ?? '-';
        document.getElementById('m-hp').textContent         = data.no_hp ?? '-';
        document.getElementById('m-nama').textContent       = data.nama_pasien ?? '-';
        document.getElementById('m-suku').textContent       = data.suku ?? '-';
        document.getElementById('m-nik').textContent        = data.nik ?? '-';
        document.getElementById('m-bangsa').textContent     = data.bangsa ?? '-';
        document.getElementById('m-ttl').textContent        = (data.tempat_lahir ?? '-') + ' / ' + formatTanggal(data.tgl_lahir);
        document.getElementById('m-bahasa').textContent     = data.bahasa ?? '-';
        document.getElementById('m-umur').textContent       = data.umur ?? '-';
        document.getElementById('m-alamat').textContent     = data.alamat ?? '-';
        document.getElementById('m-jk').textContent         = data.jenis_kelamin ?? '-';
        document.getElementById('m-kelurahan').textContent  = data.kelurahan ?? '-';
        document.getElementById('m-agama').textContent      = data.agama ?? '-';
        document.getElementById('m-kecamatan').textContent  = data.kecamatan ?? '-';
        document.getElementById('m-pendidikan').textContent = data.pendidikan ?? '-';
        document.getElementById('m-kabupaten').textContent  = data.kabupaten ?? '-';
        document.getElementById('m-pekerjaan').textContent  = data.pekerjaan ?? '-';
        document.getElementById('m-provinsi').textContent   = data.provinsi ?? '-';
        document.getElementById('m-kodepos').textContent    = data.kode_pos ?? '-';
        document.getElementById('m-pembayaran').textContent = data.pembayaran ?? '-';

        document.getElementById('m-pj-nama').textContent     = data.pj_nama ?? '-';
        document.getElementById('m-pj-hubungan').textContent = data.pj_hubungan ?? '-';
        document.getElementById('m-pj-jk').textContent       = data.pj_jenis_kelamin ?? '-';
        document.getElementById('m-pj-hp').textContent       = data.pj_no_hp ?? '-';

        document.getElementById('pasienModal').classList.add('show');
    }

    function closePasienModal() {
        document.getElementById('pasienModal').classList.remove('show');
    }

    function formatTanggal(str) {
        if (!str) return '-';
        const d = new Date(str);
        if (isNaN(d)) return str;
        return d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
    }

    // Klik area gelap di luar kotak popup -> ikut menutup popup
    document.getElementById('pasienModal').addEventListener('click', function (e) {
        if (e.target === this) closePasienModal();
    });
</script>
@endsection