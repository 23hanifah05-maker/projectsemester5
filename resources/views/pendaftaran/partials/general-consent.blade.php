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
    .gc-section{background:#fbe9e9;color:#c8102e;font-weight:700;font-size:13px;padding:4px 12px;border-bottom:2px solid #c8102e;margin-bottom:12px}
    .gc-data{display:grid;grid-template-columns:1fr 1fr;gap:10px 40px;margin-bottom:16px}
    .gc-row{display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600}
    .gc-row label{flex:0 0 110px}
    .gc-row input{flex:1;min-width:0;border:1px solid #ccc;border-radius:5px;padding:6px 10px;font-size:12px;background:#fff;font-family:inherit}

    /* Butir persetujuan */
    .gc-list{border-top:1px solid #ddd}
    .gc-item{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:10px 4px;border-bottom:1px solid #ddd}
    .gc-item-text{flex:1;font-size:12px;line-height:1.5}
    .gc-item-text strong{display:block;font-size:13px;font-weight:500;margin-bottom:2px}
    .gc-item-text p{margin:0}
    .gc-keluarga{display:block;margin-top:8px;font-weight:700;font-size:12px}
    .gc-keluarga input{display:block;width:100%;margin-top:4px;border:1px solid #999;border-radius:4px;padding:6px 8px;font-family:inherit;font-size:12px}
    .gc-setuju{flex:0 0 auto;display:flex;align-items:center;gap:8px;border:1px solid #ddd;border-radius:6px;padding:6px 12px;font-size:12px;font-weight:500;cursor:pointer;background:#fff;box-shadow:0 1px 3px rgba(0,0,0,.08);white-space:nowrap}
    .gc-setuju input,.gc-banner input{width:15px;height:15px;accent-color:#c8102e}

    /* Banner */
    .gc-banner{display:flex;align-items:center;gap:8px;margin:12px 0;padding:6px 10px;background:#fbe9e9;border:1px solid #f0cccc;border-radius:4px;font-size:11px;font-weight:500;cursor:pointer}

    /* Tanda tangan */
    .gc-ttd-wrap{display:flex;flex-wrap:wrap;gap:24px;justify-content:center;align-items:flex-start;border:1px solid #ddd;border-radius:6px;padding:14px 18px}
    .gc-ttd-col{display:flex;flex-direction:column;align-items:center}
    .gc-ttd-label{font-size:11px;font-weight:600;margin-bottom:6px}
    .gc-ttd-box{border:1px solid #bbb;border-radius:4px;background:#fff;min-width:200px;min-height:110px}
    .gc-ttd-box canvas{display:block;cursor:crosshair;touch-action:none}
    .gc-link{margin-top:4px;border:0;background:none;color:#c8102e;font-size:11px;cursor:pointer;text-decoration:underline}
    .gc-ttd-waktu{display:flex;flex-direction:column;gap:10px;font-size:11px;font-weight:600;padding-top:20px}
    .gc-ttd-waktu label{display:flex;flex-direction:column;gap:3px}
    .gc-ttd-waktu input{width:130px;border:1px solid #bbb;border-radius:4px;padding:5px 8px;font-size:12px;font-family:inherit}

    /* Tombol */
    .gc-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:14px}
    .gc-btn{background:#c8102e;color:#fff;border:0;border-radius:4px;padding:6px 20px;font-size:12px;font-weight:600;cursor:pointer;font-family:inherit}
    .gc-btn:hover{background:#a50d26}

    @media (max-width:700px){
        .gc-data{grid-template-columns:1fr}
        .gc-item{flex-direction:column;align-items:flex-start}
        .gc-head img{width:44px;height:44px}
    }

    /* ===== Penyesuaian: tampilan tabel dengan kolom "Setuju" ===== */
    .gc-section{background:#fff;color:#c8102e;border:1px solid #ccc;border-bottom:0;border-radius:6px 6px 0 0;padding:8px 14px;margin-bottom:0}
    .gc-data{border:1px solid #ccc;border-top:0;border-radius:0 0 6px 6px;padding:14px;margin-bottom:14px}
    .gc-list{border:1px solid #ccc;border-radius:6px;overflow:hidden}
    .gc-item{align-items:stretch;gap:0;padding:0;border-bottom:1px solid #ccc}
    .gc-item:last-child{border-bottom:0}
    .gc-item-text{padding:10px 16px}
    .gc-keluarga input{width:55%}
    .gc-setuju{flex:0 0 130px;justify-content:center;border:0;border-left:1px solid #ccc;border-radius:0;box-shadow:none;padding:0 12px}
    .gc-banner{border-radius:4px}
    @media (max-width:700px){
        .gc-item{flex-direction:column}
        .gc-setuju{flex:0 0 auto;border-left:0;border-top:1px solid #ccc;justify-content:flex-start;padding:8px 16px}
        .gc-keluarga input{width:100%}
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
                            <label class="gc-keluarga">Nama Anggota Keluarga Yang Diperbolehkan Melihat Informasi Pasien :
                                <input type="text" id="gc-keluarga">
                            </label>
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
            <div class="gc-ttd-col">
                <div class="gc-ttd-label">Pemberi Informasi</div>
                <div class="gc-ttd-box"></div>
            </div>
            <div class="gc-ttd-col">
                <div class="gc-ttd-label">Tanda tangan Pasien/ Keluarga</div>
                <div class="gc-ttd-box">
                    <canvas id="gcTtd" width="400" height="110"></canvas>
                </div>
                <button type="button" class="gc-link no-print" onclick="gcHapusTtd()">Hapus tanda tangan</button>
            </div>
            <div class="gc-ttd-waktu">
                <label>Tanggal <input type="text" id="gc-tgl" readonly></label>
                <label>Jam <input type="text" id="gc-jam" readonly></label>
            </div>
        </div>

        <div class="gc-actions no-print">
            <button type="button" class="gc-btn" onclick="simpanConsent()">💾 Simpan</button>
            <button type="button" class="gc-btn" onclick="window.print()">🖨 Cetak</button>
        </div>

    </div>
</div>