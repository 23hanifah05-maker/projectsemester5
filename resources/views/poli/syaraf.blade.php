<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Poli Saraf</title>

    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;

            width: 342px;
            height: 100vh;

            background: linear-gradient(
                rgba(190, 0, 0, 0.92),
                rgba(150, 0, 0, 0.95)
            );

            color: white;

            padding-top: 35px;
        }

        /* LOGO */

        .logo {
            display: flex;
            align-items: center;

            padding: 0 25px;

            margin-bottom: 30px;
        }

        .logo img {
            width: 80px;
            height: 80px;

            object-fit: contain;

            margin-right: 10px;
        }

        .logo-text {
            font-size: 20px;
            font-weight: bold;
            line-height: 1.2;
        }

        /* MENU UTAMA */

        .menu-title {
            font-size: 23px;
            font-weight: bold;

            padding: 0 28px;

            margin-bottom: 10px;
        }

        /* MENU */

        .menu-item {
            display: flex;
            align-items: center;

            width: calc(100% - 40px);

            margin: 5px 20px;

            padding: 14px 25px;

            color: white;
            text-decoration: none;

            font-size: 22px;
            font-weight: bold;

            border-radius: 18px;
        }

        .menu-item i {
            width: 30px;
            margin-right: 10px;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.15);
        }

        /* =========================
           POLI
        ========================= */

        .poli-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            width: calc(100% - 40px);

            margin: 5px 20px 0;

            padding: 12px 20px;

            background: white;

            color: #d90000;

            border-radius: 18px;

            font-size: 22px;
            font-weight: bold;
        }

        .poli-header i {
            margin-right: 7px;
        }

        .submenu {
            width: calc(100% - 40px);

            margin: 0 20px;

            background: rgba(255,255,255,0.22);

            border-radius: 0 0 18px 18px;
        }

        .submenu a {
            display: flex;
            align-items: center;

            height: 40px;

            padding-left: 105px;

            color: white;

            text-decoration: none;

            font-size: 21px;

            border-bottom: 1px solid rgba(255,255,255,0.25);
        }

        .submenu a:hover {
            background: rgba(255,255,255,0.15);
        }

        .submenu a::before {
            content: "";

            width: 14px;
            height: 14px;

            background: white;

            border-radius: 50%;

            margin-right: 10px;
        }

        /* =========================
           MAIN
        ========================= */

        .main {
            margin-left: 342px;

            min-height: 100vh;
        }

        /* =========================
           HEADER
        ========================= */

        .topbar {
            height: 82px;

            background: white;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;
        }

        .page-title {
            display: flex;
            align-items: center;

            gap: 12px;

            color: #d90000;

            font-size: 23px;
            font-weight: bold;
        }

        .page-title i {
            font-size: 28px;
        }

        /* ADMIN */

        .admin {
            display: flex;
            align-items: center;

            gap: 15px;

            font-size: 22px;
        }

        .admin-icon {
            width: 43px;
            height: 43px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #e60000;

            color: white;

            border-radius: 50%;
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 16px;
        }

        .card {
            background: white;

            min-height: 880px;

            border-radius: 10px;

            padding: 30px 16px;
        }

        /* =========================
           DAFTAR PASIEN
        ========================= */

        .judul {
            display: inline-block;

            color: #d90000;

            font-size: 21px;

            font-weight: bold;

            padding-bottom: 5px;

            border-bottom: 3px solid #d90000;

            margin-bottom: 22px;
        }

        /* =========================
           FILTER
        ========================= */

        .filter {
            display: flex;

            align-items: center;

            justify-content: space-between;
        }

        .periode {
            display: flex;

            align-items: center;

            gap: 15px;
        }

        .periode label,
        .keyword label {
            font-size: 15px;

            font-weight: bold;
        }

        .periode input {
            width: 175px;

            height: 26px;

            border: 1px solid #a98787;

            border-radius: 4px;

            padding: 0 8px;
        }

        .keyword {
            display: flex;

            align-items: center;

            gap: 15px;
        }

        .keyword input {
            width: 340px;

            height: 26px;

            border: 1px solid #a98787;

            border-radius: 4px;

            padding: 0 10px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .sidebar {
                width: 250px;
            }

            .main {
                margin-left: 250px;
            }

            .submenu a {
                padding-left: 50px;
            }

            .filter {
                flex-direction: column;

                align-items: flex-start;

                gap: 20px;
            }

            .keyword {
                margin-left: 0;
            }

        }

    </style>
</head>


<body>


    <!-- =====================================
         SIDEBAR
    ====================================== -->

    <div class="sidebar">


        <!-- LOGO -->

        <div class="logo">

            <img
                src="{{ asset('images/logo-klinik.png') }}"
                alt="Logo Klinik">

            <div class="logo-text">

                KLINIK RAWAT INAP<br>
                MERAH PUTIH

            </div>

        </div>


        <!-- MENU UTAMA -->

        <div class="menu-title">
            Menu Utama
        </div>


        <!-- DASHBOARD -->

        <a href="{{ route('dashboard') }}"
           class="menu-item">

            <i class="fa-solid fa-house"></i>

            Dashboard

        </a>


        <!-- POLI -->

        <div class="poli-header">

            <span>

                <i class="fa-solid fa-stethoscope"></i>

                Poli

            </span>

            <i class="fa-solid fa-chevron-down"></i>

        </div>


        <!-- SUB MENU -->

        <div class="submenu">

            <a href="{{ route('poli.syaraf') }}">
                Poli Saraf
            </a>

            <a href="{{ route('poli.obgyn') }}">
                Poli Obgyn
            </a>

            <a href="{{ route('poli.jantung') }}">
                Poli Jantung
            </a>

            <a href="{{ route('poli.jiwa') }}">
                Poli Jiwa
            </a>

            <a href="{{ route('radiologi') }}">
                Poli Radiologi
            </a>

        </div>


        <!-- RADIOLOGI -->

        <a href="{{ route('radiologi') }}"
           class="menu-item">

            <i class="fa-solid fa-x-ray"></i>

            Radiologi

        </a>


    </div>



    <!-- =====================================
         MAIN CONTENT
    ====================================== -->

    <div class="main">


        <!-- TOPBAR -->

        <div class="topbar">


            <!-- JUDUL -->

            <div class="page-title">

                <i class="fa-solid fa-stethoscope"></i>

                Poli Saraf

            </div>


            <!-- ADMIN -->

            <div class="admin">

                <div class="admin-icon">

                    <i class="fa-solid fa-user"></i>

                </div>

                Admin

            </div>


        </div>



        <!-- CONTENT -->

        <div class="content">


            <div class="card">


                <!-- JUDUL -->

                <div class="judul">
                    Daftar Pasien
                </div>


                <!-- FILTER -->

                <div class="filter">


                    <!-- PERIODE -->

                    <div class="periode">

                        <label>
                            Periode
                        </label>

                        <input
                            type="date"
                            name="tanggal_awal">

                        <span>
                            s.d
                        </span>

                        <input
                            type="date"
                            name="tanggal_akhir">

                    </div>


                    <!-- KEYWORD -->

                    <div class="keyword">

                        <label>
                            Keyword
                        </label>

                        <input
                            type="text"
                            name="keyword">

                    </div>


                </div>


            </div>

        </div>


    </div>


</body>
</html>