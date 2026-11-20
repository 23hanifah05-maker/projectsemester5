<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik Utama Merah Putih')</title>

    {{-- FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- FONT AWESOME --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    {{-- CSS UTAMA --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('extra-css')
    <link rel="stylesheet" href="{{ asset('css/logout-popup.css') }}">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F4F7F6;
            transition: all 0.3s ease-in-out;
            font-family: 'Poppins', sans-serif;
        }

        .main {
            margin-left: 230px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .main {
            margin-left: 0;
        }

        /* HEADER PUTIH */
        .header {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #FFFFFF;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* Tombol toggle sidebar */
        .header-toggle-btn {
            background: transparent;
            border: none;
            color: #d12027;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .header-toggle-btn:hover {
            color: #941b1e;
        }

        /* Judul halaman dinamis */
        .header-page-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-page-title i,
        .header-page-title svg {
            font-size: 1.6rem;
            color: #d12027;
        }

        .header-page-title h1 {
            margin: 0;
            font-size: 1.6rem;
            color: #d12027;
            font-weight: 700;
        }

        /* Info user (ikon + nama + role) */
        .admin {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #333;
        }

        .admin-icon {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            font-size: 1.2rem;
        }

        .admin-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .admin-nama {
            font-weight: 600;
            font-size: 0.95rem;
        }

        .admin-role {
            font-weight: 400;
            font-size: 0.75rem;
            color: #777;
        }

        /* TOMBOL KEMBALI
           Berbentuk kotak putih berbingkai, senada dengan panel di bawahnya. */
        .btn-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 36px;
            margin: -6px 0 12px;
            padding: 0;
            background: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            color: #d90000;
            font-size: 16px;
            line-height: 1;
            text-decoration: none;
            cursor: pointer;
            position: relative;
            z-index: 5;
            transition: background 0.15s, border-color 0.15s, color 0.15s, box-shadow 0.15s;
        }

        .btn-back:hover {
            background: #d90000;
            border-color: #d90000;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(217, 0, 0, 0.25);
        }

        .btn-back:active {
            background: #a93226;
            border-color: #a93226;
        }

        .btn-back:focus-visible {
            outline: 2px solid #d90000;
            outline-offset: 2px;
        }
    </style>
</head>
<body>

    @include('partials.sidebar')

    @php
        // Daftar role: label, ikon Font Awesome, dan warna latar lingkaran
        $daftarRole = [
            'admin'     => ['label' => 'Admin',     'ikon' => 'fa-user-shield',  'warna' => '#d12027'],
            'pmik'      => ['label' => 'PMIK',      'ikon' => 'fa-file-medical', 'warna' => '#7b3fa0'],
            'perawat'   => ['label' => 'Perawat',   'ikon' => 'fa-user-nurse',   'warna' => '#e0568a'],
            'dokter'    => ['label' => 'Dokter',    'ikon' => 'fa-user-doctor',  'warna' => '#1f7ae0'],
            'radiologi' => ['label' => 'Radiologi', 'ikon' => 'fa-x-ray',        'warna' => '#2a9d6f'],
        ];

        $roleUser = strtolower(session('role', Auth::user()?->role ?? 'admin'));
        $infoRole = $daftarRole[$roleUser] ?? [
            'label' => ucfirst($roleUser),
            'ikon'  => 'fa-user',
            'warna' => '#d12027',
        ];
        $namaUser = session('username', Auth::user()?->username ?? 'Admin');

        // Tujuan cadangan tombol kembali (kalau tidak ada riwayat halaman sebelumnya)
        $urlKembali = \Illuminate\Support\Facades\Route::has('dashboard')
            ? route('dashboard')
            : url('/');

        // Tombol kembali tidak ditampilkan di Dashboard
        $tampilkanKembali = !request()->routeIs('dashboard');
    @endphp

    <main class="main">
        <header class="header">
            <div class="header-left">
                {{-- Tombol buka/tutup sidebar --}}
                <button type="button" class="header-toggle-btn" onclick="toggleSidebar()" title="Buka/Tutup Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>

                {{-- Judul halaman dinamis --}}
                <div class="header-page-title">
                    @yield('header-icon')
                    <h1>@yield('header-title', 'Dashboard')</h1>
                </div>
            </div>

            {{-- Info user sesuai role --}}
            <div class="admin">
                <div class="admin-icon" style="background: {{ $infoRole['warna'] }};">
                    <i class="fa-solid {{ $infoRole['ikon'] }}"></i>
                </div>
                <div class="admin-info">
                    <span class="admin-nama">{{ $namaUser }}</span>
                    <span class="admin-role">{{ $infoRole['label'] }}</span>
                </div>
            </div>
        </header>

        <section class="content" style="padding: 30px;">

            {{-- TOMBOL KEMBALI (otomatis di semua halaman kecuali Dashboard).
                 Untuk menyembunyikan di halaman tertentu, tambahkan di blade halaman itu:
                 @section('hide-back')@endsection --}}
            @if ($tampilkanKembali && !View::hasSection('hide-back'))
                <a href="{{ $urlKembali }}"
                   id="btnBack"
                   class="btn-back"
                   title="Kembali"
                   aria-label="Kembali">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            @endif

            @yield('content')
        </section>
    </main>

    <script>
        /* Tombol kembali:
           - Ada riwayat halaman dari situs ini  -> kembali satu halaman.
           - Tidak ada (dibuka langsung / tab baru) -> ke Dashboard (href cadangan). */
        (function () {
            const tombol = document.getElementById('btnBack');
            if (!tombol) return;

            tombol.addEventListener('click', function (e) {
                let dariSitusIni = false;

                try {
                    dariSitusIni = document.referrer !== '' &&
                        new URL(document.referrer).origin === window.location.origin;
                } catch (err) {
                    dariSitusIni = false;
                }

                if (dariSitusIni && window.history.length > 1) {
                    e.preventDefault();
                    window.history.back();
                }
                // Selain itu biarkan link berjalan normal ke halaman cadangan.
            });
        })();
    </script>

    @yield('extra-js')
</body>
</html>