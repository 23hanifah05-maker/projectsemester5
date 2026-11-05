@php
    $butir = [
        [
            'Hak dan Kewajiban Sebagai Pasien',
            "<div class='gc-sub-group'>
                <span class='gc-sub-item'>a) Saya memiliki hak untuk mengambil bagian dalam keputusan mengenai penyakit saya dan dalam hal perawatan medis dan rencana pengobatan.</span>
                <span class='gc-sub-item'>b) Saya telah mendapat informasi tentang Hak dan Kewajiban pasien di Klinik Merah Putih melalui leaflet dan banner yang disediakan oleh petugas.</span>
            </div>"
        ],
        [
            'Persetujuan Pelayanan',
            'Saya menyetujui dan memberikan persetujuan untuk mendapatkan pelayanan di Klinik Merah Putih dan dengan ini saya meminta dan memberikan kuasa kepada Klinik Merah Putih, dokter, perawat, dan tenaga kesehatan lainnya untuk memberikan asuhan keperawatan, pemeriksaan fisik yang dilakukan oleh dokter dan perawat dan melakukan prosedur diagnostik radiologi dan / atau terapi dan tatalaksana sesuai pertimbangan dokter yang diperlukan atau disarankan pada perawatan saya. Hal ini mencakup seluruh pemeriksaan dan prosedur diagnostik rutin termasuk X-ray, pemberian dan / atau tindakan medis serta penyuntikan (intramuskular, intravena, dan prosedur invasif lainnya), produk farmasi dan obat-obatan, pemasangan alat kesehatan (kecuali yang membutuhkan persetujuan khusus (tertulis) dan pengambilan darah untuk pemeriksaan laboratorium termasuk pemeriksaan status HIV / AIDS atau pemeriksaan patologi).'
        ],
        [
            'Pelepasan Informasi',
            "<div class='gc-sub-group'>
                <span class='gc-sub-item'>i. Saya memahami informasi yang ada di dalam diri saya termasuk diagnosis, hasil laboratorium dan hasil tes diagnostik yang akan digunakan untuk perawatan medis dijamin kerahasiaannya oleh Klinik Merah Putih.</span>
                <span class='gc-sub-item'>ii. Saya setuju untuk melepaskan informasi terkait dengan kondisi kesehatan, asuhan dan pengobatan yang saya terima kepada:</span>
                <div class='gc-sub-indent'>
                    <span class='gc-sub-item'>a) Dokter dan tenaga kesehatan lain yang turut merawat / memberikan asuhan kepada saya.</span>
                    <span class='gc-sub-item'>b) Perusahaan asuransi kesehatan atau perusahaan lainnya atau pihak lain yang menjamin pembiayaan saya.</span>
                    <span class='gc-sub-item'>c) Pihak / anggota keluarga saya (maksimal 4 nama):</span>
                </div>
            </div>"
        ],
        [
            'Pengajuan Keluhan',
            'Saya telah menerima informasi tentang adanya tata cara pengajuan keluhan pelanggan terkait pelayanan yang diberikan Klinik Merah Putih terhadap diri saya. Saya setuju untuk mengikuti tata cara pengajuan keluhan pelanggan sesuai prosedur yang ada.'
        ],
        [
            'Informasi Rawat Jalan',
            "<div class='gc-sub-group'>
                <span class='gc-sub-item'>a) Saya telah menerima informasi tentang tata tertib yang berlaku di Klinik Merah Putih dan saya beserta keluarga bersedia untuk mematuhinya.</span>
                <span class='gc-sub-item'>b) Saya tidak diperkenankan untuk membawa perhiasan, barang-barang berharga / uang berlebihan.</span>
                <span class='gc-sub-item'>c) Pasien yang tidak sengaja membawa barang berharga selama perawatan di Klinik Merah Putih, maka barang tersebut wajib dititipkan kepada keluarga yang menunggu untuk diamankan. Jika pasien tidak sadar atau tidak mampu melindungi barangnya, maka barang tersebut wajib dititipkan kepada petugas satpam sesuai prosedur yang berlaku.</span>
                <span class='gc-sub-item'>d) Selama dalam pelayanan saya dan keluarga akan mematuhi ketentuan untuk tidak mengambil, menyimpan, mengedarkan gambar / video dokumen dan aktivitas pelayanan selama di klinik tanpa persetujuan klinik.</span>
            </div>"
        ],
        [
            'Kewajiban Pembayaran',
            "Saya menyatakan setuju, baik sebagai pasien ataupun sebagai wali, bahwa sesuai pertimbangan pelayanan yang diberikan kepada pasien, maka saya wajib untuk membayar total biaya pelayanan sesuai acuan biaya dan ketentuan Klinik Merah Putih dengan jaminan atau pribadi.<br><br>Apabila asuransi kesehatan swasta atau program pemerintah menanggung pembiayaan saya, saya memberi wewenang kepada Klinik Merah Putih untuk memberi tagihan dari semua pelayanan dan tindakan medis yang diberikan. Tanggungan asuransi saya mungkin menyatakan bahwa sebagian pembayaran tetap menjadi tanggung jawab pribadi saya atau tidak ditanggung oleh asuransi, maka Klinik Merah Putih berwenang memberi tagihan untuk biaya yang tidak ditanggung oleh asuransi dan saya bertanggung jawab untuk membayarnya.<br><br>Apabila saya tidak memberikan persetujuan, atau dikemudian hari mencabut persetujuan saya untuk melepaskan informasi saya kepada perusahaan asuransi yang saya tentukan, maka saya pribadi bertanggung jawab untuk membayar semua pelayanan dan tindakan medis dari Klinik Merah Putih."
        ],
        [
            'Pernyataan Persetujuan',
            'Saya menyetujui setiap pernyataan yang terdapat pada formulir ini dan menandatanganinya tanpa paksaan dan dengan kesadaran penuh.'
        ],
    ];
