@php
    $butir = [
        ['Hak dan Kewajiban pasien', 'Saya menyatakan telah menerima dan memahami informasi mengenai hak serta kewajiban saya sebagai pasien di Klinik Merah Putih melalui petugas, leaflet, atau banner.'],
        ['Persetujuan Pelayanan Medis', 'Saya memberikan persetujuan dan kuasa kepada Klinik Merah Putih, dokter, perawat, serta tenaga kesehatan untuk memberikan asuhan medis, pemeriksaan fisik, laboratorium, radiologi (termasuk X-ray), tindakan medis rutin, penyuntikan, pemberian obat-obatan, dan pemasangan alat kesehatan sesuai kebutuhan perawatan saya.'],
        ['Pelepasan Informasi Medis', 'Saya memahami bahwa data medis saya dijamin kerahasiaannya. Saya menyetujui pelepasan informasi kesehatan, diagnosis, dan hasil pemeriksaan medis saya kepada tenaga kesehatan yang merawat, pihak penjamin atau asuransi, serta anggota keluarga saya.'],
        ['Pengajuan Keluhan', 'Saya telah menerima informasi mengenai tata cara pengajuan keluhan terkait pelayanan klinik dan setuju untuk mengikuti prosedur yang berlaku.'],
        ['Tata Tertib dan Barang Berharga', 'Saya dan keluarga berjanji mematuhi tata tertib Klinik Merah Putih, tidak membawa perhiasan atau barang berharga berlebihan (atau menitipkannya sesuai prosedur jika membawa), serta tidak mengambil, menyimpan, atau membagikan foto, video, maupun dokumen aktivitas pelayanan tanpa izin tertulis.'],
        ['Kewajiban Pembayaran', 'Saya bertanggung jawab melunasi seluruh biaya pelayanan medis yang diberikan sesuai ketentuan tarif Klinik Merah Putih. Apabila menggunakan jaminan atau asuransi, saya bersedia membayarkan sisa biaya yang tidak ditanggung oleh pihak penjamin.'],
        ['Pernyataan Persetujuan', 'Saya menyetujui seluruh pernyataan dalam formulir ini dan menandatanganinya secara sadar tanpa paksaan dari pihak mana pun.'],
    ];
@endphp

{{-- CSS khusus modal. Tabel, kotak data pasien, dan garis tipis ada di public/css/general-consent.css --}}
<style>
    .gc-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,.55);
        align-items: flex-start;
        justify-content: center;
        overflow-y: auto;
        padding: 24px 12px;
        font-family: 'Poppins','Segoe UI',Arial,sans-serif;
        color: #222;
    }
    .gc-overlay.show { display: flex; }

    .gc-paper {
    position: relative;
    background: #fff;
    width: 100%;
    max-width: 980px;
    border-radius: 12px;
    padding: 18px;                 /* jarak antara tepi modal dan kotak luar */
    box-shadow: 0 10px 40px rgba(0,0,0,.25);
}

