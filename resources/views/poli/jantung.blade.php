@extends('layouts.app')

@section('title', 'Poli Jantung - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-stethoscope"></i>
@endsection
@section('header-title', 'Poli Jantung')

@section('extra-css')
    {{-- Pakai CSS yang sama dengan halaman Pendaftaran karena struktur tabelnya sama --}}
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">

    <style>
        /* ===== Aksi: tombol mata + penanda status (sama seperti Poli Obgyn) ===== */
        .aksi-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-aksi svg {
            width: 20px;
            height: 20px;
            stroke: #fff;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            vertical-align: middle;
        }

        .badge-periksa {
            display: inline-block;
            min-width: 70px;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            color: #fff;
        }

        .badge-periksa.periksa { background-color: #f4b400; color: #333; } /* kuning = periksa */
        .badge-periksa.selesai { background-color: #2e9e4f; }              /* hijau = selesai */

        .table-pendaftaran th:last-child { min-width: 170px; }
    </style>
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
            ])->map(fn ($p) => (object) $p);

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
                    @php $sp = $item->status_periksa ?? 'periksa'; @endphp

                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->no_rm }}</td>
                        <td>{{ $item->nama_pasien }}</td>

                        {{-- NIK disamarkan: 4 digit awal tampil, sisanya X --}}
                        <td>{{ \App\Helpers\Mask::nik($item->nik, 4, 'X') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $item->jenis_kelamin ?? '-' }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->pembayaran }}</td>
                        <td class="aksi-cell">
                            <div class="aksi-wrap">
                                <button type="button" class="btn-aksi" title="Lihat Detail">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>

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

@endsection