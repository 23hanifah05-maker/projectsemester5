{{--
    PARTIAL: Kotak "Tindak Lanjut" dan "Resep Obat" pada kolom Plan (P)
    Simpan di: resources/views/partials/resep-obat.blade.php
    Dipakai di Poli Obgyn dan Poli Syaraf lewat:  @include('partials.resep-obat')

    - textarea[name="plan"] asli disembunyikan, tetapi tetap ada dan berisi
      gabungan kedua kotak (dipakai oleh simpan SOAP, resume, dan cetak).
    - Tombol Cetak yang sudah ada dipindahkan ke kiri bawah kotak Resep Obat.
--}}

<style>
    .kb-plan-wrap,
    .ob-plan-wrap {
        display: block;
    }

    .rsp-kotak {
        width: 100%;
        box-sizing: border-box;
        margin-top: 8px;
        padding: 10px 12px;
        border: 1px solid #d9d9d9;
        border-radius: 8px;
        background: #fff;
    }

    .rsp-kotak + .rsp-kotak {
        margin-top: 12px;
    }

    .rsp-judul {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #b81d24;
    }

    .rsp-kotak textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 6px 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background: #fff;
        font: inherit;
        font-size: 13px;
        line-height: 1.5;
        resize: vertical;
    }

    .rsp-kotak textarea:focus {
        outline: none;
        border-color: #b81d24;
    }

    /* Tombol Cetak di kiri bawah kotak Resep Obat */
    .rsp-footer {
        display: flex;
        justify-content: flex-start;
        margin-top: 10px;
    }

    .rsp-footer .kb-cetak-btn,
    .rsp-footer [onclick*="cetakResep"] {
        position: static !important;
        top: auto !important;
        right: auto !important;
        bottom: auto !important;
        left: auto !important;
        transform: none !important;
        margin: 0 !important;
    }
</style>

<script>
    (function () {
        const JUDUL_TL = 'Tindak Lanjut :';
        const JUDUL_RESEP = 'Resep Obat :';

        function buatKotak(judul, id) {
            const kotak = document.createElement('div');
            kotak.className = 'rsp-kotak';

            const label = document.createElement('label');
            label.className = 'rsp-judul';
            label.setAttribute('for', id);
            label.textContent = judul;

            const area = document.createElement('textarea');
            area.id = id;
            area.rows = 2;
            area.autocomplete = 'off';

            kotak.appendChild(label);
            kotak.appendChild(area);

            return { kotak: kotak, area: area };
        }

        // Pecah isi Plan yang tersimpan menjadi dua bagian
        function pecahPlan(teks) {
            const pola = /^\s*Tindak Lanjut :\s*\n?([\s\S]*?)\n*\s*Resep Obat :\s*\n?([\s\S]*)$/;
            const hasil = teks.match(pola);

            if (hasil) {
                return {
                    tl: hasil[1].replace(/\s+$/, ''),
                    resep: hasil[2].replace(/\s+$/, '')
                };
            }

            // Isi lama tanpa judul masuk ke Tindak Lanjut
            return { tl: teks.trim(), resep: '' };
        }

        function siapkanPlan() {
            const plan = document.querySelector('textarea[name="plan"]');

            if (!plan || plan.dataset.templateSiap === '1') return;

            plan.dataset.templateSiap = '1';

            const tl = buatKotak(JUDUL_TL, 'planTindakLanjut');
            const resep = buatKotak(JUDUL_RESEP, 'planResepObat');

            const awal = pecahPlan(plan.value);
            tl.area.value = awal.tl;
            resep.area.value = awal.resep;

            // Sembunyikan textarea asli (tetap ada untuk simpan & cetak).
            // Nilainya TIDAK diubah di sini, supaya pengaman "belum disimpan"
            // tidak menganggap form sudah berubah.
            plan.style.display = 'none';
            plan.removeAttribute('required');

            plan.insertAdjacentElement('afterend', tl.kotak);
            tl.kotak.insertAdjacentElement('afterend', resep.kotak);

            // Footer kotak Resep Obat: tombol Cetak di kiri bawah
            const footer = document.createElement('div');
            footer.className = 'rsp-footer';
            resep.kotak.appendChild(footer);

            // Pindahkan tombol Cetak yang sudah ada; jika tidak ada, buat baru
            let tombolCetak = document.querySelector('.kb-cetak-plan, .ob-cetak-plan') ||
                              document.querySelector('[onclick*="cetakResep"]');

            if (!tombolCetak) {
                tombolCetak = document.createElement('button');
                tombolCetak.type = 'button';
                tombolCetak.className = 'kb-cetak-btn';
                tombolCetak.textContent = 'Cetak';
                tombolCetak.addEventListener('click', function () {
                    window.cetakResep();
                });
            }

            footer.appendChild(tombolCetak);

            // Gabungkan kedua kotak ke textarea asli saat dokter mengetik
            function sinkron() {
                const a = tl.area.value;
                const b = resep.area.value;

                if (a.trim() === '' && b.trim() === '') {
                    plan.value = '';
                } else {
                    plan.value =
                        JUDUL_TL + '\n' + a +
                        '\n\n' +
                        JUDUL_RESEP + '\n' + b;
                }

                plan.dispatchEvent(new Event('input', { bubbles: true }));
            }

            tl.area.addEventListener('input', sinkron);
            resep.area.addEventListener('input', sinkron);
        }

        document.addEventListener('DOMContentLoaded', siapkanPlan);

        if (document.readyState !== 'loading') {
            siapkanPlan();
        }
    })();

    // ---------------------------------------------------------
    // Cetak resep: mencetak isi Plan (judul + isi kedua kotak).
    // Menggantikan cetakResep() bawaan halaman setelah semua
    // script halaman selesai dimuat. Area cetak, tanggal, dan
    // class cetak-resep-mode sama seperti fungsi bawaan.
    // ---------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function () {
        window.cetakResep = function () {
            const resepEl = document.getElementById('planResepObat');
            const resepObat = resepEl ? resepEl.value.trim() : '';

            // Yang dicetak hanya kotak Resep Obat
            if (resepObat === '') {
                alert('Isi kotak Resep Obat dulu sebelum mencetak resep.');
                return;
            }

            const area = document.getElementById('cetak-resep-area');
            const isiPlan = document.getElementById('resep-isi-plan');

            if (!area || !isiPlan) {
                alert('Area cetak resep tidak ditemukan di halaman ini.');
                return;
            }

            const isiObatLama = document.getElementById('resep-isi-obat');
            if (isiObatLama) isiObatLama.remove();

            // Hanya isi Resep Obat (tanpa judul, karena lembar resep sudah ada "R/")
            isiPlan.style.whiteSpace = 'pre-wrap';
            isiPlan.innerText = resepObat;

            const tanggal = document.getElementById('resep-tanggal');

            if (tanggal) {
                tanggal.innerText = new Date().toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
            }

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