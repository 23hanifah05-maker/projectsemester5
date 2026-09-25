<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Klinik Utama Merah Putih</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- CSS eksternal untuk halaman login --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
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