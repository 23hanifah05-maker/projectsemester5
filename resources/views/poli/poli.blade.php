<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $namaPoli }} - Klinik Rawat Inap Merah Putih</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --red: #b81d24;
            --red-dark: #8f1419;
            --red-light: #d9363e;
            --white: #ffffff;
            --bg: #b9d3b5;
            --line: #d8d8d8;
            --text: #222222;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f3f3;
            color: var(--text);
            font-size: 14px;
        }

        .layout { display: flex; min-height: 100vh; }

        /* ================= SIDEBAR ================= */
        .sidebar {
            width: 270px;
            flex-shrink: 0;
            background: linear-gradient(180deg, var(--red) 0%, var(--red-dark) 100%);
            color: var(--white);
            padding: 24px 22px;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand { display: flex; align-items: center; gap: 12px; margin-bottom: 34px; }
        .brand-logo {
            width: 56px; height: 56px; border-radius: 50%;
            background: var(--white); color: var(--red);
            display: grid; place-items: center;
            font-weight: 700; font-size: 16px; flex-shrink: 0;
            border: 3px solid #f1c0c3;
        }
        .brand-name { font-weight: 600; font-size: 13px; line-height: 1.3; }

        .menu-title { font-size: 13px; opacity: .85; margin-bottom: 14px; }

        .menu { list-style: none; }
        .menu > li { margin-bottom: 8px; }

        .menu a {
            display: flex; align-items: center; gap: 12px;
            color: var(--white); text-decoration: none;
            padding: 10px 12px; border-radius: 12px;
            font-weight: 600; font-size: 15px;
            transition: background .15s;
        }
        .menu a i.icon { width: 20px; text-align: center; font-size: 17px; }
        .menu a:hover { background: rgba(255,255,255,.12); }

        /* Menu aktif (Poli) */
        .menu > li.active > a {
            background: var(--white);
            color: var(--red);
        }
        .menu > li.active > a .chevron { margin-left: auto; font-size: 12px; }

        .submenu {
            list-style: none;
            background: rgba(255,255,255,.14);
            border-radius: 0 0 12px 12px;
            padding: 8px 0 10px;
            margin-top: -6px;
            display: none;
        }
        .menu > li.active .submenu { display: block; }

        .submenu a {
            font-weight: 500; font-size: 13px;
            padding: 7px 16px; border-radius: 0; gap: 10px;
        }
        .submenu a::before {
            content: ''; width: 7px; height: 7px;
            background: var(--white); border-radius: 50%;
        }
        .submenu a.current { background: rgba(255,255,255,.22); }

        .logout {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,.7);
        }
        .logout a { font-size: 13px; font-weight: 500; padding: 8px 4px; }

        /* ================= KONTEN ================= */
        .main { flex: 1; padding: 0; min-width: 0; }

        .topbar {
            background: var(--white);
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 30px;
            border-bottom: 1px solid #eee;
        }
        .page-title {
            display: flex; align-items: center; gap: 10px;
            color: var(--red); font-weight: 600; font-size: 16px;
        }
        .page-title i { font-size: 20px; }

        .user { display: flex; align-items: center; gap: 10px; font-weight: 500; }
        .user-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--red); color: var(--white);
            display: grid; place-items: center; font-size: 14px;
        }

        .content { padding: 22px 28px; }

        .card {
            background: var(--white);
            border-radius: 8px;
            padding: 22px 24px 28px;
            min-height: 70vh;
        }

        .card h2 {
            display: inline-block;
            font-size: 14px; font-weight: 600; color: var(--red);
            border-bottom: 2px solid var(--red);
            padding-bottom: 2px; margin-bottom: 16px;
        }

        /* Filter */
        .filters {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 14px; margin-bottom: 16px;
        }
        .filter-group { display: flex; align-items: center; gap: 10px; font-size: 12px; font-weight: 600; }
        .filter-group span.sd { font-weight: 400; color: #777; }

        .input {
            border: 1px solid #bbb; border-radius: 4px;
            padding: 6px 10px; font-family: inherit; font-size: 12px;
            background: var(--white); min-width: 160px;
        }
        .input:focus { outline: 2px solid var(--red-light); outline-offset: 1px; }
        .input.keyword { min-width: 300px; }

        /* Tabel */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        thead th {
            background: var(--red); color: var(--white);
            font-weight: 600; padding: 8px 10px; text-align: center;
            border-right: 1px solid rgba(255,255,255,.35);
            white-space: nowrap;
        }
        thead th:last-child { border-right: 0; }
        tbody td {
            padding: 9px 10px; border: 1px solid var(--line);
            text-align: center; height: 34px;
        }
        tbody td.left { text-align: left; }
        tbody tr:hover { background: #fff4f4; }

        /* Tombol aksi */
        .aksi { display: flex; justify-content: center; gap: 4px; }
        .btn-aksi {
            width: 20px; height: 20px; border-radius: 3px;
            background: var(--red); color: var(--white);
            display: grid; place-items: center;
            font-size: 10px; text-decoration: none; border: 0; cursor: pointer;
        }
        .btn-aksi:hover { background: var(--red-dark); }

        .empty { color: #888; padding: 24px 0; }

        @media (max-width: 900px) {
            .layout { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: static; }
            .input.keyword { min-width: 100%; }
        }
    </style>
</head>
<body>
<div class="layout">

    {{-- ============ SIDEBAR ============ --}}
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-logo">MP</div>
            <div class="brand-name">KLINIK RAWAT INAP<br>MERAH PUTIH</div>
        </div>

        <div class="menu-title">Menu Utama</div>

        <ul class="menu">
            <li>
                <a href="#"><i class="fa-solid fa-house icon"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fa-solid fa-clipboard-list icon"></i> Pendaftaran</a>
            </li>
            <li class="active">
                <a href="#"><i class="fa-solid fa-stethoscope icon"></i> Poli
                    <i class="fa-solid fa-chevron-down chevron"></i>
                </a>
                <ul class="submenu">
                    @foreach ($daftarPoli as $slug => $nama)
                        <li>
                            <a href="{{ route('poli.show', $slug) }}"
                               class="{{ $poliAktif === $slug ? 'current' : '' }}">
                                {{ $nama }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <li>
                <a href="#"><i class="fa-solid fa-x-ray icon"></i> Radiologi</a>
            </li>
        </ul>

        <div class="logout">
            <a href="#"><i class="fa-solid fa-right-from-bracket icon"></i> Keluar</a>
        </div>
    </aside>

    {{-- ============ KONTEN ============ --}}
    <div class="main">
        <header class="topbar">
            <div class="page-title">
                <i class="fa-solid fa-stethoscope"></i> {{ $namaPoli }}
            </div>
            <div class="user">
                <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                Admin
            </div>
        </header>

        <div class="content">
            <div class="card">
                <h2>Daftar Pasien</h2>

                <form method="GET" action="" class="filters">
                    <div class="filter-group">
                        <label for="periode_awal">Periode</label>
                        <input type="date" id="periode_awal" name="periode_awal" class="input"
                               value="{{ request('periode_awal') }}">
                        <span class="sd">s.d</span>
                        <input type="date" name="periode_akhir" class="input"
                               value="{{ request('periode_akhir') }}" aria-label="Periode akhir">
                    </div>

                    <div class="filter-group">
                        <label for="keyword">Keyword</label>
                        <input type="text" id="keyword" name="keyword" class="input keyword"
                               value="{{ request('keyword') }}" placeholder="Cari nama, No. RM, atau NIK">
                    </div>
                </form>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:40px">No</th>
                                <th>No. RM</th>
                                <th style="min-width:160px">Nama Pasien</th>
                                <th>NIK</th>
                                <th>Tgl. Lahir</th>
                                <th>Jenis Kelamin</th>
                                <th style="min-width:180px">Alamat</th>
                                <th style="width:110px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data dari controller: $pasien --}}
                            @forelse (($pasien ?? []) as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->no_rm }}</td>
                                    <td class="left">{{ $p->nama }}</td>
                                    <td>{{ $p->nik }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tgl_lahir)->format('d-m-Y') }}</td>
                                    <td>{{ $p->jenis_kelamin }}</td>
                                    <td class="left">{{ $p->alamat }}</td>
                                    <td>
                                        <div class="aksi">
                                            <a href="#" class="btn-aksi" title="Detail"><i class="fa-solid fa-check"></i></a>
                                            <a href="#" class="btn-aksi" title="Tambah"><i class="fa-solid fa-plus"></i></a>
                                            <a href="#" class="btn-aksi" title="Ubah"><i class="fa-solid fa-pen"></i></a>
                                            <button type="button" class="btn-aksi" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- Baris kosong seperti pada desain --}}
                                @for ($i = 0; $i < 6; $i++)
                                    <tr>
                                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                        <td>
                                            <div class="aksi">
                                                <a href="#" class="btn-aksi"><i class="fa-solid fa-check"></i></a>
                                                <a href="#" class="btn-aksi"><i class="fa-solid fa-plus"></i></a>
                                                <a href="#" class="btn-aksi"><i class="fa-solid fa-pen"></i></a>
                                                <button type="button" class="btn-aksi"><i class="fa-solid fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>