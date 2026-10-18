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

<style>
    .gc-overlay{display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);align-items:flex-start;justify-content:center;overflow-y:auto;padding:24px 12px;font-family:'Poppins','Segoe UI',Arial,sans-serif;color:#222}
    .gc-overlay.show,.gc-overlay.active,.gc-overlay.open,.gc-overlay.is-open{display:flex}
    .gc-box{position:relative;background:#fff;width:100%;max-width:980px;border:1px solid #ddd;border-radius:10px;padding:20px 24px 24px;box-shadow:0 10px 40px rgba(0,0,0,.25)}
    .gc-close{position:absolute;top:8px;right:12px;border:0;background:none;font-size:28px;line-height:1;cursor:pointer;color:#888}
    .gc-close:hover{color:#c8102e}

    /* Kop */
    .gc-head{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:10px}
    .gc-head img{width:64px;height:64px;object-fit:contain;flex:0 0 auto}
    .gc-klinik{flex:1;text-align:center;font-size:11px;color:#444}
    .gc-klinik-nama{display:inline-block;border:1px solid #ccc;border-radius:4px;padding:5px 40px;margin-bottom:6px;font-size:14px;font-weight:700;color:#222;box-shadow:0 1px 4px rgba(0,0,0,.12)}
    .gc-klinik small{display:block;font-size:11px;line-height:1.4}
    .gc-title{text-align:center;font-size:16px;line-height:1.5;padding:10px 0;border-top:1px solid #333;border-bottom:1px solid #333;margin-bottom:14px}

    /* Data pasien */
    .gc-section{background:#fff;color:#c8102e;font-weight:700;font-size:13px;border:1px solid #ccc;border-bottom:0;border-radius:6px 6px 0 0;padding:8px 14px}
    .gc-data{display:grid;grid-template-columns:1fr 1fr;gap:10px 40px;border:1px solid #ccc;border-top:0;border-radius:0 0 6px 6px;padding:14px;margin-bottom:14px}
    .gc-row{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600}
    .gc-row label{flex:0 0 110px}
    .gc-row input{flex:1;min-width:0;border:1px solid #ccc;border-radius:5px;padding:6px 10px;font-size:12px;background:#fff;font-family:inherit}

    /* Butir persetujuan */
    .gc-list{border:1px solid #ccc;border-radius:6px;overflow:hidden}
    .gc-item{display:flex;align-items:stretch;justify-content:space-between;border-bottom:1px solid #ccc}
    .gc-item:last-child{border-bottom:0}
    .gc-item-text{flex:1;padding:10px 16px;font-size:12px;line-height:1.5}
    .gc-item-text strong{display:block;font-size:13px;font-weight:500;margin-bottom:2px}
    .gc-item-text p{margin:0}
    .gc-setuju{flex:0 0 130px;display:flex;align-items:center;justify-content:center;gap:8px;border-left:1px solid #ccc;padding:0 12px;font-size:12px;font-weight:500;cursor:pointer;background:#fff;white-space:nowrap}
    .gc-setuju input,.gc-banner input{width:15px;height:15px;accent-color:#c8102e}

    /* Anggota keluarga (bisa lebih dari 1) */
    .gc-keluarga{display:block;margin-top:8px;font-weight:700;font-size:12px}
    .gc-keluarga-row{display:flex;gap:6px;margin-top:6px}
    .gc-keluarga-input{flex:0 1 55%;border:1px solid #999;border-radius:4px;padding:6px 8px;font-family:inherit;font-size:12px}
    .gc-keluarga-hapus{border:0;background:#fbe9e9;color:#c8102e;border-radius:4px;width:28px;cursor:pointer;font-size:16px}

    /* Banner */
    .gc-banner{display:flex;align-items:center;gap:8px;margin:12px 0;padding:6px 10px;background:#fbe9e9;border:1px solid #f0cccc;border-radius:4px;font-size:11px;font-weight:500;cursor:pointer}

    /* Tanda tangan */
    .gc-ttd-wrap{display:flex;flex-wrap:wrap;gap:24px;justify-content:center;align-items:flex-start;border:1px solid #ddd;border-radius:6px;padding:14px 18px}
    .gc-ttd-col{display:flex;flex-direction:column;align-items:center}
    .gc-ttd-label{font-size:11px;font-weight:600;margin-bottom:6px}
    .gc-ttd-box{border:1px solid #bbb;border-radius:4px;background:#fff}
    .gc-canvas{display:block;cursor:crosshair;touch-action:none}
    .gc-link{margin-top:4px;border:0;background:none;color:#c8102e;font-size:11px;cursor:pointer;text-decoration:underline;padding:0}
    .gc-keluarga .gc-link{display:block;margin-top:6px}
    .gc-nama-terang{margin-top:8px;font-size:12px;font-weight:600}
    .gc-nama-terang input{border:0;border-bottom:1px solid #000;text-align:center;width:180px;font-family:inherit;font-size:12px;font-weight:600;outline:none;background:transparent}
    .gc-ttd-waktu{display:flex;flex-direction:column;gap:10px;font-size:11px;font-weight:600;padding-top:20px}
    .gc-ttd-waktu label{display:flex;flex-direction:column;gap:3px}
    .gc-ttd-waktu input{width:130px;border:1px solid #bbb;border-radius:4px;padding:5px 8px;font-size:12px;font-family:inherit}

    /* Tombol */
    .gc-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:14px}
    .gc-btn{background:#c8102e;color:#fff;border:0;border-radius:4px;padding:6px 20px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit}
    .gc-btn:hover{background:#a50d26}
    .gc-btn:disabled{opacity:.6;cursor:not-allowed}

    @media (max-width:700px){
        .gc-data{grid-template-columns:1fr}
        .gc-item{flex-direction:column}
        .gc-setuju{flex:0 0 auto;border-left:0;border-top:1px solid #ccc;justify-content:flex-start;padding:8px 16px}
        .gc-head img{width:44px;height:44px}
        .gc-keluarga-input{flex:1}
    }
    @media print{
        .no-print{display:none !important}
        .gc-overlay{position:static;display:block;background:none;padding:0}
        .gc-box{box-shadow:none;border:0;max-width:100%}
    }
</style>

<div class="gc-overlay" id="consentModal">
    <div class="gc-box" id="consentPaper">

        <button type="button" class="gc-close no-print" onclick="tutupConsent()">&times;</button>

        <div class="gc-head">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <div class="gc-klinik">
                <div class="gc-klinik-nama">KLINIK MERAH PUTIH</div>
                <small>Jl. Ronggowarsito No.98a, Ngadirejo, Central Karang, Kec. Ngawi, Kabupaten Ngawi, Jawa Timur</small>
            </div>
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>

        <div class="gc-title">
            FORMULIR PEMBERIAN INFORMASI DAN PERSETUJUAN UMUM<br>(GENERAL CONSENT) RAWAT JALAN
        </div>

        <div class="gc-section">Data Pasien</div>
        <div class="gc-data">
            <div class="gc-row"><label>No RM</label><input type="text" id="gc-norm" readonly></div>
            <div class="gc-row"><label>Tempat, Tgl Lahir</label><input type="text" id="gc-ttl" readonly></div>
            <div class="gc-row"><label>Nama</label><input type="text" id="gc-nama" readonly></div>
            <div class="gc-row"><label>Umur</label><input type="text" id="gc-umur" readonly></div>
            <div class="gc-row"><label>Jenis Kelamin</label><input type="text" id="gc-jk" readonly></div>
            <div class="gc-row"><label>No Hp</label><input type="text" id="gc-hp" readonly></div>
        </div>

        <div class="gc-list">
            @foreach ($butir as $i => $b)
                <div class="gc-item">
                    <div class="gc-item-text">
                        <strong>{{ $i + 1 }}. {{ $b[0] }}</strong>
                        <p>{{ $b[1] }}</p>

                        @if ($i === 2)
                            <div class="gc-keluarga">
                                Nama Anggota Keluarga Yang Diperbolehkan Melihat Informasi Pasien :
                                <div id="gc-keluarga-list">
                                    <div class="gc-keluarga-row">
                                        <input type="text" class="gc-keluarga-input" placeholder="Nama anggota keluarga">
                                        <button type="button" class="gc-keluarga-hapus no-print" style="display:none" onclick="gcHapusKeluarga(this)">&times;</button>
                                    </div>
                                </div>
                                <button type="button" class="gc-link no-print" onclick="gcTambahKeluarga()">+ Tambah anggota keluarga</button>
                            </div>
                        @endif
                    </div>
                    <label class="gc-setuju"><input type="checkbox" class="gc-cek"> Setuju</label>
                </div>
            @endforeach
        </div>

        <label class="gc-banner">
            <input type="checkbox" id="gc-semua">
            Dengan ini saya menyatakan bahwa seluruh informasi di atas telah saya baca, pahami, dan saya setuju dengan sebenar-benarnya
        </label>

        <div class="gc-ttd-wrap">
            {{-- Pemberi Informasi --}}
            <div class="gc-ttd-col">
                <div class="gc-ttd-label">Pemberi Informasi</div>
                <div class="gc-ttd-box">
                    <canvas id="gcTtdPemberi" class="gc-canvas" width="220" height="110"></canvas>
                </div>
                <button type="button" class="gc-link no-print" onclick="gcHapusTtd('gcTtdPemberi')">Hapus tanda tangan</button>
                <div class="gc-nama-terang">( <input type="text" id="gc-nama-pemberi" placeholder="Nama terang"> )</div>
            </div>

            {{-- Pasien / Keluarga --}}
            <div class="gc-ttd-col">
                <div class="gc-ttd-label">Tanda tangan Pasien/ Keluarga</div>
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

<script>
/* =========================================================
   BUKA / TUTUP MODAL
   Contoh pemakaian:
   bukaConsent({norm:'E000014', nama:'Siti', ttl:'Ngawi, 01-01-1990', umur:'36 tahun', jk:'Perempuan', hp:'0812...'})
   Kalau kamu sudah punya fungsi bukaConsent/tutupConsent sendiri, hapus versi di bawah ini.
   ========================================================= */
function bukaConsent(d = {}) {
    document.getElementById('gc-norm').value = d.norm || '';
    document.getElementById('gc-nama').value = d.nama || '';
    document.getElementById('gc-ttl').value = d.ttl || '';
    document.getElementById('gc-umur').value = d.umur || '';
    document.getElementById('gc-jk').value = d.jk || '';
    document.getElementById('gc-hp').value = d.hp || '';


    const sekarang = new Date();
    const pad = n => String(n).padStart(2, '0');

    document.getElementById('gc-tgl').value =
        pad(sekarang.getDate()) + '/' +
        pad(sekarang.getMonth() + 1) + '/' +
        sekarang.getFullYear();

    document.getElementById('gc-jam').value =
        pad(sekarang.getHours()) + '.' +
        pad(sekarang.getMinutes());

    document.getElementById('consentModal').classList.add('show');
}
function tutupConsent() {
    document.getElementById('consentModal').classList.remove('show');
}

/* Kosongkan form tiap kali modal dibuka */
function gcReset() {
    document.querySelectorAll('.gc-cek').forEach(c => c.checked = false);
    document.getElementById('gc-semua').checked = false;

    const list = document.getElementById('gc-keluarga-list');
    const rows = list.querySelectorAll('.gc-keluarga-row');
    rows.forEach((r, i) => { if (i > 0) r.remove(); });
    list.querySelector('input').value = '';
    gcRefreshKeluarga();

    document.getElementById('gc-nama-pemberi').value = '';
    document.getElementById('gc-nama-pasien').value = '';
    gcHapusTtd('gcTtdPemberi');
    gcHapusTtd('gcTtd');
}

/* =========================================================
   ANGGOTA KELUARGA (boleh lebih dari 1)
   ========================================================= */
function gcRefreshKeluarga() {
    const rows = document.querySelectorAll('#gc-keluarga-list .gc-keluarga-row');
    rows.forEach(r => {
        r.querySelector('.gc-keluarga-hapus').style.display = rows.length > 1 ? 'inline-block' : 'none';
    });
}
function gcTambahKeluarga() {
    const list = document.getElementById('gc-keluarga-list');
    const row = list.querySelector('.gc-keluarga-row').cloneNode(true);
    row.querySelector('input').value = '';
    list.appendChild(row);
    gcRefreshKeluarga();
    row.querySelector('input').focus();
}
function gcHapusKeluarga(btn) {
    btn.closest('.gc-keluarga-row').remove();
    gcRefreshKeluarga();
}
function gcAmbilKeluarga() {
    return Array.from(document.querySelectorAll('.gc-keluarga-input'))
        .map(i => i.value.trim())
        .filter(Boolean);
}

/* =========================================================
   SIGNATURE PAD (tanpa library) - dipakai 2 canvas
   ========================================================= */
window.gcPad = window.gcPad || {};

function gcInitPad(id) {
    const canvas = document.getElementById(id);
    const ctx = canvas.getContext('2d');
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.strokeStyle = '#000';

    const state = { canvas: canvas, ctx: ctx, drawing: false, dirty: false };
    window.gcPad[id] = state;

    function pos(e) {
        const r = canvas.getBoundingClientRect();
        return {
            x: (e.clientX - r.left) * (canvas.width / r.width),
            y: (e.clientY - r.top) * (canvas.height / r.height)
        };
    }

    canvas.addEventListener('pointerdown', function (e) {
        state.drawing = true;
        canvas.setPointerCapture(e.pointerId);
        const p = pos(e);
        ctx.beginPath();
        ctx.moveTo(p.x, p.y);
        ctx.lineTo(p.x + 0.1, p.y + 0.1);
        ctx.stroke();
        state.dirty = true;
    });
    canvas.addEventListener('pointermove', function (e) {
        if (!state.drawing) return;
        const p = pos(e);
        ctx.lineTo(p.x, p.y);
        ctx.stroke();
    });
    ['pointerup', 'pointerleave', 'pointercancel'].forEach(function (ev) {
        canvas.addEventListener(ev, function () { state.drawing = false; });
    });
}

function gcHapusTtd(id) {
    const s = window.gcPad[id];
    if (!s) return;
    s.ctx.clearRect(0, 0, s.canvas.width, s.canvas.height);
    s.dirty = false;
}
function gcAmbilTtd(id) {
    return window.gcPad[id] && window.gcPad[id].dirty
        ? window.gcPad[id].canvas.toDataURL('image/png')
        : '';
}

/* =========================================================
   CHECKBOX "SEMUA SETUJU"
   ========================================================= */
document.addEventListener('DOMContentLoaded', function () {
    gcInitPad('gcTtdPemberi');
    gcInitPad('gcTtd');
    gcRefreshKeluarga();

    document.getElementById('gc-semua').addEventListener('change', function () {
        document.querySelectorAll('.gc-cek').forEach(c => c.checked = this.checked);
    });
});

/* =========================================================
   VALIDASI + SIMPAN KE DATABASE
   ========================================================= */
function gcValidasi() {
    const cek = document.querySelectorAll('.gc-cek');
    if (!Array.from(cek).every(c => c.checked)) {
        alert('Semua butir persetujuan harus dicentang "Setuju".'); return false;
    }
    if (!document.getElementById('gc-semua').checked) {
        alert('Pernyataan persetujuan di bawah butir belum dicentang.'); return false;
    }
    if (!gcAmbilTtd('gcTtdPemberi')) {
        alert('Tanda tangan Pemberi Informasi belum diisi.'); return false;
    }
    if (!document.getElementById('gc-nama-pemberi').value.trim()) {
        alert('Nama terang Pemberi Informasi belum diisi.'); return false;
    }
    if (!gcAmbilTtd('gcTtd')) {
        alert('Tanda tangan Pasien/Keluarga belum diisi.'); return false;
    }
    if (!document.getElementById('gc-nama-pasien').value.trim()) {
        alert('Nama terang Pasien/Keluarga belum diisi.'); return false;
    }
    return true;
}

function simpanConsent() {
    if (!gcValidasi()) return;

    const noRm = document.getElementById('gc-norm').value.trim();
    if (!noRm) {
        alert('No. RM kosong. Pastikan modal dibuka lewat bukaConsent({norm: ...}).');
        return;
    }

    const data = {
        no_rm:              noRm,
        anggota_keluarga:   gcAmbilKeluarga(),                     // array nama
        ttd_pemberi:        gcAmbilTtd('gcTtdPemberi'),            // base64 PNG
        nama_pemberi:       document.getElementById('gc-nama-pemberi').value.trim(),
        ttd_pasien:         gcAmbilTtd('gcTtd'),                   // base64 PNG
        nama_penandatangan: document.getElementById('gc-nama-pasien').value.trim(),
        tanggal:            document.getElementById('gc-tgl').value,
        jam:                document.getElementById('gc-jam').value
    };

    const tombol = document.getElementById('gc-btn-simpan');
    tombol.disabled = true;

    fetch("{{ url('/general-consent') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(async function (r) {
        const j = await r.json().catch(function () { return {}; });
        if (!r.ok) {
            const pesan = j.errors
                ? Object.values(j.errors).flat().join('\n')
                : (j.message || 'Gagal menyimpan (kode ' + r.status + ')');
            throw new Error(pesan);
        }
        return j;
    })
    .then(function () {
        alert('General Consent berhasil disimpan.');
        tutupConsent();
    })
    .catch(function (err) {
        alert(err.message);
    })
    .finally(function () {
        tombol.disabled = false;
    });
}
</script>