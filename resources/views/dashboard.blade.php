<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Klinik Utama Merah Putih</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f5f5;
            color: #222;
            font-size: 13px;
        }


        /* =====================================================
           SIDEBAR
        ===================================================== */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;

            width: 225px;
            height: 100vh;

            color: white;

            padding: 25px 14px;

            z-index: 1000;

            /*
             * FOTO KLINIK DI BELAKANG
             * WARNA MERAH DIBUAT TRANSPARAN
             */

            background:
                linear-gradient(
                    to bottom,
                    rgba(215, 25, 32, 0.94) 0%,
                    rgba(195, 19, 26, 0.90) 50%,
                    rgba(167, 15, 22, 0.78) 100%
                ),
                url('{{ asset("images/background-login.jpeg") }}');

            background-size: cover;

            background-position: center bottom;

            background-repeat: no-repeat;
        }


        /* =====================================================
           LOGO
        ===================================================== */

        .logo-area {
            display: flex;

            align-items: center;

            gap: 8px;

            padding: 3px 8px 28px;
        }


        .logo-area img {
            width: 48px;

            height: 48px;

            object-fit: contain;

            filter: brightness(0) invert(1);
        }


        .logo-text {
            font-size: 11px;

            font-weight: 600;

            line-height: 1.25;

            color: white;
        }


        .logo-text span {
            display: block;

            font-weight: 700;
        }


        /* =====================================================
           MENU
        ===================================================== */

        .menu-title {
            font-size: 14px;

            margin: 0 7px 8px;

            opacity: .85;
        }


        .menu {
            list-style: none;
        }


        .menu li {
            margin-bottom: 4px;
        }


        .menu a {
            display: flex;

            align-items: center;

            gap: 10px;

            height: 35px;

            padding: 0 11px;

            border-radius: 9px;

            color: white;

            text-decoration: none;

            font-size: 13px;

            font-weight: 600;

            transition: .2s;
        }


        .menu a:hover,
        .menu a.active {
            background: white;

            color: #d71920;
        }


        .menu-icon {
            width: 20px;

            text-align: center;

            font-size: 16px;
        }


        /* =====================================================
           DROPDOWN POLI
        ===================================================== */

        /* =====================================================
           DROPDOWN POLI - SESUAI DESAIN
        ===================================================== */

        .poli-menu {
            position: relative;
            margin-bottom: 4px !important;
        }

        /* Tombol Poli */
        .poli-toggle {
            cursor: pointer;
            position: relative;
        }

        /* Saat dropdown terbuka, tombol Poli menjadi putih */
        .poli-toggle.open {
            background: white !important;
            color: #d71920 !important;
            border-radius: 9px 9px 0 0;
        }

        .poli-toggle.open .menu-icon {
            color: #d71920;
        }

        .poli-arrow {
            margin-left: auto;
            font-size: 10px;
            line-height: 1;
            color: inherit;
            transition: transform .2s ease;
        }

        /* Kotak dropdown */
        .poli-submenu {
            display: none;
            list-style: none;
            margin: 0;
            padding: 0;
            background: rgba(255, 255, 255, 0.20);
            border-radius: 0 0 9px 9px;
            overflow: hidden;
        }

        .poli-submenu.show {
            display: block;
        }

        /* Setiap pilihan Poli */
        .poli-submenu li {
            margin: 0 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.28);
        }

        .poli-submenu li:first-child {
            border-top: 0;
        }

        .poli-submenu li a {
            position: relative;
            display: flex;
            align-items: center;
            height: 34px;
            box-sizing: border-box;
            padding: 0 8px 0 42px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            line-height: 34px;
            border-radius: 0;
        }

        /* Titik putih seperti pada desain */
        .poli-submenu li a::before {
            content: "";
            position: absolute;
            left: 27px;
            top: 50%;
            width: 6px;
            height: 6px;
            margin-top: -3px;
            border-radius: 50%;
            background: white;
        }

        .poli-submenu li a:hover {
            background: rgba(255, 255, 255, 0.12);
        }


        /* =====================================================
           LOGOUT
        ===================================================== */

        .logout {
            position: absolute;

            left: 22px;

            right: 22px;

            bottom: 20px;

            padding-top: 12px;

            border-top: 2px solid rgba(255,255,255,.65);
        }


        .logout button {
            border: 0;

            background: transparent;

            color: white;

            font-family: inherit;

            font-size: 13px;

            font-weight: 600;

            cursor: pointer;

            padding: 3px 0;
        }


        /* =====================================================
           MAIN
        ===================================================== */

        .main {
            margin-left: 225px;

            min-height: 100vh;

            background: #f5f5f5;
        }


        /* =====================================================
           HEADER
        ===================================================== */

        .header {
            height: 55px;

            background: white;

            border-bottom: 1px solid #ddd;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 18px;
        }


        .header-title {
            color: #d71920;

            font-size: 16px;

            font-weight: 700;

            display: flex;

            align-items: center;

            gap: 8px;
        }


        .home-icon {
            font-size: 18px;
        }


        .admin {
            display: flex;

            align-items: center;

            gap: 9px;

            font-size: 14px;

            font-weight: 600;
        }


        .admin-icon {
            width: 31px;

            height: 31px;

            border-radius: 50%;

            background: #d71920;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            font-size: 15px;
        }


        /* =====================================================
           CONTENT
        ===================================================== */

        .content {
            padding: 18px 15px;
        }


        /* =====================================================
           PERIODE
        ===================================================== */

        .period {
            height: 40px;

            background: white;

            border: 1px solid #ddd;

            border-radius: 7px;

            padding: 0 18px;

            display: flex;

            align-items: center;

            gap: 8px;

            margin-bottom: 9px;
        }


        .period label {
            font-size: 10px;

            font-weight: 600;

            margin-right: 2px;
        }


        .period input {
            width: 113px;

            height: 27px;

            border: 1px solid #cfcfcf;

            border-radius: 4px;

            padding: 3px 7px;

            font-family: inherit;

            font-size: 10px;
        }


        .period span {
            font-size: 11px;
        }


        /* =====================================================
           STATISTICS
        ===================================================== */

        .statistics {
            display: grid;

            grid-template-columns: repeat(4, 1fr);

            gap: 13px;

            margin-bottom: 10px;
        }


        .stat-card {
            height: 65px;

            background: white;

            border: 1px solid #ddd;

            border-radius: 7px;

            padding: 8px 10px;

            display: flex;

            align-items: center;

            gap: 9px;
        }


        .stat-icon {
            width: 42px;

            height: 42px;

            flex-shrink: 0;

            background: #d71920;

            border-radius: 6px;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 19px;
        }


        .stat-title {
            font-size: 9px;

            font-weight: 600;

            margin-bottom: 1px;
        }


        .stat-number {
            font-size: 18px;

            font-weight: 700;

            line-height: 1.1;
        }


        /* =====================================================
           PANEL
        ===================================================== */

        .panel {
            background: white;

            border: 1px solid #ddd;

            border-radius: 7px;

            padding: 12px 15px;

            margin-bottom: 10px;
        }


        .panel-title {
            height: 29px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            border-bottom: 1px solid #ddd;

            color: #b3151c;

            font-size: 13px;

            font-weight: 700;

            margin-bottom: 7px;
        }


        .panel-title-left {
            display: flex;

            align-items: center;

            gap: 8px;
        }


        .panel-title-icon {
            width: 25px;

            height: 25px;

            background: #d71920;

            color: white;

            border-radius: 4px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 13px;
        }


        .panel-title small {
            font-size: 9px;

            font-weight: 500;

            color: #d71920;
        }


        /* =====================================================
           10 BESAR PENYAKIT
        ===================================================== */

        .disease-row {
            display: grid;

            grid-template-columns: 27px 88px 1fr 32px;

            align-items: center;

            gap: 4px;

            height: 17px;

            font-size: 9px;
        }


        .disease-number {
            text-align: center;
        }


        .disease-name {
            white-space: nowrap;
        }


        .bar-bg {
            height: 11px;

            background: #ddd;

            border-radius: 3px;

            overflow: hidden;
        }


        .bar {
            height: 100%;

            background: #a9151b;

            border-radius: 3px;
        }


        .disease-value {
            font-size: 9px;

            text-align: left;
        }


        /* =====================================================
           GRAFIK KUNJUNGAN
        ===================================================== */

        .chart {
            height: 160px;

            display: flex;

            align-items: flex-end;

            justify-content: space-around;

            padding: 5px 20px 0;

            position: relative;
        }


        .chart-item {
            width: 7%;

            height: 100%;

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: flex-end;
        }


        .chart-value {
            font-size: 9px;

            font-weight: 700;

            margin-bottom: 3px;
        }


        .chart-bar {
            width: 100%;

            min-height: 5px;

            background: #a9151b;

            border-radius: 2px 2px 0 0;
        }


        .chart-label {
            font-size: 8px;

            margin-top: 4px;

            white-space: nowrap;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1000px) {

            .sidebar {
                width: 210px;
            }

            .main {
                margin-left: 210px;
            }

            .statistics {
                gap: 8px;
            }

            .stat-card {
                padding: 7px;
            }

        }


        @media (max-width: 750px) {

            .sidebar {
                width: 180px;
            }

            .main {
                margin-left: 180px;
            }

            .statistics {
                grid-template-columns: repeat(2, 1fr);
            }

        }


        @media (max-width: 550px) {

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
            }

            .statistics {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="sidebar">


    <!-- LOGO -->

    <div class="logo-area">

        <img
            src="{{ asset('images/logo.png') }}"
            alt="Logo Klinik">


        <div class="logo-text">

            KLINIK UTAMA

            <span>
                MERAH PUTIH
            </span>

        </div>

    </div>



    <!-- MENU -->

    <div class="menu-title">

        Menu Utama

    </div>


    <ul class="menu">


        <!-- DASHBOARD -->

        <li>

            <a
                href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">

                <span class="menu-icon">
                    🏠
                </span>

                Dashboard

            </a>

        </li>



        <!-- PENDAFTARAN -->

        <li>

            <a href="#">

                <span class="menu-icon">
                    📋
                </span>

                Pendaftaran

            </a>

        </li>



        <!-- POLI -->

        <li class="poli-menu">

            <a href="javascript:void(0);"
               class="poli-toggle"
               onclick="togglePoli()">

                <span class="menu-icon">
                    🩺
                </span>

                <span>
                    Poli
                </span>

                <span class="poli-arrow" id="poliArrow">
                    ▼
                </span>

            </a>

            <!-- DROPDOWN POLI -->
            <ul class="poli-submenu" id="poliSubmenu">

                <li>
                    <a href="{{ url('/poli/jantung') }}">
                        Poli Jantung
                    </a>
                </li>

                <li>
                    <a href="{{ url('/poli/jiwa') }}">
                        Poli Jiwa
                    </a>
                </li>

                <li>
                    <a href="{{ url('/poli/syaraf') }}">
                        Poli Syaraf
                    </a>
                </li>

                <li>
                    <a href="{{ url('/poli/obgyn') }}">
                        Poli Obgyn
                    </a>
                </li>

            </ul>

        </li>



        <!-- RADIOLOGI -->

        <li>

            <a href="{{ url('/radiologi') }}">

                <span class="menu-icon">
                    ☢️
                </span>

                Radiologi

            </a>

        </li>


    </ul>



    <!-- KELUAR -->

    <div class="logout">

        <form
            method="POST"
            action="{{ route('logout') }}">

            @csrf

            <button type="submit">

                🚪 &nbsp; Keluar

            </button>

        </form>

    </div>


</aside>



<!-- =====================================================
     MAIN
===================================================== -->

<main class="main">


    <!-- HEADER -->

    <header class="header">


        <div class="header-title">

            <span class="home-icon">
                🏠
            </span>

            Dashboard

        </div>



        <div class="admin">


            <div class="admin-icon">

                👤

            </div>


            <span>

                {{ session('username', 'Admin') }}

            </span>


        </div>


    </header>



    <!-- CONTENT -->

    <section class="content">


        <!-- =================================================
             PERIODE
        ================================================== -->

        <div class="period">


            <label>
                Periode
            </label>


            <input
                type="date">


            <span>
                s.d
            </span>


            <input
                type="date">


        </div>



        <!-- =================================================
             KARTU STATISTIK
        ================================================== -->

        <div class="statistics">


            <!-- TOTAL PASIEN -->

            <div class="stat-card">


                <div class="stat-icon">
                    👥
                </div>


                <div>


                    <div class="stat-title">
                        Total Pasien
                    </div>


                    <div class="stat-number">
                        1.237
                    </div>


                </div>


            </div>



            <!-- POLI AKTIF -->

            <div class="stat-card">


                <div class="stat-icon">
                    🩺
                </div>


                <div>


                    <div class="stat-title">
                        Poli Aktif
                    </div>


                    <div class="stat-number">
                        1.237
                    </div>


                </div>


            </div>



            <!-- KAMAR -->

            <div class="stat-card">


                <div class="stat-icon">
                    🛏️
                </div>


                <div>


                    <div class="stat-title">
                        Total Kamar Tersedia
                    </div>


                    <div class="stat-number">
                        1.237
                    </div>


                </div>


            </div>



            <!-- PENDAFTARAN -->

            <div class="stat-card">


                <div class="stat-icon">
                    📋
                </div>


                <div>


                    <div class="stat-title">
                        Pendaftaran Hari Ini
                    </div>


                    <div class="stat-number">
                        1.237
                    </div>


                </div>


            </div>


        </div>



        <!-- =================================================
             10 BESAR PENYAKIT
        ================================================== -->

        <div class="panel">


            <div class="panel-title">


                <div class="panel-title-left">


                    <div class="panel-title-icon">
                        ▥
                    </div>


                    <span>
                        10 Besar Penyakit
                    </span>


                </div>


                <small>
                    Jumlah Kasus
                </small>


            </div>



            @php

                $penyakit = [

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

                $maksimal = 186;

            @endphp



            @foreach ($penyakit as $index => $data)


                <div class="disease-row">


                    <div class="disease-number">

                        {{ $index + 1 }}

                    </div>


                    <div class="disease-name">

                        {{ $data['nama'] }}

                    </div>


                    <div class="bar-bg">


                        <div
                            class="bar"
                            style="
                                width:
                                {{ ($data['jumlah'] / $maksimal) * 100 }}%;
                            ">
                        </div>


                    </div>


                    <div class="disease-value">

                        {{ $data['jumlah'] }}

                    </div>


                </div>


            @endforeach


        </div>



        <!-- =================================================
             KUNJUNGAN PER POLI
        ================================================== -->

        <div class="panel">


            <div class="panel-title">


                <div class="panel-title-left">


                    <div class="panel-title-icon">
                        ▥
                    </div>


                    <span>
                        Kunjungan Per Poli
                    </span>


                </div>


                <small>
                    Jumlah Kunjungan
                </small>


            </div>



            @php

                $kunjungan = [

                    186,
                    142,
                    98,
                    76,
                    64,
                    58,
                    52,
                    43,
                    37,
                    32

                ];


                $namaPoli = [

                    'Umum',
                    'Anak',
                    'Gigi',
                    'Dalam',
                    'Mata',
                    'Bedah',
                    'THT',
                    'Kulit',
                    'Saraf',
                    'Lainnya'

                ];

            @endphp



            <div class="chart">


                @foreach ($kunjungan as $index => $jumlah)


                    <div class="chart-item">


                        <div class="chart-value">

                            {{ $jumlah }}

                        </div>


                        <div
                            class="chart-bar"
                            style="
                                height:
                                {{ ($jumlah / 186) * 110 }}px;
                            ">
                        </div>


                        <div class="chart-label">

                            {{ $namaPoli[$index] }}

                        </div>


                    </div>


                @endforeach


            </div>


        </div>


    </section>


</main>



    <script>

        function togglePoli() {

            const submenu = document.getElementById('poliSubmenu');
            const arrow = document.getElementById('poliArrow');

            if (!submenu || !arrow) {
                return;
            }

            const toggle = document.querySelector('.poli-toggle');

            if (submenu.classList.contains('show')) {

                submenu.classList.remove('show');
                toggle.classList.remove('open');
                arrow.innerHTML = '▼';

            } else {

                submenu.classList.add('show');
                toggle.classList.add('open');
                arrow.innerHTML = '▲';

            }
        }

    </script>

</body>

</html>