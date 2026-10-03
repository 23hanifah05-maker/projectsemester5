/* =====================================================
   AUDIT LOG (sementara di localStorage)
   Nanti diganti dengan simpan ke database.
===================================================== */
(function () {

    const KUNCI = 'audit_trail_log';

    const DATA_AWAL = [
        { id: 6, username: 'admin2', role: 'admin', aktivitas: 'Edit',   modul: 'Pendaftaran', waktu: '2026-10-01 09:23:00', ip: '127.0.0.1', no_rm: 'RM-0001', kolom: 'Alamat', sebelum: 'Jl. Merdeka No. 10', sesudah: 'Jl. Merdeka No. 12', status: 'Berhasil' },
        { id: 5, username: 'admin2', role: 'admin', aktivitas: 'Tambah', modul: 'Pendaftaran', waktu: '2026-10-01 09:11:00', ip: '127.0.0.1', no_rm: 'RM-0002', kolom: '-', sebelum: '-', sesudah: 'Data pasien baru ditambahkan', status: 'Berhasil' },
        { id: 4, username: 'admin2', role: 'admin', aktivitas: 'Tambah', modul: 'Pendaftaran', waktu: '2026-10-01 08:57:00', ip: '127.0.0.1', no_rm: 'RM-0003', kolom: '-', sebelum: '-', sesudah: 'Data pasien baru ditambahkan', status: 'Berhasil' },
        { id: 3, username: 'admin2', role: 'admin', aktivitas: 'Tambah', modul: 'Pendaftaran', waktu: '2026-10-01 08:45:00', ip: '127.0.0.1', no_rm: 'RM-0004', kolom: '-', sebelum: '-', sesudah: 'Data pasien baru ditambahkan', status: 'Berhasil' },
        { id: 2, username: 'admin2', role: 'admin', aktivitas: 'Login',  modul: 'Autentikasi', waktu: '2026-10-01 08:12:00', ip: '127.0.0.1', no_rm: '-', kolom: '-', sebelum: '-', sesudah: '-', status: 'Berhasil' },
        { id: 1, username: 'admin2', role: 'admin', aktivitas: 'Login',  modul: 'Autentikasi', waktu: '2026-10-01 08:10:00', ip: '127.0.0.1', no_rm: '-', kolom: '-', sebelum: '-', sesudah: '-', status: 'Berhasil' }
    ];

    function dua(n) {
        return String(n).padStart(2, '0');
    }

    function sekarang() {
        const d = new Date();
        return d.getFullYear() + '-' + dua(d.getMonth() + 1) + '-' + dua(d.getDate()) +
               ' ' + dua(d.getHours()) + ':' + dua(d.getMinutes()) + ':' + dua(d.getSeconds());
    }

    const AuditLog = {

        ambil: function () {
            try {
                const mentah = localStorage.getItem(KUNCI);

                if (mentah === null) {
                    localStorage.setItem(KUNCI, JSON.stringify(DATA_AWAL));
                    return DATA_AWAL.slice();
                }

                return JSON.parse(mentah) || [];
            } catch (e) {
                console.error(e);
                return [];
            }
        },

        simpan: function (daftar) {
            try {
                localStorage.setItem(KUNCI, JSON.stringify(daftar));
            } catch (e) {
                console.error(e);
            }
        },

        catat: function (aktivitas, modul, extra) {
            const user = window.AUDIT_USER || { username: 'admin2', role: 'admin' };
            const daftar = this.ambil();

            const idBaru = daftar.reduce(function (maks, l) {
                return Math.max(maks, Number(l.id) || 0);
            }, 0) + 1;

            daftar.push(Object.assign({
                id: idBaru,
                username: user.username,
                role: user.role,
                aktivitas: aktivitas,
                modul: modul,
                waktu: sekarang(),
                ip: '127.0.0.1',
                no_rm: '-',
                kolom: '-',
                sebelum: '-',
                sesudah: '-',
                status: 'Berhasil'
            }, extra || {}));

            this.simpan(daftar);
        },

        reset: function () {
            localStorage.removeItem(KUNCI);
        }
    };

    window.AuditLog = AuditLog;

})();