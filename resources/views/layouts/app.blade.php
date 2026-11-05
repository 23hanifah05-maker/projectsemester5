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
    @endphp

    <main class="main">
        <header class="header">
            <div class="header-left">
                {{-- Tombol buka/tutup sidebar --}}
                <button class="header-toggle-btn" onclick="toggleSidebar()" title="Buka/Tutup Sidebar">
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
            @yield('content')
        </section>
    </main>

    @yield('extra-js')
</body>
</html>