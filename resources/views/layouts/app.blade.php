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
            gap: 15px; /* Jarak antara toggle dan judul */
        }

        /* Tombol Toggle di Header */
        .header-toggle-btn {
            background: transparent;
            border: none;
            color: #d12027; /* Warna merah */
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
        
        /* Area Judul Halaman Dinamis */
        .header-page-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Ikon Header (Warna Merah) */
        .header-page-title i, .header-page-title svg {
            font-size: 1.6rem;
            color: #d12027;
        }

        /* Teks Judul Halaman (Merah & Tebal) */
        .header-page-title h1 {
            margin: 0;
            font-size: 1.6rem;
            color: #d12027;
            font-weight: 700;
        }

        .admin {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            color: #333;
            font-size: 1rem;
        }
        
        .admin-icon {
            background: #d12027;
            color: white;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

    @include('partials.sidebar')

    <main class="main">
        <header class="header">
            <div class="header-left">
                <!-- Tombol Buka Tutup Sidebar di Samping Kiri -->
                <button class="header-toggle-btn" onclick="toggleSidebar()" title="Buka/Tutup Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                
                <!-- Judul Halaman Dinamis -->
                <div class="header-page-title">
                    @hasSection('header-icon')
                        @yield('header-icon')
                    @else
                        <!-- Default Ikon Rumah -->
                        <i class="fa-solid fa-house"></i>
                    @endif
                    
                    <h1>@yield('header-title', 'Dashboard')</h1>
                </div>
            </div>

            <div class="admin">
                <!-- Ikon Profil lalu Teks Admin -->
                <div class="admin-icon">
                    <i class="fa-solid fa-user"></i>
                </div>
                <span>{{ session('username', 'Admin') }}</span>
            </div>
        </header>

        <section class="content" style="padding: 30px;">
            @yield('content')
        </section>
    </main>

    @yield('extra-js')
</body>
</html>