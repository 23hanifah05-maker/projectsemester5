<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Audit Trail</title>
    <link rel="stylesheet" href="{{ asset('css/audit-trail.css') }}">
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