@extends('layouts.app')

@section('title', 'Kunjungan Poli Syaraf - Klinik Utama Merah Putih')
@section('header-icon', '🩺')
@section('header-title', 'Poli Syaraf')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
    <style>
        .kj-card {
            border: 1px solid #d9d9d9;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            padding: 18px 20px 20px;
        }
        .kj-section-title, .kj-riwayat-title {
            color: #b81d24;
            font-weight: 700;
            font-size: 15px;
            padding: 0 18px 12px;
            margin: 0 -18px 14px;
            border-bottom: 1px solid #999;
        }
        .kj-datapasien-box, .kj-riwayat-box {
            border: 1px solid #999;
            border-radius: 6px;
            padding: 14px 18px 18px;
        }
        .kj-datapasien-box { margin-bottom: 22px; }
        .kj-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 32px;
        }
        .kj-field { display: flex; align-items: center; gap: 12px; }
        .kj-field .kj-label {
            flex: 0 0 auto; min-width: 110px;
            font-size: 13px; font-weight: 700; color: #333; white-space: nowrap;
        }
        .kj-field .kj-value {
            flex: 1 1 auto; border: 1px solid #ccc; border-radius: 4px;
            padding: 6px 12px; font-size: 13px; color: #333; background: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0;
        }
        .kj-table {
            width: 100%; border-collapse: collapse; table-layout: fixed; border: 1px solid #999;
        }
        .kj-table th:nth-child(1), .kj-table td:nth-child(1) { width: 60px; text-align: center; }
        .kj-table th:nth-child(2), .kj-table td:nth-child(2) { width: 180px; }
        .kj-table th:nth-child(3), .kj-table td:nth-child(3) { width: auto; }
        .kj-table thead th {
            background: #b81d24; color: #fff; font-weight: 700; text-align: left;
            padding: 8px 16px; font-size: 14px; border-right: 1px solid #d64b52; border-bottom: 1px solid #999;
        }
        .kj-table thead th:first-child { text-align: center; }
        .kj-table thead th:last-child { border-right: none; }
        .kj-table tbody td {
            padding: 6px 16px; border-bottom: 1px solid #999; border-right: 1px solid #999;
            font-size: 14px; color: #333;
        }
        .kj-table tbody td:last-child { border-right: none; }
        .kj-table tbody tr:last-child td { border-bottom: none; }
        .kj-table tbody td:first-child { text-align: center; }

        .kj-link {
            color: #b81d24;
            font-weight: 600;
            text-decoration: none;
        }
        .kj-link:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')

    {{-- Data dummy pasien Poli Syaraf --}}
    @php
        $semuaPasien = $semuaPasien ?? [
            (object)['no_rm'=>'RM-0001','nama_pasien'=>'Budi Santoso','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1980-01-01','umur'=>'46 Tahun','no_hp'=>'081234567890'],
            (object)['no_rm'=>'RM-0003','nama_pasien'=>'Andi Pratama','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Malang','tgl_lahir'=>'1990-03-15','umur'=>'36 Tahun','no_hp'=>'082112223333'],
            (object)['no_rm'=>'RM-0005','nama_pasien'=>'Rudi Hartono','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Mojokerto','tgl_lahir'=>'1975-06-12','umur'=>'51 Tahun','no_hp'=>'081377889900'],
            (object)['no_rm'=>'RM-0007','nama_pasien'=>'Fajar Ramadhan','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Sidoarjo','tgl_lahir'=>'1998-01-18','umur'=>'28 Tahun','no_hp'=>'083811223344'],
            (object)['no_rm'=>'RM-0009','nama_pasien'=>'Agus Setiawan','jenis_kelamin'=>'Laki-laki','tempat_lahir'=>'Surabaya','tgl_lahir'=>'1970-06-22','umur'=>'56 Tahun','no_hp'=>'081245678901'],
        ];

        $noRmAktif = $no_rm ?? request('no_rm');
        $pasien = collect($semuaPasien)->firstWhere('no_rm', $noRmAktif);

        $riwayatPerPasien = [
            'RM-0001' => [
                ['tanggal' => '2026-09-12', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-06-03', 'poli' => 'Poli Jantung'],
                ['tanggal' => '2026-02-18', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2025-10-25', 'poli' => 'Poli Radiologi'],
            ],
            'RM-0003' => [
                ['tanggal' => '2026-08-20', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-03-11', 'poli' => 'Poli Jiwa'],
            ],
            'RM-0005' => [
                ['tanggal' => '2026-09-01', 'poli' => 'Poli Jantung'],
                ['tanggal' => '2026-07-15', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-04-02', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2025-12-09', 'poli' => 'Poli Radiologi'],
                ['tanggal' => '2025-08-22', 'poli' => 'Poli Jantung'],
            ],
            'RM-0007' => [
                ['tanggal' => '2026-05-30', 'poli' => 'Poli Saraf'],
            ],
            'RM-0009' => [
                ['tanggal' => '2026-09-05', 'poli' => 'Poli Jantung'],
                ['tanggal' => '2026-06-27', 'poli' => 'Poli Saraf'],
                ['tanggal' => '2026-01-14', 'poli' => 'Poli Jantung'],
            ],
        ];

        $riwayat = $riwayatPerPasien[$noRmAktif] ?? [];
    @endphp

    <div class="kj-card">

        <!-- KOTAK DATA PASIEN -->
        <div class="kj-datapasien-box">
            <div class="kj-section-title">Data Pasien</div>

            @if ($pasien)
                <div class="kj-fields">
                    <div class="kj-field">
                        <span class="kj-label">No RM</span>
                        <span class="kj-value">{{ $pasien->no_rm }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Tempat, Tgl Lahir</span>
                        <span class="kj-value">{{ $pasien->tempat_lahir }}, {{ \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Nama</span>
                        <span class="kj-value">{{ $pasien->nama_pasien }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Umur</span>
                        <span class="kj-value">{{ $pasien->umur }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">Jenis Kelamin</span>
                        <span class="kj-value">{{ $pasien->jenis_kelamin }}</span>
                    </div>
                    <div class="kj-field">
                        <span class="kj-label">No Hp</span>
                        <span class="kj-value">{{ $pasien->no_hp }}</span>
                    </div>
                </div>
            @else
                <p style="color:#888;">Data pasien tidak ditemukan.</p>
            @endif
        </div>

        <!-- TOMBOL TAB: ASSESMENT & CLINICAL PATHWAY -->
        <div class="d-flex gap-2 mb-4">
            <button type="button" id="btn-assessment" class="btn btn-danger btn-sm px-3 py-2 rounded-2" onclick="showAssessment()">
                <i class="fa-solid fa-pen-to-square me-1"></i> Assesment
            </button>
            <button type="button" id="btn-pathway" class="btn btn-secondary btn-sm px-4 py-2 rounded-2" onclick="showPathway()">
                <i class="fa-solid fa-sitemap me-1"></i> Clinical Pathway
            </button>
        </div>

        <!-- KONTEN 1: TABEL RIWAYAT KUNJUNGAN (Bagian Assessment) -->
        <div id="content-assessment">
            <div class="kj-riwayat-box">
                <div class="kj-riwayat-title">Riwayat Kunjungan</div>

                <table class="kj-table">
                    <thead>
                        <tr><th>No</th><th>Tgl. Kunjungan</th><th>Riwayat Kunjungan</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayat as $i => $item)
                            @php
                                $urlDetail = route('poli.syaraf.kunjungan.detail', [
                                    'no_rm'   => $noRmAktif,
                                    'tanggal' => $item['tanggal'],
                                ]);
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <a href="{{ $urlDetail }}" class="kj-link">
                                        {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y') }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ $urlDetail }}" class="kj-link">
                                        {{ $item['poli'] }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" style="text-align:center; color:#888;">Belum ada riwayat kunjungan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- KONTEN 2: TABEL CLINICAL PATHWAY (Disembunyikan awalnya, muncul saat tombol Clinical Pathway diklik) -->
        <div id="content-pathway" style="display: none;">
            <div class="card shadow-sm border-danger mb-5 rounded-3 overflow-hidden">
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered mb-0 align-middle">
                        <thead class="text-white text-center" style="background-color: #a31515;">
                            <tr>
                                <th style="width: 25%;">Aktivitas Pelayanan</th>
                                <th style="width: 45%;">Keterangan</th>
                                <th style="width: 15%;">Waktu</th>
                                <th style="width: 15%;">Tarif</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Diagnosa -->
                            <tr>
                                <td class="fw-bold bg-light">
                                    Diagnosa
                                    <div class="fw-normal small ms-2 text-muted">
                                        <div class="my-1">Dx Utama</div>
                                        <div class="my-1">Dx Sekunder</div>
                                        <div class="my-1">Dx Banding</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>

                            <!-- Asesmen Klinis -->
                            <tr>
                                <td class="fw-bold bg-light">Asesmen Klinis</td>
                                <td><textarea class="form-control form-control-sm" rows="2"></textarea></td>
                                <td>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                </td>
                                <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                            </tr>

                            <!-- Pemeriksaan Fisik -->
                            <tr>
                                <td class="fw-bold bg-light">Pemeriksaan Fisik</td>
                                <td>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Pemeriksaan tanda vital</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Inspeksi postur tulang belakang dan gerakan aktif volumna vertebralis</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Pemeriksaan motorik, reflek, dan sensorik dermatom</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                                </td>
                                <td>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                </td>
                                <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                            </tr>

                            <!-- Pemeriksaan Penunjang -->
                            <tr>
                                <td class="fw-bold bg-light">Pemeriksaan Penunjang</td>
                                <td>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Magnetic Resonance Imaging (MRI)</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Computerized Tomography (CT Scan)</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Foto polos lumbosakral (rontgen / X-ray)</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                                </td>
                                <td>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                </td>
                                <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                            </tr>

                            <!-- Farmakologis -->
                            <tr>
                                <td class="fw-bold bg-light">Farmakologis</td>
                                <td>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Antipiretik</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Analgesik Adjuvan</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> NSAID oral</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Muscle Relaxant</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Cairan IV kristaloid</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                                </td>
                                <td>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                </td>
                                <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                            </tr>

                            <!-- Fisioterapi -->
                            <tr>
                                <td class="fw-bold bg-light">Fisioterapi</td>
                                <td>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Terapi lampu hangat (Infra Red)</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Stimulasi Listrik (TENS)</label></div>
                                </td>
                                <td>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                </td>
                                <td><span class="text-muted small">Rp</span> <input type="text" class="form-control form-control-sm d-inline-block w-75"></td>
                            </tr>

                            <!-- Edukasi -->
                            <tr>
                                <td class="fw-bold bg-light">Edukasi</td>
                                <td>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi menjaga postur tubuh yang benar saat duduk dan berdiri</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi olahraga yang menguatkan tulang belakang</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi angkat beban berat</label></div>
                                    <div class="form-check mb-1"><input class="form-check-input" type="checkbox"><label class="form-check-small"> Edukasi menjaga berat badan ideal</label></div>
                                    <div class="form-check"><input class="form-check-input" type="checkbox"><label class="form-check-small"> ........</label></div>
                                </td>
                                <td>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                    <div class="my-1"><input type="text" class="form-control form-control-sm"></div>
                                </td>
                                <td></td>
                            </tr>

                            <!-- Variasi Pelayanan -->
                            <tr>
                                <td class="fw-bold bg-light">Variasi Pelayanan</td>
                                <td><textarea class="form-control form-control-sm" rows="2"></textarea></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <!-- Total -->
                            <tr>
                                <td class="fw-bold bg-light">Total</td>
                                <td></td>
                                <td></td>
                                <td class="fw-bold">
                                    <span class="text-muted small">Rp</span> 
                                    <input type="text" class="form-control form-control-sm d-inline-block w-75 fw-bold bg-light" readonly>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol Cetak -->
                <div class="card-footer bg-white text-end py-3">
                    <button type="button" class="btn btn-danger px-4 fw-bold shadow-sm">
                        <i class="fa-solid fa-print me-1"></i> Cetak
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- SKRIP JAVASCRIPT UNTUK TOMBOL INTERAKTIF -->
    <script>
        function showAssessment() {
            document.getElementById('content-pathway').style.display = 'none';
            document.getElementById('content-assessment').style.display = 'block';

            document.getElementById('btn-assessment').className = 'btn btn-danger btn-sm px-3 py-2 rounded-2';
            document.getElementById('btn-pathway').className = 'btn btn-secondary btn-sm px-4 py-2 rounded-2';
        }

        function showPathway() {
            document.getElementById('content-assessment').style.display = 'none';
            document.getElementById('content-pathway').style.display = 'block';

            document.getElementById('btn-pathway').className = 'btn btn-danger btn-sm px-4 py-2 rounded-2';
            document.getElementById('btn-assessment').className = 'btn btn-secondary btn-sm px-3 py-2 rounded-2';
        }
    </script>

@endsection