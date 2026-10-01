<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Trail</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f4f4;
            color: #222;
        }
        .main { margin-left: 230px; min-height: 100vh; }
        .topbar {
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 24px;
            border-bottom: 1px solid #eee;
        }
        .topbar .title {
            color: #a31515;
            font-weight: 700;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar .user { display: flex; align-items: center; gap: 8px; font-size: 14px; }
        .topbar .user .avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: #a31515;
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
        }
        .content { padding: 20px 24px; }
        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
        .card h3 { margin: 0 0 14px; font-size: 15px; color: #a31515; text-decoration: underline; }
        .filter {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }
        .filter-left, .filter-right { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; }
        .filter input { padding: 6px 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px; }
        .filter input[type="text"] { width: 260px; }
        .btn-cari, .btn-reset {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }
        .btn-cari { background: #a31515; color: white; }
        .btn-reset { background: #eee; color: #333; }
        .filter .sd { color: #888; font-weight: 400; }
        .btn-refresh { color: #111; font-size: 14px; text-decoration: none; padding: 4px; }
        .btn-refresh:hover { color: #a31515; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th { background: #a31515; color: white; padding: 10px 8px; text-align: center; font-weight: 700; }
        thead th:first-child { border-top-left-radius: 6px; }
        thead th:last-child { border-top-right-radius: 6px; }
        tbody td { padding: 10px 8px; text-align: center; border-bottom: 1px solid #eee; }
        tbody tr.row-log { cursor: pointer; }
        tbody tr.row-log:hover { background: #fbeaea; }
        .empty { padding: 24px; text-align: center; color: #888; }

        /* ===== MODAL DETAIL ===== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            padding: 16px;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: white;
            width: 100%;
            max-width: 480px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            animation: pop 0.2s ease;
        }
        @keyframes pop {
            from { transform: scale(0.95); opacity: 0; }
            to   { transform: scale(1); opacity: 1; }
        }
        .modal-header {
            background: #b71c1c;
            color: white;
            padding: 14px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 15px;
        }
        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
            line-height: 1;
        }
        .modal-body { padding: 14px; }
        .detail-box { border: 1px solid #ccc; border-radius: 8px; padding: 10px 14px; }
        .detail-row { display: flex; font-size: 12px; padding: 4px 0; }
        .detail-row .label { width: 125px; flex-shrink: 0; }
        .detail-row .sep { width: 16px; flex-shrink: 0; }
        .detail-row .value { flex: 1; word-break: break-word; }
        .status-berhasil { color: #2e9e4f; }
        .status-gagal { color: #d32f2f; }
    </style>
</head>
<body>

    {{-- SIDEBAR --}}
    @include('partials.sidebar')

    <div class="main">
        {{-- TOPBAR --}}
        <div class="topbar">
            <div class="title">
                <i class="fa-solid fa-clock-rotate-left"></i> Audit Trail
            </div>
            <div class="user">
                <div class="avatar"><i class="fa-solid fa-user"></i></div>
                Admin
            </div>
        </div>

        {{-- ISI --}}
        <div class="content">
            <div class="card">
                <h3>Log Aktivitas</h3>

                <form method="GET" action="{{ route('audit-trail.index') }}" class="filter" id="formFilter">
                    <div class="filter-left">
                        <span>Periode</span>
                        <input type="date" name="dari" id="inpDari" value="{{ request('dari') }}">
                        <span class="sd">s.d</span>
                        <input type="date" name="sampai" id="inpSampai" value="{{ request('sampai') }}">
                    </div>
                    <div class="filter-right">
                        <span>Keyword</span>
                        <input type="text" name="keyword" id="inpKeyword" value="{{ request('keyword') }}" autocomplete="off">
                        <a href="{{ route('audit-trail.index') }}" class="btn-refresh" title="Reset / Refresh">
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
                        <tbody>
                            @forelse ($logs as $log)
                                @php
                                    $detail = $log;
                                    $detail['waktu'] = \Carbon\Carbon::parse($log['waktu'])->format('d/m/Y  H.i');
                                @endphp
                                <tr class="row-log" data-log='@json($detail)'>
                                    <td>{{ $log['id'] }}</td>
                                    <td>{{ $log['username'] }}</td>
                                    <td>{{ $log['role'] }}</td>
                                    <td>{{ $log['aktivitas'] }}</td>
                                    <td>{{ $log['modul'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($log['waktu'])->format('d/m/Y H.i') }}</td>
                                    <td>{{ $log['ip'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty">Tidak ada data log.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL LOG AKTIVITAS --}}
    <div class="modal-overlay" id="modalDetail">
        <div class="modal-box">
            <div class="modal-header">
                <span>Detail Log Aktivitas</span>
                <button type="button" class="modal-close" id="btnTutup" aria-label="Tutup">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-box">
                    <div class="detail-row"><div class="label">ID</div><div class="sep">:</div><div class="value" id="d-id"></div></div>
                    <div class="detail-row"><div class="label">Username</div><div class="sep">:</div><div class="value" id="d-username"></div></div>
                    <div class="detail-row"><div class="label">Role</div><div class="sep">:</div><div class="value" id="d-role"></div></div>
                    <div class="detail-row"><div class="label">Aktivitas</div><div class="sep">:</div><div class="value" id="d-aktivitas"></div></div>
                    <div class="detail-row"><div class="label">Modul</div><div class="sep">:</div><div class="value" id="d-modul"></div></div>
                    <div class="detail-row"><div class="label">Waktu</div><div class="sep">:</div><div class="value" id="d-waktu"></div></div>
                    <div class="detail-row"><div class="label">IP Adress</div><div class="sep">:</div><div class="value" id="d-ip"></div></div>
                    <div class="detail-row"><div class="label">Nomor RM</div><div class="sep">:</div><div class="value" id="d-no_rm"></div></div>
                    <div class="detail-row"><div class="label">Bidang Kolom</div><div class="sep">:</div><div class="value" id="d-kolom"></div></div>
                    <div class="detail-row"><div class="label">Sebelum</div><div class="sep">:</div><div class="value" id="d-sebelum"></div></div>
                    <div class="detail-row"><div class="label">Sesudah</div><div class="sep">:</div><div class="value" id="d-sesudah"></div></div>
                    <div class="detail-row"><div class="label">Status</div><div class="sep">:</div><div class="value" id="d-status"></div></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalDetail');
        const fields = ['id','username','role','aktivitas','modul','waktu','ip','no_rm','kolom','sebelum','sesudah','status'];

        document.querySelectorAll('.row-log').forEach(function (row) {
            row.addEventListener('click', function () {
                const data = JSON.parse(this.dataset.log);

                fields.forEach(function (f) {
                    document.getElementById('d-' + f).textContent = data[f] ?? '-';
                });

                const st = document.getElementById('d-status');
                st.className = 'value ' + (data.status === 'Berhasil' ? 'status-berhasil' : 'status-gagal');

                modal.classList.add('show');
            });
        });

        // ===== FILTER OTOMATIS (tanpa tombol Cari) =====
        const formFilter = document.getElementById('formFilter');
        const inpKeyword = document.getElementById('inpKeyword');
        let timer;

        // Ganti tanggal -> langsung filter
        ['inpDari', 'inpSampai'].forEach(function (id) {
            document.getElementById(id).addEventListener('change', function () {
                formFilter.submit();
            });
        });

        // Ketik keyword -> filter setelah berhenti mengetik 500ms
        inpKeyword.addEventListener('input', function () {
            clearTimeout(timer);
            timer = setTimeout(function () { formFilter.submit(); }, 500);
        });

        // Setelah halaman reload, kursor tetap di kolom keyword
        if (inpKeyword.value !== '') {
            inpKeyword.focus();
            inpKeyword.setSelectionRange(inpKeyword.value.length, inpKeyword.value.length);
        }

        function tutupModal() { modal.classList.remove('show'); }

        document.getElementById('btnTutup').addEventListener('click', tutupModal);
        modal.addEventListener('click', function (e) { if (e.target === modal) tutupModal(); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutupModal(); });
    </script>

</body>
</html>