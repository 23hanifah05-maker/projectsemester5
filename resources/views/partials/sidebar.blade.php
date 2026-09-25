{{-- resources/views/partials/sidebar.blade.php
     Sidebar sendiri, dipanggil dari layouts/app.blade.php lewat @include.
     request()->routeIs(...) dipakai supaya menu aktif berubah otomatis
     sesuai halaman yang sedang dibuka, tapi sidebar-nya sendiri tetap
     satu file yang sama untuk semua halaman. --}}

<aside class="sidebar">

    <div class="logo-area">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Klinik">
        <div class="logo-text">
            KLINIK UTAMA
            <span>MERAH PUTIH</span>
        </div>
    </div>

    <div class="menu-title">Menu Utama</div>

    <ul class="menu">

        {{-- DASHBOARD --}}
        <li>
            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="menu-icon">🏠</span>
                Dashboard
            </a>
        </li>

        {{-- PENDAFTARAN --}}
        <li>
            <a href="{{ route('pendaftaran.index') }}"
               class="{{ request()->routeIs('pendaftaran*') ? 'active' : '' }}">
                <span class="menu-icon">📋</span>
                Pendaftaran
            </a>
        </li>

        {{-- POLI (dropdown) --}}
        <li class="poli-menu">
            <a href="javascript:void(0);"
               class="poli-toggle {{ request()->routeIs('poli.*') ? 'open' : '' }}"
               onclick="togglePoli()">
                <span class="menu-icon">🩺</span>
                <span>Poli</span>
                <span class="poli-arrow" id="poliArrow">
                    {{ request()->routeIs('poli.*') ? '▲' : '▼' }}
                </span>
            </a>

            <ul class="poli-submenu {{ request()->routeIs('poli.*') ? 'show' : '' }}" id="poliSubmenu">
                <li>
                    <a href="{{ route('poli.syaraf') }}" class="{{ request()->routeIs('poli.syaraf') ? 'active-sub' : '' }}">
                        Poli Saraf
                    </a>
                </li>
                <li>
                    <a href="{{ route('poli.obgyn') }}" class="{{ request()->routeIs('poli.obgyn') ? 'active-sub' : '' }}">
                        Poli Obgyn
                    </a>
                </li>
                <li>
                    <a href="{{ route('poli.jantung') }}" class="{{ request()->routeIs('poli.jantung') ? 'active-sub' : '' }}">
                        Poli Jantung
                    </a>
                </li>
                <li>
                    <a href="{{ route('poli.jiwa') }}" class="{{ request()->routeIs('poli.jiwa') ? 'active-sub' : '' }}">
                        Poli Jiwa
                    </a>
                </li>
            </ul>
        </li>

        {{-- RADIOLOGI --}}
        <li>
            <a href="{{ route('radiologi.index') }}"
               class="{{ request()->routeIs('radiologi*') ? 'active' : '' }}">
                <span class="menu-icon">☢️</span>
                Radiologi
            </a>
        </li>

    </ul>

    <div class="logout">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">🚪 &nbsp; Keluar</button>
        </form>
    </div>

</aside>

<script>
        // Dropdown Poli. Karena sidebar ada di setiap halaman lewat @@include,
    // script ini juga otomatis ikut ada di setiap halaman.
    function togglePoli() {
        const submenu = document.getElementById('poliSubmenu');
        const toggle  = document.querySelector('.poli-toggle');
        const arrow   = document.getElementById('poliArrow');

        if (!submenu || !toggle || !arrow) return;

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