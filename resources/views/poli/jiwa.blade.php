@extends('layouts.app')

@section('title', 'Poli Jiwa - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-brain"></i>
@endsection
@section('header-title', 'Poli Jiwa')

@section('extra-css')
    {{-- Pakai CSS yang sama dengan halaman Pendaftaran karena struktur tabelnya sama --}}
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/poli-jiwa.css') }}?v={{ @filemtime(public_path('css/poli-jiwa.css')) }}">
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai hanya bila controller belum mengirim data pasien.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien) || count($pasien) === 0) {
            $pasien = collect([
                ['no_rm' => 'E000041', 'nama_pasien' => 'Sri Wahyuni',     'nik' => '3501075803850001', 'tgl_lahir' => '1985-03-18', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Melati No. 5',      'pembayaran' => 'BPJS',            'status_periksa' => 'selesai'],
                ['no_rm' => 'E000042', 'nama_pasien' => 'Agus Setiawan',   'nik' => '3504200908920002', 'tgl_lahir' => '1992-08-09', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Diponegoro No. 21', 'pembayaran' => 'umum',            'status_periksa' => 'selesai'],
                ['no_rm' => 'E000043', 'nama_pasien' => 'Dewi Anggraini', 'nik' => '3509106701980003', 'tgl_lahir' => '1998-01-27', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Mawar No. 12',       'pembayaran' => 'BPJS',            'status_periksa' => 'periksa'],
                ['no_rm' => 'E000044', 'nama_pasien' => 'Rudi Hermawan',   'nik' => '3512173010790004', 'tgl_lahir' => '1979-10-30', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Sudirman No. 8',     'pembayaran' => 'asuransi swasta', 'status_periksa' => 'periksa'],
                ['no_rm' => 'E000045', 'nama_pasien' => 'Nur Aini',        'nik' => '3518215405010005', 'tgl_lahir' => '2001-05-14', 'jenis_kelamin' => 'Perempuan', 'alamat' => 'Jl. Kenanga No. 3',      'pembayaran' => 'BPJS',            'status_periksa' => 'periksa'],
                ['no_rm' => 'E000046', 'nama_pasien' => 'Fajar Nugroho',   'nik' => '3523180212880006', 'tgl_lahir' => '1988-12-02', 'jenis_kelamin' => 'Laki-laki', 'alamat' => 'Jl. Pahlawan No. 17',    'pembayaran' => 'umum',            'status_periksa' => 'periksa'],
            ])->map(fn ($p, $i) => (object) array_merge([
                'tempat_lahir' => 'Ngawi', 'agama' => 'Islam', 'pendidikan' => 'SMA', 'pekerjaan' => 'Swasta',
                'no_hp' => '08134567' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'suku' => 'Jawa', 'bangsa' => 'Indonesia', 'bahasa' => 'Indonesia',
                'kelurahan' => 'Ngawi', 'kecamatan' => 'Ngawi', 'kabupaten' => 'Ngawi',
                'provinsi' => 'Jawa Timur', 'kode_pos' => '63211',
                'poli' => 'Poli Jiwa', 'dokter' => 'dr. Rina Kartika, Sp.KJ',
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

        <form method="GET" action="{{ route('poli.jiwa') }}" class="filter-row">
            <div class="filter-item">
                <label>Periode</label>
                <input type="date" name="dari" id="tanggalDariJiwa" value="{{ request('dari') }}">
                <span>s.d</span>
                <input type="date" name="sampai" id="tanggalSampaiJiwa" value="{{ request('sampai') }}">
            </div>

            <div class="filter-item filter-keyword">
                <label for="inputKeyword">Keyword</label>
                <input type="text" id="inputKeyword" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama, NIK, atau No. RM">
            </div>
        </form>

        <script>
            (function () {
                const dari = document.getElementById('tanggalDariJiwa');
                const sampai = document.getElementById('tanggalSampaiJiwa');

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
                        $urlRiwayat = url('/poli/jiwa/detail/' . $item->no_rm);
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
                            'poli' => $item->poli ?? 'Poli Jiwa',
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