@endphp

<style>
.gc-text p,
.gc-text div,
.gc-sub-item {
    font-size: 12px !important;
    line-height: 1.6 !important;
    text-align: justify !important;
    text-justify: inter-word !important;
    color: #222;
}

.gc-text h4 {
    font-size: 13px !important;
    font-weight: 700;
    margin: 0 0 6px;
    color: #000;
}

.gc-text p { margin: 0; }

.gc-sub-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-top: 4px;
}

.gc-sub-indent {
    margin-left: 18px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.gc-sub-item {
    display: block;
    padding-left: 22px;
    text-indent: -22px;
}

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
    padding: 18px;
    box-shadow: 0 10px 40px rgba(0,0,0,.25);
}

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

.gc-head img {
    width: 64px;
    height: 64px;
    object-fit: contain;
    flex: 0 0 auto;
}

.gc-klinik {
    flex: 1;
    text-align: center;
    font-size: 11px;
    color: #444;
}

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

.gc-klinik small {
    display: block;
    font-size: 11px;
    line-height: 1.4;
}

.gc-title {
    text-align: center;
    font-size: 16px;
    line-height: 1.5;
    padding: 10px 0;
    border-top: 1px solid var(--gc-line, #d6d6d6);
    border-bottom: 1px solid var(--gc-line, #d6d6d6);
    margin-bottom: 4px;
}

.gc-paper .gc-section-title { margin-left: 0; }

.gc-paper .gc-box,
.gc-paper .gc-list {
    margin-left: 0;
    margin-right: 0;
}

.gc-label-kecil {
    display: block;
    margin: 8px 0 0 6px;
    font-size: 12px;
    font-weight: 600;
}

.gc-keluarga-row {
    display: flex;
    gap: 6px;
    margin-top: 6px;
    margin-left: 6px;
}

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

.gc-banner input {
    width: 15px;
    height: 15px;
    accent-color: #c8102e;
}

/* TABEL PERSETUJUAN (3 KOLOM) */
.gc-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    margin-top: 8px;
}

/* Paksa struktur tabel asli agar tidak tertimpa CSS lain di aplikasi */
.gc-table { display: table !important; }
.gc-table thead { display: table-header-group !important; }
.gc-table tbody { display: table-row-group !important; }
.gc-table tr { display: table-row !important; }
.gc-table th,
.gc-table td { display: table-cell !important; }

.gc-table th,
.gc-table td {
    border: 1px solid #000;
    padding: 8px 10px;
    box-sizing: border-box;
}

.gc-table thead th {
    background: #fff;
    color: #000;
    font-size: 13px;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    padding: 6px 8px;
}

.gc-table .gc-col-pernyataan { width: auto; }
.gc-table .gc-col-pilihan { width: 90px; }

.gc-table td.gc-td-text {
    vertical-align: top;
}

.gc-table td.gc-td-pilihan {
    text-align: center;
    vertical-align: middle;
}

.gc-table tr { break-inside: avoid; page-break-inside: avoid; }

/* RADIO BUTTON TAMPIL SEBAGAI KOTAK PERSEGI */
.gc-kotak {
    -webkit-appearance: none;
    appearance: none;
    display: inline-block;
    position: relative;
    width: 18px;
    height: 18px;
    margin: 0;
    border: 1.5px solid #1f2d4d;
    border-radius: 2px;
    background: #fff;
    cursor: pointer;
    vertical-align: middle;
}

.gc-kotak:hover { background: #eef1f7; }

.gc-kotak:checked::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 1px;
    width: 4px;
    height: 9px;
    border: solid #1f2d4d;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

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

/* Dua kolom tanda tangan dibuat sama lebar & sama tinggi */
.gc-ttd-col {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1 1 0;
    min-width: 220px;
    max-width: 340px;
}

.gc-ttd-col .gc-ttd-box {
    width: 100%;
}

.gc-ttd-label {
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 6px;
}

.gc-ttd-box {
    border: 1px solid var(--gc-line, #d6d6d6);
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
}

.gc-canvas {
    display: block;
    width: 100%;
    height: auto;
    cursor: crosshair;
    touch-action: none;
}

.gc-ttd-box .gc-canvas { pointer-events: none; }

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

.gc-text .gc-link {
    display: block;
    margin: 6px 0 0 6px;
}

.gc-nama-terang {
    margin-top: 8px;
    font-size: 12px;
    font-weight: 600;
}

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

.gc-ttd-waktu label {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.gc-ttd-waktu input {
    width: 130px;
    border: 1px solid var(--gc-line, #d6d6d6);
    border-radius: 6px;
    padding: 5px 8px;
    font-size: 12px;
    font-family: inherit;
}

.gc-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 14px;
}

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

.gc-sign-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 10001;
    background: rgba(0, 0, 0, .45);
    backdrop-filter: blur(3px);
    align-items: center;
    justify-content: center;
    padding: 16px;
}

.gc-sign-overlay.show { display: flex; }

.gc-sign-modal {
    width: 100%;
    max-width: 600px;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, .3);
    font-family: 'Poppins', 'Segoe UI', Arial, sans-serif;
}

.gc-sign-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #c8102e;
    color: #fff;
    padding: 17px 22px;
}

.gc-sign-header h3 {
    margin: 0;
    font-size: 17px;
    font-weight: 600;
}

.gc-sign-close {
    border: 0;
    background: transparent;
    color: #fff;
    font-size: 26px;
    cursor: pointer;
    line-height: 1;
}

.gc-sign-body { padding: 22px; }

.gc-sign-info {
    font-size: 13px;
    color: #666;
    margin-bottom: 12px;
}

.gc-sign-canvas-wrap {
    width: 100%;
    height: 260px;
    border: 1px solid #d6d6d6;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
}

#gcTtdPopup {
    display: block;
    width: 100%;
    height: 100%;
    cursor: crosshair;
    touch-action: none;
}

