
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
        <div class="brand">
            KLINIK UTAMA <span>MERAH PUTIH</span>
        </div>
    </header>

    <main class="hero">

        <div class="welcome">
            <h1>Selamat <em>Datang</em></h1>
            <div class="bar"></div>
            <p>Silakan masuk untuk mengakses sistem.</p>
        </div>

        <div class="card">

            <div class="card-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik Utama Merah Putih">
                <div class="name">
                    KLINIK UTAMA <span>MERAH PUTIH</span>
                </div>
            </div>

            <div class="divider">Masuk ke akun anda</div>

            @if ($errors->any() || session('error'))
                <div class="alert" role="alert">
                    {{ session('error') ?? $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                {{-- Username --}}
                <label class="field-label" for="username">
                    Username
                </label>

                <div class="input">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm0 2c-4.4 0-8 2.2-8 5v3h16v-3c0-2.8-3.6-5-8-5Z"/>
                    </svg>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Masukkan username"
                        autocomplete="username"
                        required
                    >
                </div>

                {{-- Password --}}
                <label class="field-label" for="password">
                    Password
                </label>

                <div class="input">

                    {{-- Ikon gembok di samping Password --}}
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 10V7a5 5 0 0 1 10 0v3h1a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-6a3 3 0 0 1 3-3h1Zm3 0h4V7a2 2 0 0 0-4 0v3Z"/>
                    </svg>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                    {{-- Tombol untuk melihat password --}}
                    <button
                        type="button"
                        class="toggle"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >

                        {{-- Ikon mata terbuka --}}
                        <svg
                            id="eyeOpen"
                            viewBox="0 0 24 24"
                            width="22"
                            height="22"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>

                        {{-- Ikon mata tertutup --}}
                        <svg
                            id="eyeClosed"
                            viewBox="0 0 24 24"
                            width="22"
                            height="22"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                            style="display: none;"
                        >
                            <path d="m3 3 18 18"/>
                            <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                            <path d="M9.9 5.2A11.4 11.4 0 0 1 12 5c6.4 0 10 7 10 7a15.7 15.7 0 0 1-3 3.8"/>
                            <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.6 7 10 7a10.7 10.7 0 0 0 4.1-.8"/>
                        </svg>

                    </button>
                </div>

                <div class="row">
                    <a href="#">Lupa password?</a>
                </div>

                <button type="submit" class="btn">
                    MASUK
                </button>

            </form>
        </div>

        {{-- Gelombang bagian bawah --}}
        <svg
            class="wave"
            viewBox="0 0 1440 110"
            preserveAspectRatio="none"
            aria-hidden="true"
        >
            <defs>
                <linearGradient id="waveGrad" x1="0" x2="1" y1="0" y2="0">
                    <stop offset="0" stop-color="#e2454d"/>
                    <stop offset="1" stop-color="#b3151c"/>
                </linearGradient>
            </defs>

            <path
                d="M0 70 C300 20 700 120 1000 70 C1200 40 1350 10 1440 0 L1440 110 L0 110 Z"
                fill="url(#waveGrad)"
            />
        </svg>

    </main>

    {{-- JavaScript untuk menampilkan/menyembunyikan password --}}
    
<script>
    const pw = document.getElementById('password');
    const btn = document.getElementById('togglePassword');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    // Kondisi awal: password tersembunyi, ikon mata disilang
    pw.type = 'password';
    eyeOpen.style.display = 'none';
    eyeClosed.style.display = 'block';

    btn.setAttribute('aria-label', 'Tampilkan password');
    btn.setAttribute('aria-pressed', 'false');

    btn.addEventListener('click', function () {
        const showPassword = pw.type === 'password';

        if (showPassword) {
            // Mata terbuka: password bisa dilihat
            pw.type = 'text';
            eyeOpen.style.display = 'block';
            eyeClosed.style.display = 'none';

            btn.setAttribute('aria-label', 'Sembunyikan password');
            btn.setAttribute('aria-pressed', 'true');
        } else {
            // Mata disilang: password tidak bisa dilihat
            pw.type = 'password';
            eyeOpen.style.display = 'none';
            eyeClosed.style.display = 'block';

            btn.setAttribute('aria-label', 'Tampilkan password');
            btn.setAttribute('aria-pressed', 'false');
        }
    });
</script>
</body>
</html>