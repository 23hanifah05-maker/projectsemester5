@extends('layouts.app')

@section('title', 'Dashboard - Klinik Utama Merah Putih')
@section('header-icon', '🏠')
@section('header-title', 'Dashboard')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')

    <div class="period">
        <label>Periode</label>
        <input type="date">
        <span>s.d</span>
        <input type="date">
    </div>

    <div class="statistics">

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div>
                <div class="stat-title">Total Pasien</div>
                <div class="stat-number">{{ number_format($totalPasien ?? 1237, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🩺</div>
            <div>
                <div class="stat-title">Total Pasien Poli</div>
                <div class="stat-number">{{ number_format($totalPasienPoli ?? 1237, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div>
                <div class="stat-title">Pendaftaran Hari Ini</div>
                <div class="stat-number">{{ number_format($pendaftaranHariIni ?? 1237, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div>
                <div class="stat-title">Pasien Selesai</div>
                <div class="stat-number">{{ number_format($pasienSelesai ?? 1237, 0, ',', '.') }}</div>
            </div>
        </div>

    </div>

    {{-- 10 BESAR PENYAKIT --}}
    <div class="panel">
        <div class="panel-title">
            <div class="panel-title-left">
                <div class="panel-title-icon">▥</div>
                <span>10 Besar Penyakit</span>
            </div>
            <small>Jumlah Kasus</small>
        </div>

        @php
            // Data dummy dipakai kalau controller belum mengirim $penyakit dari DB.
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
            $maksimal = collect($penyakit)->max('jumlah');
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

    {{-- KUNJUNGAN PER POLI --}}
    <div class="panel">
        <div class="panel-title">
            <div class="panel-title-left">
                <div class="panel-title-icon">▥</div>
                <span>Kunjungan Per Poli</span>
            </div>
            <small>Jumlah Kunjungan</small>
        </div>

        @php
            $kunjungan = $kunjungan ?? [186, 142, 98, 76, 64, 58, 52, 43, 37, 32];
            $namaPoli = $namaPoli ?? ['Umum', 'Anak', 'Gigi', 'Dalam', 'Mata', 'Bedah', 'THT', 'Kulit', 'Saraf', 'Lainnya'];
            $maxKunjungan = max($kunjungan);
        @endphp

        <div class="chart">
            @foreach ($kunjungan as $index => $jumlah)
                <div class="chart-item">
                    <div class="chart-value">{{ $jumlah }}</div>
                    <div class="chart-bar" style="height: {{ ($jumlah / $maxKunjungan) * 110 }}px;"></div>
                    <div class="chart-label">{{ $namaPoli[$index] }}</div>
                </div>
            @endforeach
        </div>
    </div>

@endsection