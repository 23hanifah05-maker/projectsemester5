@extends('layouts.app')

@section('title', 'Audit Trail - Klinik Utama Merah Putih')

@section('header-title', 'Audit Trail')

@section('header-icon')
    <i class="fa-solid fa-clock-rotate-left"></i>
@endsection

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/audit-trail.css') }}">
@endsection

@section('content')

<div class="audit-card">

    <h3 class="audit-title">Log Aktivitas</h3>

    <form class="filter" id="formFilter" onsubmit="return false;">

        <div class="filter-left">
            <span>Periode</span>

            <input type="date" id="inpDari">

            <span class="sd">s.d</span>

            <input type="date" id="inpSampai">
        </div>

        <div class="filter-right">
            <span>Keyword</span>

            <input
                type="text"
                id="inpKeyword"
                autocomplete="off"
                placeholder="Cari aktivitas..."
            >

            <a href="#" class="btn-refresh" id="btnReset" title="Reset / Refresh">
                <i class="fa-solid fa-rotate-right"></i>
            </a>
        </div>

    </form>


    <div class="table-wrap">

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Aktivitas</th>
                    <th>Modul</th>
                    <th>Waktu</th>
                    <th>IP Adress</th>
                </tr>
            </thead>

            <tbody id="tbodyLog"></tbody>

        </table>

    </div>

</div>


{{-- MODAL DETAIL LOG AKTIVITAS --}}

<div class="modal-overlay" id="modalDetail">

    <div class="modal-box">

        <div class="modal-header">

            <span>Detail Log Aktivitas</span>

            <button type="button" class="modal-close" id="btnTutup" aria-label="Tutup">
                &times;
            </button>

        </div>

        <div class="modal-body">

            <div class="detail-box">

                @foreach ([
                    'id' => 'ID',
                    'username' => 'Username',
                    'role' => 'Role',
                    'aktivitas' => 'Aktivitas',
                    'modul' => 'Modul',
                    'waktu' => 'Waktu',
                    'ip' => 'IP Adress',
                    'no_rm' => 'Nomor RM',
                    'kolom' => 'Bidang Kolom',
                    'sebelum' => 'Sebelum',
                    'sesudah' => 'Sesudah',
                    'status' => 'Status',
                ] as $key => $label)
                    <div class="detail-row">
                        <div class="label">{{ $label }}</div>
                        <div class="sep">:</div>
                        <div class="value" id="d-{{ $key }}"></div>
                    </div>
                @endforeach

            </div>

        </div>

    </div>

</div>

@endsection


@section('extra-js')

<script src="{{ asset('js/audit-log.js') }}"></script>

<script>

    const modal = document.getElementById('modalDetail');
    const tbody = document.getElementById('tbodyLog');

    const inpDari    = document.getElementById('inpDari');
    const inpSampai  = document.getElementById('inpSampai');
    const inpKeyword = document.getElementById('inpKeyword');

    const fields = [
        'id', 'username', 'role', 'aktivitas', 'modul', 'waktu',
        'ip', 'no_rm', 'kolom', 'sebelum', 'sesudah', 'status'
    ];


    // 2026-10-01 09:23:00 -> 01/10/2026 09.23
    function formatWaktu(w) {
        const m = String(w).match(/^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/);
        return m ? m[3] + '/' + m[2] + '/' + m[1] + ' ' + m[4] + '.' + m[5] : w;
    }


    function bukaDetail(log) {

        fields.forEach(function (f) {

            const nilai = log[f];

            document.getElementById('d-' + f).textContent =
                f === 'waktu'
                    ? formatWaktu(nilai)
                    : (nilai !== undefined && nilai !== '' ? nilai : '-');

        });

        document.getElementById('d-status').className =
            'value ' +
            (log.status === 'Berhasil' ? 'status-berhasil' : 'status-gagal');

        modal.classList.add('show');
    }


    function tampilkan() {

        const dari    = inpDari.value;
        const sampai  = inpSampai.value;
        const keyword = inpKeyword.value.trim().toLowerCase();

        const hasil = AuditLog.ambil()
            .filter(function (log) {

                const tanggal = String(log.waktu).substring(0, 10);

                if (dari && tanggal < dari) return false;
                if (sampai && tanggal > sampai) return false;

                if (keyword !== '') {
                    const gabung = Object.values(log).join(' ').toLowerCase();
                    if (!gabung.includes(keyword)) return false;
                }

                return true;
            })
            .sort(function (a, b) {
                return String(b.waktu).localeCompare(String(a.waktu)) || (b.id - a.id);
            });

        tbody.innerHTML = '';

        if (hasil.length === 0) {

            const tr = document.createElement('tr');
            const td = document.createElement('td');

            td.colSpan = 7;
            td.className = 'empty';
            td.textContent = 'Tidak ada data log.';

            tr.appendChild(td);
            tbody.appendChild(tr);

            return;
        }

        hasil.forEach(function (log) {

            const tr = document.createElement('tr');
            tr.className = 'row-log';

            [
                log.id,
                log.username,
                log.role,
                log.aktivitas,
                log.modul,
                formatWaktu(log.waktu),
                log.ip
            ].forEach(function (isi) {

                const td = document.createElement('td');
                td.textContent = isi;
                tr.appendChild(td);

            });

            tr.addEventListener('click', function () {
                bukaDetail(log);
            });

            tbody.appendChild(tr);

        });
    }


    inpDari.addEventListener('change', tampilkan);
    inpSampai.addEventListener('change', tampilkan);
    inpKeyword.addEventListener('input', tampilkan);

    document.getElementById('btnReset').addEventListener('click', function (e) {
        e.preventDefault();
        inpDari.value = '';
        inpSampai.value = '';
        inpKeyword.value = '';
        tampilkan();
    });


    function tutupModal() {
        modal.classList.remove('show');
    }

    document.getElementById('btnTutup').addEventListener('click', tutupModal);

    modal.addEventListener('click', function (e) {
        if (e.target === modal) tutupModal();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') tutupModal();
    });


    tampilkan();

</script>

@endsection