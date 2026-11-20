@php
    $r = $resume ?? null;
    $edukasiTerpilih = $r ? $r->edukasi->pluck('edukasi')->toArray() : [];
    $daftarEdukasi = ['Diet', 'Obat', 'Aktivitas Fisik', 'Kontrol Ulang', 'Tanda Bahaya', 'Perawatan Luka'];

    $snomedAwal = $r
        ? $r->snomed->map(fn ($s) => [
            'bagian'    => $s->bagian,
            'kode'      => $s->kode,
            'deskripsi' => $s->deskripsi,
        ])->values()
        : collect();

    $bagianSnomed = [
        'keluhan'     => 'Keluhan Saat Masuk',
        'terapi'      => 'Terapi Obat yang Diberikan',
        'kesadaran'   => 'Tingkat Kesadaran Pasien Masuk',
        'instruksi'   => 'Instruksi atau Rencana Tindak Lanjut',
        'fisik'       => 'Pemeriksaan Fisik',
        'penunjang'   => 'Pemeriksaan Penunjang',
        'dx_utama'    => 'Diagnosis Utama',
        'dx_sekunder' => 'Diagnosis Sekunder',
        'tindakan'    => 'Tindakan / Prosedur',
    ];
@endphp

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('poli.syaraf.resume-medis.store') }}" id="form-resume-medis"
    data-snomed="{{ json_encode($snomedAwal) }}"
    data-bagian="{{ json_encode(array_keys($bagianSnomed)) }}">
    @csrf
    <input type="hidden" name="id_pendaftaran" value="{{ $id_pendaftaran }}">

    {{-- Bagian atas --}}
    <div class="rm-row">
        <div class="rm-col">
            <label>Alamat</label>
            <textarea name="alamat" rows="2">{{ old('alamat', $r->alamat ?? '') }}</textarea>

            <input type="text" name="kelurahan" placeholder="Kelurahan / Desa" value="{{ old('kelurahan', $r->kelurahan ?? '') }}">
            <input type="text" name="kecamatan" placeholder="Kecamatan" value="{{ old('kecamatan', $r->kecamatan ?? '') }}">
            <input type="text" name="kota" placeholder="Kota / Kabupaten" value="{{ old('kota', $r->kota ?? '') }}">
            <input type="text" name="provinsi" placeholder="Provinsi" value="{{ old('provinsi', $r->provinsi ?? '') }}">

            <label>Kode Pos</label>
            <input type="text" name="kode_pos" maxlength="5" value="{{ old('kode_pos', $r->kode_pos ?? '') }}">

            <label>Pembayaran</label>
            <input type="text" name="pembayaran" placeholder="mis. BPJS / Umum" value="{{ old('pembayaran', $r->pembayaran ?? '') }}">
        </div>

        <div class="rm-col">
            <label>Tgl. Kunjungan</label>
            <input type="date" name="tgl_kunjungan" value="{{ old('tgl_kunjungan', optional($r?->tgl_kunjungan)->format('Y-m-d')) }}">

            <label>Tgl. Keluar</label>
            <input type="date" name="tgl_keluar" value="{{ old('tgl_keluar', optional($r?->tgl_keluar)->format('Y-m-d')) }}">

            <fieldset class="rm-pj">
                <legend>Penanggung Jawab</legend>
                <label>Nama</label>
                <input type="text" name="pj_nama" value="{{ old('pj_nama', $r->pj_nama ?? '') }}">

                <label>Hubungan</label>
                <input type="text" name="pj_hubungan" value="{{ old('pj_hubungan', $r->pj_hubungan ?? '') }}">

                <label>Jenis Kelamin</label>
                <select name="pj_jk">
                    <option value="">-- Pilih --</option>
                    @foreach (['Laki-laki', 'Perempuan'] as $jk)
                        <option value="{{ $jk }}" @selected(old('pj_jk', $r->pj_jk ?? '') === $jk)>{{ $jk }}</option>
                    @endforeach
                </select>

                <label>No. HP</label>
                <input type="text" name="pj_hp" value="{{ old('pj_hp', $r->pj_hp ?? '') }}">
            </fieldset>
        </div>
    </div>

    {{-- Ringkasan klinis & penatalaksanaan --}}
    <h4 class="rm-judul">Ringkasan Klinis &amp; Penatalaksanaan</h4>

    <div class="rm-grid">
        @foreach ($bagianSnomed as $kolom => $label)
            <div class="rm-box">
                <label>{{ $label }}</label>
                <textarea name="{{ $kolom }}" rows="3">{{ old($kolom, $r->$kolom ?? '') }}</textarea>

                <div class="snomed-box" data-bagian="{{ $kolom }}">
                    <div class="snomed-head">
                        <span>Kode SNOMED CT</span>
                        <button type="button" class="btn-tambah-snomed" data-bagian="{{ $kolom }}">Tambah Kode +</button>
                    </div>
                    <div class="snomed-list" id="snomed-list-{{ $kolom }}">
                        <small class="snomed-kosong">Belum ada kode SNOMED CT.</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Prognosis, kondisi & cara keluar --}}
    <div class="rm-row">
        <div class="rm-col">
            <label>Prognosis</label>
            <select name="prognosis">
                <option value="">-- Pilih --</option>
                @foreach (['Ad bonam', 'Dubia ad bonam', 'Dubia ad malam', 'Ad malam'] as $p)
                    <option value="{{ $p }}" @selected(old('prognosis', $r->prognosis ?? '') === $p)>{{ $p }}</option>
                @endforeach
            </select>
        </div>
        <div class="rm-col">
            <label>Kondisi Saat Keluar</label>
            <select name="kondisi_keluar">
                <option value="">-- Pilih --</option>
                @foreach (['Sembuh', 'Membaik', 'Belum Sembuh', 'Meninggal'] as $k)
                    <option value="{{ $k }}" @selected(old('kondisi_keluar', $r->kondisi_keluar ?? '') === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="rm-col">
            <label>Cara Keluar</label>
            <select name="cara_keluar">
                <option value="">-- Pilih --</option>
                @foreach (['Atas Persetujuan Dokter', 'Dirujuk', 'Atas Permintaan Sendiri', 'Meninggal'] as $c)
                    <option value="{{ $c }}" @selected(old('cara_keluar', $r->cara_keluar ?? '') === $c)>{{ $c }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Edukasi --}}
    <h4 class="rm-judul">Edukasi</h4>
    <div class="rm-edukasi">
        @foreach ($daftarEdukasi as $e)
            <label>
                <input type="checkbox" name="edukasi[]" value="{{ $e }}"
                    @checked(in_array($e, old('edukasi', $edukasiTerpilih)))>
                {{ $e }}
            </label>
        @endforeach
    </div>

    {{-- Tanda tangan DPJP --}}
    <div class="rm-row">
        <div class="rm-col">
            <label>Tanggal TTD</label>
            <input type="date" name="tanggal_ttd" value="{{ old('tanggal_ttd', optional($r?->tanggal_ttd)->format('Y-m-d')) }}">
        </div>
        <div class="rm-col">
            <label>Jam TTD</label>
            <input type="time" name="jam_ttd" value="{{ old('jam_ttd', $r->jam_ttd ?? '') }}">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Simpan Resume Medis</button>
