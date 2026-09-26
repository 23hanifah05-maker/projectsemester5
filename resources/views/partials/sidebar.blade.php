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

                <span class="menu-icon">
                    <i class="fa-solid fa-house"></i>
                </span>

                <span class="menu-label">Dashboard</span>
            </a>
        </li>


        {{-- PENDAFTARAN --}}
        <li>
            <a href="{{ route('pendaftaran.index') }}"
               class="{{ request()->routeIs('pendaftaran*') ? 'active' : '' }}">

                <span class="menu-icon">
                    <i class="fa-solid fa-clipboard-list"></i>
                </span>

                <span class="menu-label">Pendaftaran</span>
            </a>
        </li>


        {{-- POLI --}}
        <li class="poli-menu">

            <a href="javascript:void(0)"
               class="poli-toggle {{ request()->routeIs('poli.*') ? 'open' : '' }}"
               onclick="togglePoli()">

                <span class="menu-icon">
                    <i class="fa-solid fa-stethoscope"></i>
                </span>

                <span class="menu-label">Poli</span>

                <span class="poli-arrow" id="poliArrow">
                    {{ request()->routeIs('poli.*') ? '▲' : '▼' }}
                </span>

            </a>


            <ul class="poli-submenu {{ request()->routeIs('poli.*') ? 'show' : '' }}"
                id="poliSubmenu">

                {{-- POLI SARAF --}}
                <li>
                    <a href="{{ route('poli.syaraf') }}"
                       class="{{ request()->routeIs('poli.syaraf*') ? 'active-sub' : '' }}">

                        <span class="sub-icon">
                            <i class="fa-solid fa-brain"></i>
                        </span>

                        <span>Poli Saraf</span>
                    </a>
                </li>


                {{-- POLI OBGYN --}}
                <li>
                    <a href="{{ route('poli.obgyn') }}"
                       class="{{ request()->routeIs('poli.obgyn*') ? 'active-sub' : '' }}">

                        <span class="sub-icon">
                            <i class="fa-solid fa-person-pregnant"></i>
                        </span>

                        <span>Poli Obgyn</span>
                    </a>
                </li>


                {{-- POLI JANTUNG --}}
                <li>
                    <a href="{{ route('poli.jantung') }}"
                       class="{{ request()->routeIs('poli.jantung*') ? 'active-sub' : '' }}">

                        <span class="sub-icon">
                            <i class="fa-solid fa-heart-pulse"></i>
                        </span>

                        <span>Poli Jantung</span>
                    </a>
                </li>


                {{-- POLI JIWA --}}
                <li>
                    <a href="{{ route('poli.jiwa') }}"
                       class="{{ request()->routeIs('poli.jiwa*') ? 'active-sub' : '' }}">

                        <span class="sub-icon">
                            <i class="fa-solid fa-head-side-virus"></i>
                        </span>

                        <span>Poli Jiwa</span>
                    </a>
                </li>

            </ul>
        </li>


        {{-- RADIOLOGI --}}
        <li>
            <a href="{{ route('radiologi.index') }}"
               class="{{ request()->routeIs('radiologi*') ? 'active' : '' }}">

                <span class="menu-icon">
                    <i class="fa-solid fa-radiation"></i>
                </span>

                <span class="menu-label">Radiologi</span>
            </a>
        </li>

    </ul>


    {{-- LOGOUT --}}
    <div class="logout">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit">

                <span class="menu-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </span>

                <span>Keluar</span>

            </button>

        </form>

    </div>

</aside>


<script>
function togglePoli() {

    const submenu = document.getElementById('poliSubmenu');
    const toggle = document.querySelector('.poli-toggle');
    const arrow = document.getElementById('poliArrow');

    if (!submenu || !toggle || !arrow) return;

    submenu.classList.toggle('show');
    toggle.classList.toggle('open');

    if (submenu.classList.contains('show')) {
        arrow.textContent = '▲';
    } else {
        arrow.textContent = '▼';
    }
}
</script>