/* KOTAK LUAR FORMULIR (garis tepi seperti desain) */
    .gc-frame {
    border: 1px solid var(--gc-line, #d6d6d6);
    border-radius: 10px;
    padding: 16px 18px 18px;
}

    .gc-close {
        position: absolute;
        top: 8px;
        right: 12px;
        border: 0;
        background: none;
        font-size: 28px;
        line-height: 1;
        cursor: pointer;
        color: #888;
    }
    .gc-close:hover { color: #c8102e; }

    .gc-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 10px;
    }
    .gc-head img { width: 64px; height: 64px; object-fit: contain; flex: 0 0 auto; }

    .gc-klinik { flex: 1; text-align: center; font-size: 11px; color: #444; }
    .gc-klinik-nama {
        display: inline-block;
        border: 1px solid var(--gc-line, #d6d6d6);
        border-radius: 4px;
        padding: 5px 40px;
        margin-bottom: 6px;
        font-size: 14px;
        font-weight: 700;
        color: #222;
        box-shadow: 0 1px 4px rgba(0,0,0,.12);
    }
    .gc-klinik small { display: block; font-size: 11px; line-height: 1.4; }

    .gc-title {
        text-align: center;
        font-size: 16px;
        line-height: 1.5;
        padding: 10px 0;
        border-top: 1px solid var(--gc-line, #d6d6d6);
        border-bottom: 1px solid var(--gc-line, #d6d6d6);
        margin-bottom: margin: 0 -18px 4px;
    }

    /* Pada modal, kotak/list tidak perlu margin samping tambahan */
    .gc-paper .gc-section-title { margin-left: 0; }
    .gc-paper .gc-box,
    .gc-paper .gc-list { margin-left: 0; margin-right: 0; }

    /* Nama anggota keluarga */
    .gc-keluarga-row { display: flex; gap: 6px; margin-top: 6px; margin-left: 6px; }
    .gc-keluarga-input {
        flex: 0 1 55%;
        border: 1px solid var(--gc-line, #d6d6d6);
        border-radius: 6px;
        padding: 6px 10px;
        font-family: inherit;
        font-size: 12px;
    }
    .gc-keluarga-hapus {
        border: 0;
        background: #fbe9e9;
        color: #c8102e;
        border-radius: 4px;
        width: 28px;
        cursor: pointer;
        font-size: 16px;
    }

    .gc-banner {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 12px 0;
        padding: 6px 10px;
        background: #fbe9e9;
        border: 1px solid #f0cccc;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
    }
    .gc-banner input { width: 15px; height: 15px; accent-color: #c8102e; }

    /* Tanda tangan */
    .gc-ttd-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        justify-content: center;
        align-items: flex-start;
        border: 1px solid var(--gc-line, #d6d6d6);
        border-radius: 10px;
        padding: 14px 18px;
    }
    .gc-ttd-col { display: flex; flex-direction: column; align-items: center; }
    .gc-ttd-label { font-size: 11px; font-weight: 600; margin-bottom: 6px; }
    .gc-ttd-box { border: 1px solid var(--gc-line, #d6d6d6); border-radius: 6px; background: #fff; }
    .gc-canvas { display: block; cursor: crosshair; touch-action: none; }

    .gc-link {
        margin-top: 4px;
        border: 0;
        background: none;
        color: #c8102e;
        font-size: 11px;
        cursor: pointer;
        text-decoration: underline;
        padding: 0;
    }
    .gc-text .gc-link { display: block; margin: 6px 0 0 6px; }

    .gc-nama-terang { margin-top: 8px; font-size: 12px; font-weight: 600; }
    .gc-nama-terang input {
        border: 0;
        border-bottom: 1px solid #000;
        text-align: center;
        width: 180px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        outline: none;
        background: transparent;
    }

    .gc-ttd-waktu {
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 11px;
        font-weight: 600;
        padding-top: 20px;
    }
    .gc-ttd-waktu label { display: flex; flex-direction: column; gap: 3px; }
    .gc-ttd-waktu input {
        width: 130px;
        border: 1px solid var(--gc-line, #d6d6d6);
        border-radius: 6px;
        padding: 5px 8px;
        font-size: 12px;
        font-family: inherit;
    }

    .gc-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 14px; }
    .gc-btn {
        background: #c8102e;
        color: #fff;
        border: 0;
        border-radius: 4px;
        padding: 6px 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
    }
    .gc-btn:hover { background: #a50d26; }
    .gc-btn:disabled { opacity: .6; cursor: not-allowed; }

    @media (max-width: 700px) {
        .gc-head img { width: 44px; height: 44px; }
        .gc-keluarga-input { flex: 1; }
    }

    @media print {
    @page { size: A4; margin: 6mm; }

    html, body {
        background: #fff !important;
        height: auto !important;
        overflow: visible !important;
    }

    body > *:not(#consentModal) { display: none !important; }

    #consentModal {
        display: block !important;
        position: static !important;
        background: none !important;
        padding: 0 !important;
        overflow: visible !important;
        height: auto !important;
    }

    .gc-paper {
        box-shadow: none !important;
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        border-radius: 0 !important;
    }

    .no-print { display: none !important; }

    /* Perkecil seluruh isi agar muat 1 halaman */
    .gc-frame {
        zoom: .72;
        padding: 10px 12px !important;
    }

    /* Rapatkan jarak */
    .gc-head { margin-bottom: 4px !important; }
    .gc-title { padding: 6px 0 !important; }
    .gc-text { padding: 6px 10px !important; }
    .gc-banner { margin: 6px 0 !important; }
    .gc-ttd-wrap { padding: 8px 12px !important; }
    .gc-canvas { height: 90px !important; }

    /* Nama anggota keluarga kosong tidak perlu mengambil tempat */
    .gc-item, .gc-ttd-wrap { break-inside: avoid; }

    * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<div class="gc-overlay" id="consentModal">
    <div class="gc-paper" id="consentPaper">
        <button type="button" class="gc-close no-print" onclick="tutupConsent()">&times;</button>
            <div class="gc-frame"> 

        <div class="gc-head">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <div class="gc-klinik">
                <div class="gc-klinik-nama">KLINIK MERAH PUTIH</div>
                <small>Jl. Ronggowarsito No.98a, Ngadirejo, Central Karang, Kec. Ngawi, Kabupaten Ngawi, Jawa Timur</small>
            </div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>

        <div class="gc-title">
            FORMULIR PEMBERIAN INFORMASI DAN PERSETUJUAN UMUM<br>
            (GENERAL CONSENT) RAWAT JALAN
        </div>

        {{-- DATA PASIEN --}}
        <div class="gc-section-title">Data Pasien</div>

        <div class="gc-box gc-pasien">
            <div class="gc-field">
                <label>No RM</label>
                <input type="text" id="gc-norm" readonly>
            </div>
            <div class="gc-field">
                <label>Tempat, Tgl Lahir</label>
                <input type="text" id="gc-ttl" readonly>
            </div>
            <div class="gc-field">
                <label>Nama</label>
                <input type="text" id="gc-nama" readonly>
            </div>
            <div class="gc-field">
                <label>Umur</label>
                <input type="text" id="gc-umur" readonly>
            </div>
            <div class="gc-field">
                <label>Jenis Kelamin</label>
                <input type="text" id="gc-jk" readonly>
            </div>
            <div class="gc-field">
                <label>No Hp</label>
                <input type="text" id="gc-hp" readonly>
            </div>
        </div>

        {{-- DAFTAR PERSETUJUAN (garis tipis horizontal + vertikal) --}}
        <div class="gc-list">
            @foreach ($butir as $i => $b)
                <div class="gc-item">
                    <div class="gc-text">
                        <h4>{{ $i + 1 }}. {{ $b[0] }}</h4>
                        <p>{{ $b[1] }}</p>

                        @if ($i === 2)
                            <label class="gc-label-kecil">
                                Nama Anggota Keluarga Yang Diperbolehkan Melihat Informasi Pasien:
                            </label>
                            <div id="gc-keluarga-list">
                                <div class="gc-keluarga-row">
                                    <input type="text" class="gc-keluarga-input" placeholder="Nama anggota keluarga">
                                    <button type="button" class="gc-keluarga-hapus no-print" style="display:none" onclick="gcHapusKeluarga(this)">&times;</button>
                                </div>
                            </div>
                            <button type="button" class="gc-link no-print" onclick="gcTambahKeluarga()">+ Tambah anggota keluarga</button>
                        @endif
                    </div>

                    <label class="gc-check">
                        <input type="checkbox" class="gc-cek">
                        Setuju
                    </label>
                </div>
            @endforeach
        </div>

        <label class="gc-banner">
            <input type="checkbox" id="gc-semua">
            Dengan ini saya menyatakan bahwa seluruh informasi di atas telah saya baca, pahami, dan saya setuju dengan sebenar-benarnya.
        </label>

        {{-- TANDA TANGAN --}}
        <div class="gc-ttd-wrap">
            <div class="gc-ttd-col">
                <div class="gc-ttd-label">Pemberi Informasi</div>
                <div class="gc-ttd-box">
                    <canvas id="gcTtdPemberi" class="gc-canvas" width="220" height="110"></canvas>
                </div>
                <button type="button" class="gc-link no-print" onclick="gcHapusTtd('gcTtdPemberi')">Hapus tanda tangan</button>
                <div class="gc-nama-terang">( <input type="text" id="gc-nama-pemberi" placeholder="Nama terang"> )</div>
            </div>

            <div class="gc-ttd-col">
                <div class="gc-ttd-label">Tanda tangan Pasien/Keluarga</div>
                <div class="gc-ttd-box">
                    <canvas id="gcTtd" class="gc-canvas" width="400" height="110"></canvas>
                </div>
                <button type="button" class="gc-link no-print" onclick="gcHapusTtd('gcTtd')">Hapus tanda tangan</button>
                <div class="gc-nama-terang">( <input type="text" id="gc-nama-pasien" placeholder="Nama terang"> )</div>
            </div>

            <div class="gc-ttd-waktu">
                <label>Tanggal <input type="text" id="gc-tgl" readonly></label>
                <label>Jam <input type="text" id="gc-jam" readonly></label>
            </div>
        </div>

        <div class="gc-actions no-print">
            <button type="button" class="gc-btn" id="gc-btn-simpan" onclick="simpanConsent()">Simpan</button>
            <button type="button" class="gc-btn" onclick="window.print()">Cetak</button>
        </div>
    </div>
</div>
</div>

<script>
(function () {
    'use strict';

    let gcDataPasien = {};
    window.gcPad = window.gcPad || {};

    function gcIsiData(data) {
        document.getElementById('gc-norm').value = data.norm || '';
        document.getElementById('gc-nama').value = data.nama || '';
        document.getElementById('gc-ttl').value = data.ttl || '';
        document.getElementById('gc-umur').value = data.umur || '';
        document.getElementById('gc-jk').value = data.jk || '';
        document.getElementById('gc-hp').value = data.hp || '';

        const sekarang = new Date();
        const pad = n => String(n).padStart(2, '0');

        document.getElementById('gc-tgl').value =
            pad(sekarang.getDate()) + '/' +
            pad(sekarang.getMonth() + 1) + '/' +
            sekarang.getFullYear();

        document.getElementById('gc-jam').value =
            pad(sekarang.getHours()) + '.' +
            pad(sekarang.getMinutes());
    }

    window.bukaConsent = function (data = {}) {
        gcDataPasien = data || {};
        gcIsiData(gcDataPasien);
        gcReset();

        document.getElementById('consentModal').classList.add('show');

        // Pastikan canvas siap setelah modal ditampilkan.
        gcInitPad('gcTtdPemberi');
        gcInitPad('gcTtd');
    };

    window.tutupConsent = function () {
        document.getElementById('consentModal').classList.remove('show');
    };

    function gcReset() {
        document.querySelectorAll('.gc-cek').forEach(c => c.checked = false);
        document.getElementById('gc-semua').checked = false;

        const list = document.getElementById('gc-keluarga-list');
        if (list) {
            const rows = list.querySelectorAll('.gc-keluarga-row');
            rows.forEach((row, i) => {
                if (i > 0) row.remove();
            });

            const input = list.querySelector('input');
            if (input) input.value = '';

            gcRefreshKeluarga();
        }

        document.getElementById('gc-nama-pemberi').value = '';
        document.getElementById('gc-nama-pasien').value = '';

        gcHapusTtd('gcTtdPemberi');
        gcHapusTtd('gcTtd');
    }

    // Dipanggil oleh tombol Reset di create.blade.php
    window.gcReset = gcReset;

    function gcRefreshKeluarga() {
        const rows = document.querySelectorAll('#gc-keluarga-list .gc-keluarga-row');

        rows.forEach(row => {
            const tombol = row.querySelector('.gc-keluarga-hapus');
            if (tombol) {
                tombol.style.display = rows.length > 1 ? 'inline-block' : 'none';
            }
        });
    }

    window.gcTambahKeluarga = function () {
        const list = document.getElementById('gc-keluarga-list');
        const awal = list.querySelector('.gc-keluarga-row');

        if (!awal) return;

        const row = awal.cloneNode(true);
        row.querySelector('input').value = '';
        list.appendChild(row);

        gcRefreshKeluarga();
        row.querySelector('input').focus();
    };

    window.gcHapusKeluarga = function (button) {
        const row = button.closest('.gc-keluarga-row');
        if (row) row.remove();
        gcRefreshKeluarga();
    };

    function gcAmbilKeluarga() {
        return Array.from(document.querySelectorAll('.gc-keluarga-input'))
            .map(input => input.value.trim())
            .filter(Boolean);
    }

    function gcInitPad(id) {
        const canvas = document.getElementById(id);
        if (!canvas) return;

        // Hindari menambahkan event listener berkali-kali.
        if (canvas.dataset.gcInitialized === '1') return;
        canvas.dataset.gcInitialized = '1';

        const ctx = canvas.getContext('2d');
        ctx.lineWidth = 2;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        ctx.strokeStyle = '#000';

        const state = {
            canvas: canvas,
            ctx: ctx,
            drawing: false,
            dirty: false
        };

        window.gcPad[id] = state;

        function posisi(event) {
            const rect = canvas.getBoundingClientRect();

            return {
                x: (event.clientX - rect.left) * (canvas.width / rect.width),
                y: (event.clientY - rect.top) * (canvas.height / rect.height)
            };
        }

        canvas.addEventListener('pointerdown', function (event) {
            event.preventDefault();
            state.drawing = true;
            canvas.setPointerCapture(event.pointerId);

            const p = posisi(event);
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.x + 0.1, p.y + 0.1);
            ctx.stroke();

            state.dirty = true;
        });

        canvas.addEventListener('pointermove', function (event) {
            if (!state.drawing) return;

            event.preventDefault();
            const p = posisi(event);
            ctx.lineTo(p.x, p.y);
            ctx.stroke();
            state.dirty = true;
        });

        ['pointerup', 'pointerleave', 'pointercancel'].forEach(function (eventName) {
            canvas.addEventListener(eventName, function () {
                state.drawing = false;
            });
        });
    }

    window.gcHapusTtd = function (id) {
        const state = window.gcPad[id];
        if (!state) return;

        state.ctx.clearRect(0, 0, state.canvas.width, state.canvas.height);
        state.dirty = false;
    };

    function gcAmbilTtd(id) {
        const state = window.gcPad[id];

        return state && state.dirty
            ? state.canvas.toDataURL('image/png')
            : '';
    }

    function gcValidasi() {
        const semuaButir = Array.from(document.querySelectorAll('.gc-cek'));

        if (!semuaButir.every(checkbox => checkbox.checked)) {
            alert('Semua butir persetujuan harus dicentang "Setuju".');
            return false;
        }

        if (!document.getElementById('gc-semua').checked) {
            alert('Pernyataan persetujuan di bawah butir belum dicentang.');
            return false;
        }

        if (!gcAmbilTtd('gcTtdPemberi')) {
            alert('Tanda tangan Pemberi Informasi belum diisi.');
            return false;
        }

        if (!document.getElementById('gc-nama-pemberi').value.trim()) {
            alert('Nama terang Pemberi Informasi belum diisi.');
            return false;
        }

        if (!gcAmbilTtd('gcTtd')) {
            alert('Tanda tangan Pasien/Keluarga belum diisi.');
            return false;
        }

        if (!document.getElementById('gc-nama-pasien').value.trim()) {
            alert('Nama terang Pasien/Keluarga belum diisi.');
            return false;
        }

        return true;
    }

    window.simpanConsent = function () {
        if (!gcValidasi()) return;

        const noRm = document.getElementById('gc-norm').value.trim();

        if (!noRm) {
            alert('No. RM kosong. Isi data pasien terlebih dahulu, lalu buka kembali General Consent.');
            return;
        }

        /*
         * Simpan hasil consent ke hidden input di form pendaftaran.
         * Tidak melakukan fetch terpisah karena pasien mungkin belum tersimpan
         * ke database pada saat General Consent diisi.
         */
        const form = document.getElementById('formPendaftaran');

        if (!form) {
            alert('Form pendaftaran tidak ditemukan.');
            return;
        }

        function setHidden(name, value) {
            let input = form.querySelector('[name="' + name + '"]');

            if (!input) {
                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;
                form.appendChild(input);
            }

            input.value = value;
        }

        setHidden('general_consent', '1');
        setHidden('gc_ttd_pemberi', gcAmbilTtd('gcTtdPemberi'));
        setHidden('gc_nama_pemberi', document.getElementById('gc-nama-pemberi').value.trim());
        setHidden('gc_ttd_pasien', gcAmbilTtd('gcTtd'));
        setHidden('gc_nama_penandatangan', document.getElementById('gc-nama-pasien').value.trim());
        setHidden('gc_anggota_keluarga', JSON.stringify(gcAmbilKeluarga()));
        setHidden('gc_tanggal', document.getElementById('gc-tgl').value);
        setHidden('gc_jam', document.getElementById('gc-jam').value);

        const tombolConsent = document.getElementById('btnConsent');

        if (tombolConsent) {
            tombolConsent.textContent = '✔ General Consent';
            tombolConsent.classList.add('sudah');
        }

        window.tutupConsent();
        alert('General Consent sudah diisi. Selanjutnya, simpan pendaftaran pasien.');
    };

    document.addEventListener('DOMContentLoaded', function () {
         document.body.appendChild(document.getElementById('consentModal'));

        gcInitPad('gcTtdPemberi');
        gcInitPad('gcTtd');
        gcRefreshKeluarga();

        const semua = document.getElementById('gc-semua');

        if (semua) {
            semua.addEventListener('change', function () {
                document.querySelectorAll('.gc-cek').forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    });
})();
</script>