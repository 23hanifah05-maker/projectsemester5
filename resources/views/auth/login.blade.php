<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Klinik Utama Merah Putih</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --red: #d2232a;
            --red-dark: #8a1a20;
            --red-light: #d0525b;
            --ink: #1a1a1a;
            --muted: #7a7a7a;
            --line: #cfcfcf;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { height: 100%; }
        body {
            font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
            color: var(--ink);
            background: #fff;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        .topbar {
            height: 56px;
            padding: 0 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fff;
            flex-shrink: 0;
        }
        .topbar img { width: 38px; height: 38px; object-fit: contain; }
        .brand { line-height: 1.15; font-size: 12px; font-weight: 600; }
        .brand span { display: block; color: var(--red); font-weight: 700; }

        /* Area utama */
        .hero {
            position: relative;
            flex: 1;
            min-height: 560px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px 80px;
            overflow: hidden;
            background:
                linear-gradient(to right, rgba(255,255,255,.88) 0%, rgba(255,255,255,.35) 45%, rgba(255,255,255,0) 70%),
                linear-gradient(to bottom, rgba(238,140,140,.35) 0%, rgba(255,255,255,0) 40%),
                url("{{ asset('images/background-login.jpeg') }}") center / cover no-repeat;
        }

        /* Teks sambutan */
        .welcome {
            position: absolute;
            left: 7%;
            top: 50%;
            transform: translateY(-50%);
            max-width: 260px;
        }
        .welcome h1 {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.2;
        }
        .welcome h1 em { font-style: normal; color: var(--red); }
        .welcome .bar {
            width: 88px;
            height: 4px;
            border-radius: 4px;
            background: var(--red);
            margin: 2px 0 8px;
        }
        .welcome p { font-size: 13px; color: #5c5c5c; }

        /* Kartu login */
        .card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 380px;
            background: #fff;
            border-radius: 28px;
            padding: 32px 24px 26px;
            box-shadow: 0 12px 40px rgba(0,0,0,.18);
        }
        .card-logo { text-align: center; }
        .card-logo img { width: 84px; height: 84px; object-fit: contain; }
        .card-logo .name { font-size: 12px; font-weight: 600; line-height: 1.25; margin-top: 4px; }
        .card-logo .name span { display: block; color: var(--red); font-weight: 700; }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0 22px;
            font-size: 11px;
            color: var(--muted);
            white-space: nowrap;
        }
        .divider::before, .divider::after { content: ""; flex: 1; height: 1px; background: var(--line); }

        label.field-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .input {
            display: flex;
            align-items: center;
            gap: 10px;
            height: 44px;
            padding: 0 14px;
            border: 1px solid #b9b9b9;
            border-radius: 14px;
            background: #fff;
            margin-bottom: 16px;
        }
        .input:focus-within { border-color: var(--red); box-shadow: 0 0 0 3px rgba(210,35,42,.15); }
        .input svg { width: 16px; height: 16px; flex-shrink: 0; fill: #222; }
        .input input {
            flex: 1;
            min-width: 0;
            border: 0;
            outline: 0;
            background: transparent;
            font: inherit;
            font-size: 12px;
        }
        .input input::placeholder { color: #8a8a8a; }
        .toggle { border: 0; background: none; cursor: pointer; display: flex; padding: 2px; }
        .toggle svg { fill: none; stroke: #555; stroke-width: 1.8; }

        .row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            margin: 2px 0 18px;
        }
        .row label { display: flex; align-items: center; gap: 8px; color: #666; cursor: pointer; }
        .row input[type=checkbox] { width: 16px; height: 16px; accent-color: var(--red); }
        .row a { color: var(--red); font-weight: 600; text-decoration: none; }
        .row a:hover { text-decoration: underline; }

        .btn {
            width: 100%;
            height: 44px;
            border: 0;
            border-radius: 12px;
            color: #fff;
            font: inherit;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: .5px;
            cursor: pointer;
            background: linear-gradient(to right, var(--red-light), var(--red-dark));
        }
        .btn:hover { filter: brightness(1.07); }
        .btn:focus-visible, .row a:focus-visible, .toggle:focus-visible { outline: 3px solid rgba(210,35,42,.4); outline-offset: 2px; }

        .alert {
            background: #fdecec;
            color: #9b1c1c;
            border: 1px solid #f5b5b5;
            border-radius: 12px;
            padding: 10px 12px;
            font-size: 12px;
            margin-bottom: 16px;
        }

        /* Gelombang merah di bawah */
        .wave { position: absolute; left: 0; right: 0; bottom: 0; width: 100%; height: 110px; z-index: 1; pointer-events: none; }

        @media (max-width: 900px) {
            .welcome { display: none; }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik Utama Merah Putih">
        <div class="brand">KLINIK UTAMA <span>MERAH PUTIH</span></div>
    </header>

    <main class="hero">

        <div class="welcome">
            <h1>Selamat <em>Datang</em></h1>
            <div class="bar"></div>
            <p>Silahkan masuk untuk mengakses sitem.</p>
        </div>

        <div class="card">
            <div class="card-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik Utama Merah Putih">
                <div class="name">KLINIK UTAMA <span>MERAH PUTIH</span></div>
            </div>

            <div class="divider">Masuk ke akun anda</div>

            @if ($errors->any() || session('error'))
                <div class="alert" role="alert">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <label class="field-label" for="username">Username</label>
                <div class="input">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.4 0-8 2.2-8 5v3h16v-3c0-2.8-3.6-5-8-5Z"/></svg>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username" autocomplete="username" required>
                </div>

                <label class="field-label" for="password">Password</label>
                <div class="input">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M9 4h6a1 1 0 0 1 1 1v1h3a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3V5a1 1 0 0 1 1-1Zm1 2h4V6h-4Z"/></svg>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
                    <button type="button" class="toggle" id="togglePassword" aria-label="Tampilkan password">
                        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>

                <div class="row">
                    <label><input type="checkbox" name="remember"> Ingat saya</label>
                    <a href="#">Lupa password?</a>
                </div>

                <button type="submit" class="btn">MASUK</button>
            </form>
        </div>

        <svg class="wave" viewBox="0 0 1440 110" preserveAspectRatio="none" aria-hidden="true">
            <defs>
                <linearGradient id="waveGrad" x1="0" x2="1" y1="0" y2="0">
                    <stop offset="0" stop-color="#e2454d"/>
                    <stop offset="1" stop-color="#b3151c"/>
                </linearGradient>
            </defs>
            <path d="M0 70 C300 20 700 120 1000 70 C1200 40 1350 10 1440 0 L1440 110 L0 110 Z" fill="url(#waveGrad)"/>
        </svg>

    </main>

    <script>
        const pw = document.getElementById('password');
        const btn = document.getElementById('togglePassword');
        btn.addEventListener('click', function () {
            const show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
        });
    </script>
</body>
</html>