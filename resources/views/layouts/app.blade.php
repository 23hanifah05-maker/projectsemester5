<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Klinik Utama Merah Putih')
    </title>

    {{-- FONT --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    {{-- FONT AWESOME --}}
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    {{-- CSS UTAMA --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}"
    >

    {{-- CSS KHUSUS HALAMAN --}}
    @yield('extra-css')

</head>

<body>

    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}
    @include('partials.sidebar')


    {{-- =====================================================
        MAIN CONTENT
    ====================================================== --}}
    <main class="main">


        {{-- =================================================
            HEADER
        ================================================== --}}
        <header class="header">

            <div class="header-left">

                {{-- ICON DASHBOARD / HALAMAN --}}
                <div class="header-icon">

                    @hasSection('header-icon')

                        @yield('header-icon')

                    @else

                        {{-- Default icon rumah --}}
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >

                            <path
                                d="M3 10.5L12 3L21 10.5"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M5 9.5V20H19V9.5"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linejoin="round"
                            />

                            <path
                                d="M9 20V14H15V20"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linejoin="round"
                            />

                        </svg>

                    @endif

                </div>


                {{-- JUDUL HALAMAN --}}
                <h1>
                    @yield('header-title', 'Dashboard')
                </h1>

            </div>


            {{-- =================================================
                ADMIN
            ================================================== --}}
            <div class="admin">

                <div class="admin-icon">

                    <i class="fa-solid fa-user"></i>

                </div>

                <span>
                    {{ session('username', 'Admin') }}
                </span>

            </div>

        </header>


        {{-- =====================================================
            CONTENT
        ====================================================== --}}
        <section class="content">

            @yield('content')

        </section>

    </main>


    {{-- =====================================================
        JAVASCRIPT KHUSUS HALAMAN
    ====================================================== --}}
    @yield('extra-js')

</body>

</html>