.gc-sign-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
}

.gc-sign-btn {
    border: none;
    border-radius: 6px;
    padding: 10px 22px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    font-family: inherit;
}

.gc-sign-btn-clear { background: #fbe9e9; color: #c8102e; }
.gc-sign-btn-cancel { background: #e9e9e9; color: #444; }
.gc-sign-btn-save { background: #c8102e; color: #fff; }
.gc-sign-btn-save:hover { background: #a50d26; }

@media (max-width: 600px) {
    .gc-sign-modal { max-width: 100%; }
    .gc-sign-body { padding: 15px; }
    .gc-sign-canvas-wrap { height: 220px; }
    .gc-sign-footer { flex-wrap: wrap; }
}

@media (max-width: 700px) {
    .gc-head img { width: 44px; height: 44px; }
    .gc-keluarga-input { flex: 1; }
    .gc-table .gc-col-pilihan { width: 64px; }
    .gc-table thead th { font-size: 11px; }
}

@media print {
    @page {
        size: A4;
        margin: 6mm;
    }

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

    .gc-frame {
        zoom: .72;
        padding: 10px 12px !important;
    }

    .gc-head { margin-bottom: 4px !important; }
    .gc-title { padding: 6px 0 !important; }
    .gc-table th,
    .gc-table td { padding: 6px 8px !important; }
    .gc-banner { margin: 6px 0 !important; }
    .gc-ttd-wrap { padding: 8px 12px !important; }
    .gc-ttd-col { max-width: 300px !important; }

    .gc-ttd-wrap { break-inside: avoid; }

    .gc-sign-overlay { display: none !important; }

    * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<div class="gc-overlay" id="consentModal">

    <div class="gc-paper" id="consentPaper">

        <button type="button"
                class="gc-close no-print"
                onclick="tutupConsent()">
            &times;
        </button>

        <div class="gc-frame">

            <div class="gc-head">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">

                <div class="gc-klinik">
                    <div class="gc-klinik-nama">KLINIK MERAH PUTIH</div>
                    <small>
                        Jl. Ronggowarsito No.98a, Ngadirejo, Central Karang,
                        Kec. Ngawi, Kabupaten Ngawi, Jawa Timur
                    </small>
                </div>

                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>

            <div class="gc-title">
                FORMULIR PEMBERIAN INFORMASI DAN PERSETUJUAN UMUM<br>
                (GENERAL CONSENT) RAWAT JALAN
            </div>

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

            {{-- TABEL PERSETUJUAN: 3 KOLOM (Pernyataan | Setuju | Tidak Setuju) --}}
            <div class="gc-list">

                <table class="gc-table">

                    <colgroup>
                        <col class="gc-col-pernyataan">
                        <col class="gc-col-pilihan">
                        <col class="gc-col-pilihan">
                    </colgroup>

                    <thead>
                        <tr>
                            <th>Pernyataan</th>
                            <th>Setuju</th>
                            <th>Tidak Setuju</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($butir as $i => $b)

                            <tr class="gc-row" data-index="{{ $i + 1 }}">

                                <td class="gc-td-text">
                                    <div class="gc-text">

                                        <h4>{{ $i + 1 }}. {{ $b[0] }}</h4>
                                        <p>{!! $b[1] !!}</p>

                                        @if ($i === 2)

                                            <label class="gc-label-kecil">
                                                Nama Pihak / Anggota Keluarga Yang Diperbolehkan Melihat Informasi RM Pasien (maksimal 4):
                                            </label>

                                            <div id="gc-keluarga-list">
                                                <div class="gc-keluarga-row">
                                                    <input type="text"
                                                           class="gc-keluarga-input"
                                                           placeholder="Nama pihak / anggota keluarga">

                                                    <button type="button"
                                                            class="gc-keluarga-hapus no-print"
                                                            style="display:none"
                                                            onclick="gcHapusKeluarga(this)">
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>

                                            <button type="button"
                                                    id="gc-tambah-keluarga"
                                                    class="gc-link no-print"
                                                    onclick="gcTambahKeluarga()">
                                                + Tambah nama
                                            </button>

                                        @endif

                                    </div>
                                </td>

                                <td class="gc-td-pilihan">
                                    <input type="radio"
                                           class="gc-cek gc-kotak"
                                           name="gc_persetujuan[{{ $i + 1 }}]"
                                           value="setuju"
                                           aria-label="Setuju butir {{ $i + 1 }}">
                                </td>

                                <td class="gc-td-pilihan">
                                    <input type="radio"
                                           class="gc-cek gc-kotak"
                                           name="gc_persetujuan[{{ $i + 1 }}]"
                                           value="tidak_setuju"
                                           aria-label="Tidak setuju butir {{ $i + 1 }}">
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

            {{-- KONFIRMASI SAJA, TIDAK MENGUBAH PILIHAN DI ATAS --}}
            <label class="gc-banner">
                <input type="checkbox" id="gc-semua">
                Dengan ini saya menyatakan bahwa seluruh informasi di atas telah saya baca dan saya pahami dengan sebenar-benarnya.
            </label>

            {{-- TANDA TANGAN --}}
            <div class="gc-ttd-wrap">

                <div class="gc-ttd-col">

                    <div class="gc-ttd-label">Pemberi Informasi</div>

                    <div class="gc-ttd-box"
                         onclick="gcBukaPopupTtd('gcTtdPemberi')"
                         title="Klik untuk mengisi tanda tangan">

                        <canvas id="gcTtdPemberi"
                                class="gc-canvas"
                                width="400"
                                height="186">
                        </canvas>

                    </div>

                    <button type="button"
                            class="gc-link no-print"
                            onclick="gcHapusTtd('gcTtdPemberi')">
                        Hapus tanda tangan
                    </button>

                    <div class="gc-nama-terang">
                        (
                        <input type="text"
                               id="gc-nama-pemberi"
                               placeholder="Nama terang">
                        )
                    </div>

                </div>

                <div class="gc-ttd-col">

                    <div class="gc-ttd-label">Tanda tangan Penerima Informasi</div>

                    <div class="gc-ttd-box"
                         onclick="gcBukaPopupTtd('gcTtd')"
                         title="Klik untuk mengisi tanda tangan">

                        <canvas id="gcTtd"
                                class="gc-canvas"
                                width="400"
                                height="186">
                        </canvas>

                    </div>

                    <button type="button"
                            class="gc-link no-print"
                            onclick="gcHapusTtd('gcTtd')">
                        Hapus tanda tangan
                    </button>

                    <div class="gc-nama-terang">
                        (
                        <input type="text"
                               id="gc-nama-pasien"
                               placeholder="Nama terang">
                        )
                    </div>

                </div>

                <div class="gc-ttd-waktu">

                    <label>
                        Tanggal
                        <input type="text" id="gc-tgl" readonly>
                    </label>

                    <label>
                        Jam
                        <input type="text" id="gc-jam" readonly>
                    </label>

                </div>

            </div>

            <div class="gc-actions no-print">

                <button type="button"
                        class="gc-btn"
                        id="gc-btn-simpan"
                        onclick="simpanConsent()">
                    Simpan
                </button>

                <button type="button"
                        class="gc-btn"
                        onclick="window.print()">
                    Cetak
                </button>

            </div>

        </div>
    </div>

    {{-- POPUP INPUT TANDA TANGAN --}}
    <div class="gc-sign-overlay" id="gcSignModal">

        <div class="gc-sign-modal">

            <div class="gc-sign-header">

                <h3 id="gcSignTitle">Tanda Tangan</h3>

                <button type="button"
                        class="gc-sign-close"
                        onclick="gcTutupPopupTtd()">
                    &times;
                </button>

            </div>

            <div class="gc-sign-body">

                <div class="gc-sign-info">
                    Silakan bubuhkan tanda tangan pada area di bawah ini.
                </div>

                <div class="gc-sign-canvas-wrap">

                    <canvas id="gcTtdPopup"
                            width="560"
                            height="260">
                    </canvas>

                </div>

                <div class="gc-sign-footer">

                    <button type="button"
                            class="gc-sign-btn gc-sign-btn-clear"
                            onclick="gcHapusTtd('gcTtdPopup')">
                        Hapus
                    </button>

                    <button type="button"
                            class="gc-sign-btn gc-sign-btn-cancel"
                            onclick="gcTutupPopupTtd()">
                        Batal
                    </button>

                    <button type="button"
                            class="gc-sign-btn gc-sign-btn-save"
                            onclick="gcSimpanPopupTtd()">
                        Simpan
                    </button>

                </div>

            </div>
        </div>

    </div>

</div>

<script>
(function () {

    'use strict';

    const GC_MAKS_KELUARGA = 4;

    let gcDataPasien = {};
    let gcTargetTtd = null;

    // No RM pasien yang isian General Consent-nya sedang tersimpan di form.
    // Selama No RM sama, isian tidak direset saat modal dibuka lagi.
    let gcNormTerakhir = null;

    window.gcPad = window.gcPad || {};

    /* MENGISI DATA PASIEN */
    function gcIsiData(data, jagaWaktu) {

        document.getElementById('gc-norm').value = data.norm || '';
        document.getElementById('gc-nama').value = data.nama || '';
        document.getElementById('gc-ttl').value = data.ttl || '';
        document.getElementById('gc-umur').value = data.umur || '';
        document.getElementById('gc-jk').value = data.jk || '';
        document.getElementById('gc-hp').value = data.hp || '';

        // Jika isian dipertahankan, tanggal & jam tidak diubah.
        if (jagaWaktu) {
            return;
        }

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

    /* MEMBUKA GENERAL CONSENT */
    window.bukaConsent = function (data = {}) {

        gcDataPasien = data || {};

        const norm = String(gcDataPasien.norm || '').trim();

        const pasienSama = norm !== '' && norm === gcNormTerakhir;

        if (pasienSama) {

            // Pasien yang sama: pertahankan semua isian (pilihan, nama,
            // tanda tangan, tanggal & jam). Hanya data pasien yang disegarkan.
            gcIsiData(gcDataPasien, true);

        } else {

            // Pasien berbeda / belum pernah diisi: mulai dari kosong.
            gcReset();

            gcIsiData(gcDataPasien, false);

            gcNormTerakhir = norm !== '' ? norm : null;

        }

        document.getElementById('consentModal').classList.add('show');

        gcInitPad('gcTtdPemberi');
        gcInitPad('gcTtd');
        gcInitPad('gcTtdPopup');

    };

    /* MENUTUP GENERAL CONSENT */
    window.tutupConsent = function () {

        document.getElementById('consentModal').classList.remove('show');

    };

    /* MEMBUKA POPUP TANDA TANGAN */
    window.gcBukaPopupTtd = function (id) {

        if (id !== 'gcTtdPemberi' && id !== 'gcTtd') {
            return;
        }

        gcTargetTtd = id;

        const judul = document.getElementById('gcSignTitle');

        judul.textContent = id === 'gcTtdPemberi'
            ? 'Tanda Tangan Pemberi Informasi'
            : 'Tanda Tangan Pasien/Keluarga';

        gcInitPad('gcTtdPopup');
        gcHapusTtd('gcTtdPopup');

        const sumber = window.gcPad[id];
        const popup = window.gcPad['gcTtdPopup'];

        if (sumber && sumber.dirty && popup) {

            popup.ctx.drawImage(
                sumber.canvas,
                0,
                0,
                popup.canvas.width,
                popup.canvas.height
            );

            popup.dirty = true;

        }

        document.getElementById('gcSignModal').classList.add('show');

    };

    /* MENUTUP POPUP TANDA TANGAN */
    window.gcTutupPopupTtd = function () {

        const modal = document.getElementById('gcSignModal');

        if (modal) {
            modal.classList.remove('show');
        }

        gcTargetTtd = null;

    };

    /* MENYIMPAN TANDA TANGAN POPUP */
    window.gcSimpanPopupTtd = function () {

        if (!gcTargetTtd) {
            return;
        }

        const popup = window.gcPad['gcTtdPopup'];
        const target = window.gcPad[gcTargetTtd];

        if (!popup || !popup.dirty) {

            alert('Silakan bubuhkan tanda tangan terlebih dahulu.');

            return;
        }

        if (!target) {
            return;
        }

        const gambar = new Image();

        gambar.onload = function () {

            target.ctx.clearRect(
                0,
                0,
                target.canvas.width,
                target.canvas.height
            );

            target.ctx.drawImage(
                gambar,
                0,
                0,
                target.canvas.width,
                target.canvas.height
            );

            target.dirty = true;

            gcTutupPopupTtd();

        };

        gambar.src = popup.canvas.toDataURL('image/png');

    };

    /* RESET GENERAL CONSENT */
    function gcReset() {

        gcNormTerakhir = null;

        document.querySelectorAll('.gc-cek').forEach(c => {
            c.checked = false;
        });

        document.getElementById('gc-semua').checked = false;

        const list = document.getElementById('gc-keluarga-list');

        if (list) {

            const rows = list.querySelectorAll('.gc-keluarga-row');

            rows.forEach((row, i) => {
                if (i > 0) row.remove();
            });

            const input = list.querySelector('input');

            if (input) {
                input.value = '';
            }

            gcRefreshKeluarga();

        }

        document.getElementById('gc-nama-pemberi').value = '';
        document.getElementById('gc-nama-pasien').value = '';

        gcHapusTtd('gcTtdPemberi');
        gcHapusTtd('gcTtd');
        gcHapusTtd('gcTtdPopup');

        gcTutupPopupTtd();

    }

    window.gcReset = gcReset;

    /* REFRESH DAFTAR NAMA (TOMBOL HAPUS & TOMBOL TAMBAH, MAKS 4) */
    function gcRefreshKeluarga() {

        const rows = document.querySelectorAll(
            '#gc-keluarga-list .gc-keluarga-row'
        );

        rows.forEach(row => {

            const tombol = row.querySelector('.gc-keluarga-hapus');

            if (tombol) {

                tombol.style.display =
                    rows.length > 1 ? 'inline-block' : 'none';

            }

        });

        const tambah = document.getElementById('gc-tambah-keluarga');

        if (tambah) {

            tambah.style.display =
                rows.length >= GC_MAKS_KELUARGA ? 'none' : 'block';

        }

    }

    /* TAMBAH NAMA */
    window.gcTambahKeluarga = function () {

        const list = document.getElementById('gc-keluarga-list');

        if (!list) {
            return;
        }

        const jumlah = list.querySelectorAll('.gc-keluarga-row').length;

        if (jumlah >= GC_MAKS_KELUARGA) {

            alert('Maksimal ' + GC_MAKS_KELUARGA + ' nama yang dapat ditambahkan.');

            return;

        }

        const awal = list.querySelector('.gc-keluarga-row');

        if (!awal) {
            return;
        }

        const row = awal.cloneNode(true);

        row.querySelector('input').value = '';

        list.appendChild(row);

        gcRefreshKeluarga();

        row.querySelector('input').focus();

    };

    /* HAPUS NAMA */
    window.gcHapusKeluarga = function (button) {

        const row = button.closest('.gc-keluarga-row');

        if (row) {
            row.remove();
        }

        gcRefreshKeluarga();

    };

    /* MENGAMBIL DATA NAMA (MAKS 4) */
    function gcAmbilKeluarga() {

        return Array.from(
            document.querySelectorAll('.gc-keluarga-input')
        )
        .map(input => input.value.trim())
        .filter(Boolean)
        .slice(0, GC_MAKS_KELUARGA);

    }

    /* MENGAMBIL PILIHAN PERSETUJUAN SETIAP BUTIR */
    function gcAmbilPersetujuan() {

        const hasil = {};

        document.querySelectorAll('.gc-row').forEach((item, index) => {

            const pilihan = item.querySelector(
                'input[type="radio"]:checked'
            );

            hasil[index + 1] = pilihan ? pilihan.value : '';

        });

        return hasil;

    }

    /* INISIALISASI CANVAS TANDA TANGAN */
    function gcInitPad(id) {

        const canvas = document.getElementById(id);

        if (!canvas) {
            return;
        }

        if (canvas.dataset.gcInitialized === '1') {
            return;
        }

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

            if (!state.drawing) {
                return;
            }

            event.preventDefault();

            const p = posisi(event);

            ctx.lineTo(p.x, p.y);
            ctx.stroke();

            state.dirty = true;

        });

        ['pointerup', 'pointerleave', 'pointercancel'].forEach(
            function (eventName) {

                canvas.addEventListener(eventName, function () {
                    state.drawing = false;
                });

            }
        );

    }

    /* HAPUS TANDA TANGAN */
    window.gcHapusTtd = function (id) {

        const state = window.gcPad[id];

        if (!state) {
            return;
        }

        state.ctx.clearRect(
            0,
            0,
            state.canvas.width,
            state.canvas.height
        );

        state.dirty = false;

    };

    /* MENGAMBIL HASIL TANDA TANGAN */
    function gcAmbilTtd(id) {

        const state = window.gcPad[id];

        return state && state.dirty
            ? state.canvas.toDataURL('image/png')
            : '';

    }

    /* VALIDASI GENERAL CONSENT */
    function gcValidasi() {

        const semuaButir = Array.from(
            document.querySelectorAll('.gc-row')
        );

        // Setiap butir wajib memilih Setuju atau Tidak Setuju.
        const semuaSudahDipilih = semuaButir.every(item => {

            return item.querySelector(
                'input[type="radio"]:checked'
            ) !== null;

        });

        if (!semuaSudahDipilih) {

            alert('Semua butir harus dipilih Setuju atau Tidak Setuju.');

            return false;

        }

        // Checkbox bawah hanya validasi konfirmasi.
        if (!document.getElementById('gc-semua').checked) {

            alert('Pernyataan konfirmasi di bawah butir belum dicentang.');

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

    /* SIMPAN GENERAL CONSENT */
    window.simpanConsent = function () {

        if (!gcValidasi()) {
            return;
        }

        const noRm = document.getElementById('gc-norm').value.trim();

        if (!noRm) {

            alert(
                'No. RM kosong. Isi data pasien terlebih dahulu, lalu buka kembali General Consent.'
            );

            return;

        }

        const form =
            document.getElementById('formPendaftaran') ||
            document.getElementById('formKunjungan');

        if (!form) {

            alert('Form pendaftaran tidak ditemukan.');

            return;

        }

        function setHidden(name, value) {

            let input = form.querySelector(
                '[name="' + name + '"]'
            );

            if (!input) {

                input = document.createElement('input');
                input.type = 'hidden';
                input.name = name;

                form.appendChild(input);

            }

            input.value = value;

        }

        setHidden('general_consent', '1');

        // Ambil hasil pilihan masing-masing butir.
        const persetujuan = gcAmbilPersetujuan();

        // Simpan masing-masing butir ke hidden input terpisah.
        setHidden('gc_hak_kewajiban', persetujuan[1]);
        setHidden('gc_persetujuan_pelayanan', persetujuan[2]);
        setHidden('gc_pelepasan_informasi', persetujuan[3]);
        setHidden('gc_pengajuan_keluhan', persetujuan[4]);
        setHidden('gc_informasi_rawat_jalan', persetujuan[5]);
        setHidden('gc_kewajiban_pembayaran', persetujuan[6]);
        setHidden('gc_pernyataan_persetujuan', persetujuan[7]);

        // Simpan status centang konfirmasi secara terpisah.
        setHidden(
            'gc_konfirmasi',
            document.getElementById('gc-semua').checked ? '1' : '0'
        );

        setHidden('gc_ttd_pemberi', gcAmbilTtd('gcTtdPemberi'));

        setHidden(
            'gc_nama_pemberi',
            document.getElementById('gc-nama-pemberi').value.trim()
        );

        setHidden('gc_ttd_pasien', gcAmbilTtd('gcTtd'));

        setHidden(
            'gc_nama_penandatangan',
            document.getElementById('gc-nama-pasien').value.trim()
        );

        // Maksimal 4 nama pihak yang boleh mengakses informasi RM.
        setHidden(
            'gc_anggota_keluarga',
            JSON.stringify(gcAmbilKeluarga())
        );

        setHidden('gc_tanggal', document.getElementById('gc-tgl').value);
        setHidden('gc_jam', document.getElementById('gc-jam').value);

        const tombolConsent = document.getElementById('btnConsent');

        if (tombolConsent) {

            tombolConsent.textContent = '✔ General Consent';
            tombolConsent.classList.add('sudah');

        }

        window.tutupConsent();

        alert(
            'General Consent sudah diisi. Selanjutnya, simpan pendaftaran pasien.'
        );

    };

    /* DOM CONTENT LOADED */
    document.addEventListener('DOMContentLoaded', function () {

        document.body.appendChild(
            document.getElementById('consentModal')
        );

        gcInitPad('gcTtdPemberi');
        gcInitPad('gcTtd');
        gcInitPad('gcTtdPopup');

        gcRefreshKeluarga();

        const popup = document.getElementById('gcSignModal');

        if (popup) {

            popup.addEventListener('click', function (event) {

                if (event.target === popup) {
                    gcTutupPopupTtd();
                }

            });

        }

    });

})();
</script>