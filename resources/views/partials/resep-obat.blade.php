{{--
    PARTIAL: Resep Obat + judul "PATEN (TINDAK LANJUT :)"
    Simpan di: resources/views/partials/resep-obat.blade.php
    Dipakai di Poli Obgyn dan Poli Syaraf lewat:  @include('partials.resep-obat')

    Isi partial ini berdiri sendiri (CSS, HTML, JavaScript), jadi halaman
    kunjungan hanya perlu satu baris @include.
--}}

<style>
    .rsp-plan-label {
        display: block;
        width: 100%;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #b81d24;
    }

    .rsp-box {
        margin-top: 14px;
        padding: 14px 16px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        background: #fff;
    }

    .rsp-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #b81d24;
    }

    .rsp-tambah {
        padding: 6px 12px;
        border: 1px solid #b81d24;
        border-radius: 6px;
        background: #fff;
        color: #b81d24;
        font: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .rsp-tambah:hover {
        background: #b81d24;
        color: #fff;
    }

    .rsp-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .rsp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .rsp-table th {
        padding: 6px 8px;
        background: #fdeaea;
        color: #b81d24;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
    }

    .rsp-table td {
        padding: 4px;
        vertical-align: middle;
    }

    .rsp-table input,
    .rsp-table select {
        width: 100%;
        box-sizing: border-box;
        padding: 6px 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: #fff;
        font: inherit;
        font-size: 13px;
    }

    .rsp-hapus {
        border: none;
        background: none;
        color: #c1121f;
        font-size: 15px;
        cursor: pointer;
    }
</style>

<div class="rsp-box" id="resepBox">
    <div class="rsp-head">
        <span>Resep Obat</span>

        <button type="button" class="rsp-tambah" onclick="tambahBarisResep()">
            + Tambah Obat
        </button>
    </div>

    <div class="rsp-wrap">
        <table class="rsp-table">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Nama Obat</th>
                    <th style="width:120px;">Jenis</th>
                    <th style="width:140px;">Dosis</th>
                    <th style="width:100px;">Jumlah</th>
                    <th>Aturan Pakai</th>
                    <th style="width:40px;"></th>
                </tr>
            </thead>

            <tbody id="resepBody"></tbody>
        </table>
    </div>
</div>

<script>
    // ---------------------------------------------------------
    // Judul "PATEN (TINDAK LANJUT :)" di atas kolom P
    // (berlaku untuk Poli Obgyn: .ob-plan-wrap, Poli Syaraf: .kb-plan-wrap)
    // ---------------------------------------------------------
    (function () {
        const wrap = document.querySelector('.ob-plan-wrap, .kb-plan-wrap');

        if (!wrap || wrap.querySelector('.rsp-plan-label')) return;

        const label = document.createElement('div');

        label.className = 'rsp-plan-label';
        // Ubah tulisan judul di bawah ini bila perlu
        label.textContent = 'PATEN (TINDAK LANJUT :)';

        wrap.insertBefore(label, wrap.firstChild);
    })();

    // ---------------------------------------------------------
    // Tabel Resep Obat
    // ---------------------------------------------------------
    function nomorUlangResep() {
        document.querySelectorAll('#resepBody tr').forEach(function (tr, i) {
            tr.querySelector('.resep-no').textContent = i + 1;
        });
    }

    function tambahBarisResep(data) {
        const d = data || {};
        const tr = document.createElement('tr');

        tr.innerHTML =
            '<td class="resep-no" style="text-align:center;"></td>' +
            '<td><input type="text" class="resep-nama" placeholder="Nama obat" autocomplete="off"></td>' +
            '<td><select class="resep-jenis">' +
                '<option value="Generik">Generik</option>' +
                '<option value="Paten">Paten</option>' +
            '</select></td>' +
            '<td><input type="text" class="resep-dosis" placeholder="mis. 500 mg" autocomplete="off"></td>' +
            '<td><input type="text" class="resep-jumlah" placeholder="mis. 10" autocomplete="off"></td>' +
            '<td><input type="text" class="resep-aturan" placeholder="mis. 3x1 sesudah makan" autocomplete="off"></td>' +
            '<td><button type="button" class="rsp-hapus" title="Hapus" aria-label="Hapus">&times;</button></td>';

        tr.querySelector('.resep-nama').value = d.nama || '';
        tr.querySelector('.resep-jenis').value = d.jenis || 'Generik';
        tr.querySelector('.resep-dosis').value = d.dosis || '';
        tr.querySelector('.resep-jumlah').value = d.jumlah || '';
        tr.querySelector('.resep-aturan').value = d.aturan || '';

        tr.querySelector('.rsp-hapus').addEventListener('click', function () {
            const body = document.getElementById('resepBody');

            // Selalu sisakan satu baris kosong
            if (body.children.length <= 1) {
                tr.querySelectorAll('input').forEach(i => i.value = '');
                tr.querySelector('.resep-jenis').value = 'Generik';
                return;
            }

            tr.remove();
            nomorUlangResep();
        });

        document.getElementById('resepBody').appendChild(tr);
        nomorUlangResep();
    }

    function ambilResep() {
        return Array.from(document.querySelectorAll('#resepBody tr'))
            .map(function (tr) {
                return {
                    nama: tr.querySelector('.resep-nama').value.trim(),
                    jenis: tr.querySelector('.resep-jenis').value,
                    dosis: tr.querySelector('.resep-dosis').value.trim(),
                    jumlah: tr.querySelector('.resep-jumlah').value.trim(),
                    aturan: tr.querySelector('.resep-aturan').value.trim()
                };
            })
            .filter(r => r.nama !== '');
    }

    tambahBarisResep();

    // ---------------------------------------------------------
    // Cetak resep: teks Plan + daftar obat.
    // Menggantikan cetakResep() bawaan halaman setelah semua
    // script halaman selesai dimuat.
    // ---------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function () {
        window.cetakResep = function () {
            const planEl = document.querySelector('textarea[name="plan"]');
            const planText = planEl ? planEl.value.trim() : '';
            const resep = ambilResep();

            if (!planText && resep.length === 0) {
                alert('Isi bagian Plan (P) atau Resep Obat dulu sebelum mencetak resep.');
                return;
            }

            const area = document.getElementById('cetak-resep-area');
            const isiPlan = document.getElementById('resep-isi-plan');

            if (!area || !isiPlan) {
                alert('Area cetak resep tidak ditemukan di halaman ini.');
                return;
            }

            // Kotak daftar obat di lembar resep (dibuat sekali, tepat di bawah isi Plan)
            let isiObat = document.getElementById('resep-isi-obat');

            if (!isiObat) {
                isiObat = document.createElement('div');
                isiObat.id = 'resep-isi-obat';
                isiObat.className = isiPlan.className;
                isiObat.style.whiteSpace = 'pre-line';
                isiPlan.parentNode.insertBefore(isiObat, isiPlan.nextSibling);
            }

            isiPlan.innerText = planText;

            isiObat.innerText = resep.map(function (r) {
                const baris = ['R/ ' + r.nama + ' (' + r.jenis + ')'];

                const detail = [r.dosis, r.jumlah ? 'jumlah ' + r.jumlah : '']
                    .filter(Boolean).join(' - ');

                if (detail) baris.push('     ' + detail);
                if (r.aturan) baris.push('     S ' + r.aturan);

                return baris.join('\n');
            }).join('\n\n');

            const tanggal = document.getElementById('resep-tanggal');

            if (tanggal) {
                tanggal.innerText = new Date().toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }

            // Poli Obgyn memakai siapkanCetak (area dikembalikan setelah print);
            // Poli Syaraf memindahkan area ke <body> langsung.
            if (typeof siapkanCetak === 'function') {
                siapkanCetak('cetak-resep-area', 'cetak-resep-mode');
            } else {
                document.body.appendChild(area);
                document.body.classList.add('cetak-resep-mode');
            }

            window.print();
        };
    });
</script>