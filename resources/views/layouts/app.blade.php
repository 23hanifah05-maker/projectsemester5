<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik Utama Merah Putih')</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS eksternal: layout + sidebar dipakai di SEMUA halaman --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- CSS tambahan khusus per halaman (dashboard.css, pendaftaran.css, dst) --}}
    @yield('extra-css')
</head>
<body>

    {{-- Sidebar sekarang file terpisah, dipanggil lewat @include.
         Karena semua halaman (dashboard, pendaftaran, poli, radiologi)
         extends layout ini, sidebar-nya SELALU sama & tidak reset. --}}
    @include('partials.sidebar')

    <main class="main">

        <header class="header">
            <div class="header-title">
                <span class="home-icon">@yield('header-icon', '🏠')</span>
                @yield('header-title', 'Dashboard')
            </div>

            <div class="admin">
                <div class="admin-icon">👤</div>
                <span>{{ session('username', 'Admin') }}</span>
            </div>
        </header>

        <section class="content">
            @yield('content')
        </section>

    </main>

    @yield('extra-js')
</body>
</html>