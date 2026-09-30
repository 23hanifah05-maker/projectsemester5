<!-- Import FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --sidebar-width: 230px; 
        --overlay-color: rgba(163, 21, 21, 0.88); 
    }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: linear-gradient(
            var(--overlay-color), 
            var(--overlay-color)
        ), url("{{ asset('images/background-login.jpeg') }}");
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        color: white;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between; 
        transition: all 0.3s ease;
        z-index: 1000;
        overflow-y: auto; 
        overflow-x: hidden;
    }

    .sidebar.hide {
        left: -var(--sidebar-width);
        transform: translateX(-100%);
    }

    .sidebar::-webkit-scrollbar {
        width: 4px;
    }
    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 4px;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px 15px 5px; 
        flex-shrink: 0;
    }

    .sidebar-header img {
        width: 40px;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        padding: 2px;
        object-fit: contain;
    }

    .sidebar-header h3 {
        font-size: 11.5px;
        line-height: 1.2;
        font-weight: 700;
        margin: 0;
    }

    .menu-label {
        padding: 5px 15px;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 600;
        flex-shrink: 0;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0 10px;
        margin: 0;
    }

    .sidebar-menu li {
        margin-bottom: 6px;
    }

    .sidebar-menu li a {
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .sidebar-menu li a i.icon-main {
        width: 22px;
        font-size: 16px;
    }

    .sidebar-menu li a:hover,
    .sidebar-menu li a.active {
        background-color: white;
        color: #a31515;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .sidebar-menu li a.active i.icon-main {
        color: #a31515;
    }

    .has-dropdown > a { justify-content: space-between; }
    .has-dropdown > a .left-content { display: flex; align-items: center; }
    .dropdown-icon { font-size: 11px !important; transition: transform 0.3s; }
    
    .sidebar-menu li a.expanded {
        background-color: white;
        color: #a31515;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
    }

    .sidebar-menu li a.expanded i.icon-main,
    .sidebar-menu li a.expanded .dropdown-icon {
        color: #a31515;
    }
    
    .sidebar-menu li a.expanded .dropdown-icon { transform: rotate(180deg); }

    .submenu {
        list-style: none;
        background: rgba(0, 0, 0, 0.25);
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        overflow: hidden;
        display: none; 
        padding: 0;
        margin: 0;
    }

    .submenu.show { display: block; }
    .submenu li { margin-bottom: 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
    
    .submenu li a {
        padding: 8px 12px 8px 35px;
        font-size: 13px;
        border-radius: 0;
        color: white;
        background: transparent !important;
        box-shadow: none !important;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .submenu li a i {
        width: 16px;
        text-align: center;
    }

    .submenu li a:hover { background: rgba(255, 255, 255, 0.2) !important; color: white !important; }

    .sidebar-footer { 
        padding: 15px 20px; 
        flex-shrink: 0;
        margin-top: auto;
    }
    .sidebar-footer hr { border: none; border-top: 1px solid rgba(255, 255, 255, 0.4); margin-bottom: 10px; }
    .sidebar-footer a { color: white; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 600; }
    .sidebar-footer a:hover { opacity: 0.8; }
</style>

<!-- STRUKTUR SIDEBAR -->
<div class="sidebar" id="mySidebar">
    <div>
        <div class="sidebar-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h3>KLINIK RAWAT INAP<br>MERAH PUTIH</h3>
        </div>
        
        <div class="menu-label">Menu Utama</div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <div class="left-content"><i class="fa-solid fa-house icon-main"></i> Dashboard</div>
                </a>
            </li>
            <li>
                <a href="{{ route('pendaftaran.index') }}" class="{{ request()->routeIs('pendaftaran*') ? 'active' : '' }}">
                    <div class="left-content"><i class="fa-solid fa-address-book icon-main"></i> Pendaftaran</div>
                </a>
            </li>
            <li class="has-dropdown">
                <a href="#" id="btnPoli" class="{{ request()->routeIs('poli.*') ? 'expanded' : '' }}">
                    <div class="left-content"><i class="fa-solid fa-stethoscope icon-main"></i> Poli</div>
                    <i class="fa-solid fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu {{ request()->routeIs('poli.*') ? 'show' : '' }}" id="menuPoli">
                    <li><a href="{{ route('poli.jantung') }}"><i class="fa-solid fa-heart-pulse"></i> Poli Jantung</a></li>
                    <li><a href="{{ route('poli.jiwa') }}"><i class="fa-solid fa-head-side-virus"></i> Poli Jiwa</a></li>
                    <li><a href="{{ route('poli.syaraf') }}"><i class="fa-solid fa-brain"></i> Poli Syaraf</a></li>
                    <li><a href="{{ route('poli.obgyn') }}"><i class="fa-solid fa-person-dress"></i> Poli Obgyn</a></li>
                    <li><a href="{{ route('radiologi.index') }}"><i class="fa-solid fa-x-ray"></i> Poli Radiologi</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('radiologi.index') }}" class="{{ request()->routeIs('radiologi*') ? 'active' : '' }}">
                    <div class="left-content"><i class="fa-solid fa-radiation icon-main"></i> Radiologi</div>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer">
        <hr>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background:none; border:none; padding:0; cursor:pointer; width:100%;">
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                </a>
            </button>
        </form>
    </div>
</div>

<script>
    document.getElementById('btnPoli').addEventListener('click', function(e) {
        e.preventDefault(); 
        document.getElementById('menuPoli').classList.toggle('show'); 
        this.classList.toggle('expanded'); 
    });

    function toggleSidebar() {
        const sidebar = document.getElementById('mySidebar');
        sidebar.classList.toggle('hide');
        document.body.classList.toggle('sidebar-collapsed');
    }
</script>