</form>

<script>
(function () {
    const formEl = document.getElementById('form-resume-medis');
    let daftar = JSON.parse(formEl.dataset.snomed);
    const daftarBagian = JSON.parse(formEl.dataset.bagian);

    function esc(teks) {
        const d = document.createElement('div');
        d.textContent = teks;
        return d.innerHTML;
    }

    function render(bagian) {
        const wadah = document.getElementById('snomed-list-' + bagian);
        const item = daftar.filter(s => s.bagian === bagian);

        if (item.length === 0) {
            wadah.innerHTML = '<small class="snomed-kosong">Belum ada kode SNOMED CT.</small>';
            return;
        }

        wadah.innerHTML = item.map(s => `
            <div class="snomed-item">
                <span>${esc(s.kode)} - ${esc(s.deskripsi)}</span>
                <button type="button" class="btn-hapus-snomed" data-bagian="${esc(s.bagian)}" data-kode="${esc(s.kode)}">&times;</button>
            </div>
        `).join('');
    }

    function renderHidden() {
        formEl.querySelectorAll('.snomed-hidden').forEach(el => el.remove());

        daftar.forEach((s, i) => {
            ['bagian', 'kode', 'deskripsi'].forEach(f => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.className = 'snomed-hidden';
                input.name = `snomed[${i}][${f}]`;
                input.value = s[f];
                formEl.appendChild(input);
            });
        });
    }

    function renderSemua() {
        daftarBagian.forEach(render);
        renderHidden();
    }

    document.querySelectorAll('.btn-tambah-snomed').forEach(btn => {
        btn.addEventListener('click', function () {
            const bagian = this.dataset.bagian;
            const kode = (prompt('Masukkan kode SNOMED CT:') || '').trim();
            if (!kode) return;
            const deskripsi = (prompt('Masukkan deskripsi kode:') || '').trim();
            if (!deskripsi) return;

            if (daftar.some(s => s.bagian === bagian && s.kode === kode)) {
                alert('Kode tersebut sudah ditambahkan.');
                return;
            }

            daftar.push({ bagian, kode, deskripsi });
            renderSemua();
        });
    });

    formEl.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-hapus-snomed');
        if (!btn) return;
        daftar = daftar.filter(s => !(s.bagian === btn.dataset.bagian && s.kode === btn.dataset.kode));
        renderSemua();
    });

    renderSemua();
})();
</script>