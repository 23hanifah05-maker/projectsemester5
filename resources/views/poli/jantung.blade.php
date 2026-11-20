@extends('layouts.app')

@section('title', 'Poli Jantung - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-stethoscope"></i>
@endsection
@section('header-title', 'Poli Jantung')

@section('extra-css')
    {{-- Pakai CSS yang sama dengan halaman Pendaftaran karena struktur tabelnya sama --}}
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/poli-jantung.css') }}?v={{ @filemtime(public_path('css/poli-jantung.css')) }}">
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai hanya bila controller belum mengirim data pasien.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien) || count($pasien) === 0) {
            $pasien = collect([
                ['no_rm' => 'E000031', 'nama_pasien' => 'Hendra Wijaya',   'nik' => '3501071504700001', 'tgl_lahir' => '1970-04-15', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Gajah Mada No. 7',    'pembayaran' => 'BPJS',            'status_periksa' => 'selesai'],
                ['no_rm' => 'E000032', 'nama_pasien' => 'Lestari Wulandari', 'nik' => '3504216209680002', 'tgl_lahir' => '1968-09-22', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Cempaka No. 19',     'pembayaran' => 'umum',            'status_periksa' => 'selesai'],
                ['no_rm' => 'E000033', 'nama_pasien' => 'Bambang Hartono', 'nik' => '3509100211750003', 'tgl_lahir' => '1975-11-02', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Veteran No. 33',     'pembayaran' => 'asuransi swasta', 'status_periksa' => 'periksa'],
                ['no_rm' => 'E000034', 'nama_pasien' => 'Ratna Kusuma',    'nik' => '3512285307820004', 'tgl_lahir' => '1982-07-13', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Anggrek No. 4',      'pembayaran' => 'BPJS',            'status_periksa' => 'periksa'],
                ['no_rm' => 'E000035', 'nama_pasien' => 'Yusuf Ramadhan',  'nik' => '3518140806600005', 'tgl_lahir' => '1960-06-08', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Kartini No. 26',     'pembayaran' => 'umum',            'status_periksa' => 'periksa'],
                ['no_rm' => 'E000036', 'nama_pasien' => 'Mega Puspitasari', 'nik' => '3523196412900006', 'tgl_lahir' => '1990-12-24', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Imam Bonjol No. 11', 'pembayaran' => 'BPJS',            'status_periksa' => 'periksa'],
            ])->map(fn ($p, $i) => (object) array_merge([
                'tempat_lahir' => 'Ngawi', 'agama' => 'Islam', 'pendidikan' => 'SMA', 'pekerjaan' => 'Swasta',
                'no_hp' => '08123456' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'suku' => 'Jawa', 'bangsa' => 'Indonesia', 'bahasa' => 'Indonesia',
                'kelurahan' => 'Ngawi', 'kecamatan' => 'Ngawi', 'kabupaten' => 'Ngawi',
                'provinsi' => 'Jawa Timur', 'kode_pos' => '63211',
                'poli' => 'Poli Jantung', 'dokter' => 'dr. Bagus Fitriadi, Sp.JP',
                'pj_nama' => 'Andi Pratama', 'pj_hubungan' => 'Keluarga', 'pj_jenis_kelamin' => 'Laki-laki',
                'pj_no_hp' => '08523451289',
                'nik_lengkap' => $p['nik'],
            ], $p));

            // Pencarian sederhana untuk data dummy (nama, NIK, atau No. RM)
            if (request('keyword')) {
                $kw = strtolower(request('keyword'));
                $pasien = $pasien->filter(fn ($p) =>
                    str_contains(strtolower($p->nama_pasien), $kw) ||
                    str_contains(strtolower($p->nik), $kw) ||
                    str_contains(strtolower($p->no_rm), $kw)
                )->values();
            }
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
                    <th>Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pasien as $index => $item)
                    @php
                        $sp = $item->status_periksa ?? 'periksa';
                        $urlRiwayat = url('/poli/jantung/detail/' . $item->no_rm);
                        $nikTampil = \App\Helpers\Mask::nik($item->nik, 4, 'X');
                        $umurTampil = $item->umur
                            ?? (!empty($item->tgl_lahir) ? \Carbon\Carbon::parse($item->tgl_lahir)->age . ' Tahun' : '-');

                        // Data untuk popup detail pasien (kolom yang tidak ada tampil "-")
                        $detail = [
                            'no_rm' => $item->no_rm,
                            'nama_pasien' => $item->nama_pasien,
                            'nik' => $item->nik_lengkap ?? $nikTampil,
                            'ttl' => ($item->tempat_lahir ?? '-') . ' / ' . (!empty($item->tgl_lahir) ? \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') : '-'),
                            'umur' => $umurTampil,
                            'jenis_kelamin' => $item->jenis_kelamin ?? '-',
                            'agama' => $item->agama ?? '-',
                            'pendidikan' => $item->pendidikan ?? '-',
                            'pekerjaan' => $item->pekerjaan ?? '-',
                            'no_hp' => $item->no_hp ?? '-',
                            'suku' => $item->suku ?? '-',
                            'bangsa' => $item->bangsa ?? '-',
                            'bahasa' => $item->bahasa ?? '-',
                            'alamat' => $item->alamat ?? '-',
                            'kelurahan' => $item->kelurahan ?? '-',
                            'kecamatan' => $item->kecamatan ?? '-',
                            'kabupaten' => $item->kabupaten ?? '-',
                            'provinsi' => $item->provinsi ?? '-',
                            'kode_pos' => $item->kode_pos ?? '-',
                            'pembayaran' => $item->pembayaran ?? '-',
                            'poli' => $item->poli ?? 'Poli Jantung',
                            'dokter' => $item->dokter ?: '-',
                            'pj_nama' => $item->pj_nama ?? '-',
                            'pj_jenis_kelamin' => $item->pj_jenis_kelamin ?? '-',
                            'pj_hubungan' => $item->pj_hubungan ?? '-',
                            'pj_no_hp' => $item->pj_no_hp ?? '-',
                        ];
                    @endphp

                    <tr data-url="{{ $urlRiwayat }}" data-detail='@json($detail)' tabindex="0">
                        <td class="klik-riwayat">{{ $index + 1 }}</td>
                        <td class="klik-riwayat">{{ $item->no_rm }}</td>

                        {{-- Dari No sampai Pembayaran: klik di sel mana pun untuk membuka riwayat pasien --}}
                        <td class="klik-riwayat">{{ $item->nama_pasien }}</td>
                        <td class="klik-riwayat">{{ $nikTampil }}</td>
                        <td class="klik-riwayat">{{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}</td>
                        <td class="klik-riwayat">{{ $item->jenis_kelamin ?? '-' }}</td>
                        <td class="klik-riwayat">{{ $item->alamat }}</td>
                        <td class="klik-riwayat">{{ $item->pembayaran }}</td>

                        <td class="aksi-cell">
                            <div class="aksi-wrap">
                                <button type="button" class="btn-aksi" title="Lihat Detail Pasien" onclick="bukaDetailPasien(this)">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>

                                {{-- Kuning "Periksa" selama belum diperiksa, hijau "Selesai" setelah selesai --}}
                                <span class="badge-periksa {{ $sp }}">
                                    {{ $sp === 'selesai' ? 'Selesai' : 'Periksa' }}
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">Data pasien tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    {{-- ===== POPUP DETAIL PASIEN ===== --}}
    @php
        $kolomKiri = [
            'no_rm' => 'No. Rekam Medis', 'nama_pasien' => 'Nama Lengkap', 'nik' => 'NIK',
            'ttl' => 'Tempat/Tgl Lahir', 'umur' => 'Umur', 'jenis_kelamin' => 'Jenis Kelamin',
            'agama' => 'Agama', 'pendidikan' => 'Pendidikan', 'pekerjaan' => 'Pekerjaan',
            'poli' => 'Poli', 'pembayaran' => 'Pembayaran',
        ];
        $kolomKanan = [
            'no_hp' => 'No. HP', 'suku' => 'Suku', 'bangsa' => 'Bangsa', 'bahasa' => 'Bahasa',
            'alamat' => 'Alamat', 'kelurahan' => 'Kelurahan', 'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kota/Kabupaten', 'kode_pos' => 'Kode Pos', 'provinsi' => 'Provinsi',
            'dokter' => 'Dokter',
        ];
        $kolomPj = [
            'pj_nama' => 'Nama', 'pj_jenis_kelamin' => 'Jenis Kelamin',
            'pj_hubungan' => 'Hubungan', 'pj_no_hp' => 'No HP',
        ];
    @endphp

    <div class="dp-overlay" id="dpOverlay" role="dialog" aria-modal="true" aria-labelledby="dpJudul">
        <div class="dp-modal">
            <div class="dp-head">
                <span id="dpJudul">Data Pasien</span>
                <button type="button" class="dp-tutup" onclick="tutupDetailPasien()" aria-label="Tutup">&times;</button>
            </div>

            <div class="dp-body">
                <div class="dp-section">Identitas Pasien</div>
                <div class="dp-grid">
                    <div>
                        @foreach ($kolomKiri as $kunci => $label)
                            <div class="dp-row"><span class="dp-label">{{ $label }}</span><span class="dp-titik">:</span><span class="dp-nilai" data-f="{{ $kunci }}">-</span></div>
                        @endforeach
                    </div>
                    <div>
                        @foreach ($kolomKanan as $kunci => $label)
                            <div class="dp-row"><span class="dp-label">{{ $label }}</span><span class="dp-titik">:</span><span class="dp-nilai" data-f="{{ $kunci }}">-</span></div>
                        @endforeach
                    </div>
                </div>

                <div class="dp-section">Penanggung Jawab</div>
                <div class="dp-grid">
                    @foreach (array_chunk($kolomPj, 2, true) as $kolom)
                        <div>
                            @foreach ($kolom as $kunci => $label)
                                <div class="dp-row"><span class="dp-label">{{ $label }}</span><span class="dp-titik">:</span><span class="dp-nilai" data-f="{{ $kunci }}">-</span></div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // Klik sel Nama sampai Pembayaran -> buka riwayat pasien
        document.querySelectorAll('td.klik-riwayat').forEach(function (td) {
            td.addEventListener('click', function (e) {
                window.location.href = td.closest('tr').dataset.url;
            });
        });

        document.querySelectorAll('tbody tr[data-url]').forEach(function (tr) {
            tr.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && e.target === tr) window.location.href = tr.dataset.url;
            });
        });

        // Klik ikon mata -> tampilkan detail pasien
        function bukaDetailPasien(tombol) {
            const data = JSON.parse(tombol.closest('tr').dataset.detail || '{}');

            document.querySelectorAll('#dpOverlay [data-f]').forEach(function (el) {
                const nilai = data[el.dataset.f];
                el.textContent = (nilai === null || nilai === undefined || nilai === '') ? '-' : nilai;
            });

            document.getElementById('dpOverlay').classList.add('aktif');
            document.body.style.overflow = 'hidden';
        }

        function tutupDetailPasien() {
            document.getElementById('dpOverlay').classList.remove('aktif');
            document.body.style.overflow = '';
        }

        document.getElementById('dpOverlay').addEventListener('click', function (e) {
            if (e.target === this) tutupDetailPasien();
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') tutupDetailPasien();
        });
    </script>

@endsection