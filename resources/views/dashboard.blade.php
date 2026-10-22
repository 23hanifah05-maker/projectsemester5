@extends('layouts.app')

@section('title', 'Dashboard - Klinik Utama Merah Putih')

@section('header-title', 'Dashboard')
@section('header-icon')
    <i class="fa-solid fa-house icon-main"></i>
@endsection

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')

    {{-- =====================================================
        PERIODE (posisi & tampilan tetap; hanya memfilter 2 grafik)
    ====================================================== --}}
    <form method="GET" action="{{ route('dashboard') }}" class="period">

        <label>Periode</label>

        <input type="date" name="tanggal_awal"
               value="{{ request('tanggal_awal') }}"
               onchange="this.form.submit()">

        <span>s.d</span>

        <input type="date" name="tanggal_akhir"
               value="{{ request('tanggal_akhir') }}"
               onchange="this.form.submit()">

    </form>


    {{-- =====================================================
        STATISTIK (tidak terpengaruh filter periode)
    ====================================================== --}}
    <div class="statistics">

        {{-- TOTAL PASIEN HARI INI --}}
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 4H19V20H5V4Z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                    <path d="M8 2V6" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M16 2V6" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M5 9H19" stroke="white" stroke-width="1.8"/>
                    <path d="M8 13H16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M8 16H13" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <div class="stat-title">Total Pasien Hari Ini</div>
                <div class="stat-number">{{ number_format($totalPasienHariIni ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- TOTAL PASIEN BULAN INI --}}
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="9" cy="8" r="3" stroke="white" stroke-width="1.8"/>
                    <path d="M3.5 19C3.5 16.2386 5.73858 14 8.5 14H9.5C12.2614 14 14.5 16.2386 14.5 19"
                          stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <circle cx="16.5" cy="9" r="2.5" stroke="white" stroke-width="1.8"/>
                    <path d="M15 14.5C15.4643 14.1753 16.0314 14 16.625 14C18.765 14 20.5 15.7349 20.5 17.875"
                          stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
            </div>
            <div>
                <div class="stat-title">Total Pasien Bulan Ini</div>
                <div class="stat-number">{{ number_format($totalPasienBulanIni ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- PASIEN BELUM DILAYANI --}}
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="8.5" stroke="white" stroke-width="1.8"/>
                    <path d="M12 7.5V12L15 14" stroke="white" stroke-width="1.8"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="stat-title">Pasien Belum Dilayani</div>
                <div class="stat-number">{{ number_format($pasienBelumDilayani ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- PASIEN SELESAI --}}
        <div class="stat-card">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 5C4 3.89543 4.89543 3 6 3H18C19.1046 3 20 3.89543 20 5V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 4 20.1046 4 19V5Z"
                          stroke="white" stroke-width="1.8"/>
                    <path d="M8 7H16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M8 10.5H16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M8 14H11" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <circle cx="16.5" cy="16.5" r="3.5" fill="#D71920" stroke="white" stroke-width="1.5"/>
                    <path d="M14.8 16.5L16 17.7L18.3 15.3" stroke="white" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div>
                <div class="stat-title">Pasien Selesai</div>
                <div class="stat-number">{{ number_format($pasienSelesai ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

    </div>


    {{-- =====================================================
        10 BESAR PENYAKIT
    ====================================================== --}}
    <div class="panel">

        <div class="panel-title">
            <div class="panel-title-left">
                <div class="panel-title-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 4H19V20H5V4Z" stroke="white" stroke-width="1.8" stroke-linejoin="round"/>
                        <path d="M8 8H16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M8 12H16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M8 16H13" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </div>
                <span>10 Besar Penyakit</span>
            </div>
            <small>Jumlah Kasus</small>
        </div>

        @php
            $penyakit = $penyakit ?? [
                ['nama' => 'ISPA', 'jumlah' => 186],
                ['nama' => 'Hipertensi', 'jumlah' => 142],
                ['nama' => 'Diabetes Mellitus', 'jumlah' => 98],
                ['nama' => 'Gastritis', 'jumlah' => 76],
                ['nama' => 'Dyspepsia', 'jumlah' => 64],
                ['nama' => 'ISK', 'jumlah' => 58],
                ['nama' => 'Nyeri Pinggang', 'jumlah' => 52],
                ['nama' => 'Dermatitis', 'jumlah' => 43],
                ['nama' => 'Asma', 'jumlah' => 37],
                ['nama' => 'Osteoartritis', 'jumlah' => 32],
            ];

            $maksimal = collect($penyakit)->max('jumlah') ?: 1;
        @endphp

        @foreach ($penyakit as $index => $data)
            <div class="disease-row">
                <div class="disease-number">{{ $index + 1 }}</div>
                <div class="disease-name">{{ $data['nama'] }}</div>
                <div class="bar-bg">
                    <div class="bar" style="width: {{ ($data['jumlah'] / $maksimal) * 100 }}%;"></div>
                </div>
                <div class="disease-value">{{ $data['jumlah'] }}</div>
            </div>
        @endforeach

    </div>


    {{-- =====================================================
        KUNJUNGAN PER POLI
    ====================================================== --}}
    <div class="panel">

        <div class="panel-title">
            <div class="panel-title-left">
                <div class="panel-title-icon">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 20V5" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M5 20H20" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                        <rect x="8" y="13" width="2.5" height="5" rx="1" fill="white"/>
                        <rect x="12" y="10" width="2.5" height="8" rx="1" fill="white"/>
                        <rect x="16" y="7" width="2.5" height="11" rx="1" fill="white"/>
                    </svg>
                </div>
                <span>Kunjungan Per Poli</span>
            </div>
            <small>Jumlah Kunjungan</small>
        </div>

        @php
            // Format: 'Nama Poli' => jumlah. Poli utama (Saraf, Obgyn, Jantung, Jiwa) di depan.
            $kunjunganPoli = $kunjunganPoli ?? [
                'Saraf'   => 186,
                'Obgyn'   => 142,
                'Jantung' => 98,
                'Jiwa'    => 76,
                'Umum'    => 64,
                'Anak'    => 58,
                'Gigi'    => 52,
                'Dalam'   => 43,
                'Bedah'   => 37,
                'THT'     => 32,
            ];

            $maxKunjungan = max($kunjunganPoli) ?: 1;
        @endphp

        <div class="chart">
            @foreach ($kunjunganPoli as $namaPoli => $jumlah)
                <div class="chart-item">
                    <div class="chart-value">{{ $jumlah }}</div>
                    <div class="chart-bar" style="height: {{ ($jumlah / $maxKunjungan) * 110 }}px;"></div>
                    <div class="chart-label">{{ $namaPoli }}</div>
                </div>
            @endforeach
        </div>

    </div>

@endsection