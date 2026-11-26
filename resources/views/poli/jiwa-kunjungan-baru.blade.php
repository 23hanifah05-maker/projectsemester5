@extends('layouts.app')

@section('title', 'Kunjungan Poli Jiwa - Klinik Utama Merah Putih')
@section('header-icon')
    <i class="fa-solid fa-brain"></i>
@endsection
@section('header-title', 'Poli Jiwa')

@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/jiwa-kunjungan-baru.css') }}?v={{ @filemtime(public_path('css/jiwa-kunjungan-baru.css')) }}">
@endsection

@section('content')

    @php
        // ===== DATA DUMMY =====
        // Dipakai bila controller belum mengirim $pasien.
        // Hapus blok ini bila data asli dari database sudah dikirim controller.
        if (!isset($pasien)) {
            $dummy = [
                'E000041' => ['Sri Wahyuni',    'Perempuan', '1985-03-18', '081345670001'],
                'E000042' => ['Agus Setiawan',  'Laki-laki', '1992-08-09', '081345670002'],
                'E000043' => ['Dewi Anggraini', 'Perempuan', '1998-01-27', '081345670003'],
                'E000044' => ['Rudi Hermawan',  'Laki-laki', '1979-10-30', '081345670004'],
                'E000045' => ['Nur Aini',       'Perempuan', '2001-05-14', '081345670005'],
                'E000046' => ['Fajar Nugroho',  'Laki-laki', '1988-12-02', '081345670006'],
            ];
            $d = $dummy[$no_rm] ?? null;

            $pasien = (object) [
                'no_rm'         => $no_rm,
                'nama'          => $d[0] ?? '-',
                'jenis_kelamin' => $d[1] ?? '-',
                'tempat_lahir'  => 'Ngawi',
                'tgl_lahir'     => $d[2] ?? null,
                'no_hp'         => $d[3] ?? '-',
            ];
        }

        $id_pendaftaran = $id_pendaftaran ?? null;

        $tglLahirTampil = !empty($pasien->tgl_lahir)
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d-m-Y') : '-';
        $umurTampil = !empty($pasien->tgl_lahir)
            ? \Carbon\Carbon::parse($pasien->tgl_lahir)->age : '-';

        // ===== HAK AKSES (sesuaikan nama kolom role di tabel users) =====
        $role = strtolower(auth()->user()->role ?? 'admin');
        $bisaTtv = in_array($role, ['admin', 'perawat']);
        $bisaSO  = in_array($role, ['admin', 'dokter', 'perawat']);
        $bisaAP  = in_array($role, ['admin', 'dokter']);
        $bisaIcd = in_array($role, ['admin', 'pmik']);
        $bisaRujuk = in_array($role, ['admin', 'dokter']);

        $ro = fn ($boleh) => $boleh ? '' : 'readonly';

        // ===== DAFTAR ISIAN SOAP =====
        $soap = [
            'S' => [
                's_keluhan_utama'       => 'Keluhan utama',
                's_riwayat_sekarang'    => 'Riwayat penyakit sekarang',
                's_riwayat_dahulu'      => 'Riwayat penyakit dahulu',
                's_riwayat_keluarga'    => 'Riwayat penyakit keluarga',
                's_riwayat_pengobatan'  => 'Riwayat pengobatan',
                's_riwayat_sosial'      => 'Riwayat sosial',
                's_riwayat_operasi'     => 'Riwayat operasi',
                's_riwayat_alergi'      => 'Riwayat alergi',
                's_riwayat_imunisasi'   => 'Riwayat imunisasi',
            ],
            'O' => [
                'o_tanda_vital'  => 'Pemeriksaan tanda vital',
                'o_fisik_lain'   => 'Pemeriksaan fisik lainnya',
            ],
            'A' => [
                'a_dx_utama'     => 'Dx utama',
                'a_dx_sekunder'  => 'Dx Sekunder',
            ],
        ];
        $boleh = ['S' => $bisaSO, 'O' => $bisaSO, 'A' => $bisaAP];

        $nonFarmakologis = [
            'p_tindakan_medis'    => 'Tindakan medis',
            'p_konseling_edukasi' => 'Konseling edukasi',
            'p_rencana_lanjut'    => 'Rencana tindak lanjut',
            'p_diet'              => 'Diet',
        ];
    @endphp

    <div class="kb-wrap">

        @if (session('sukses'))
            <div class="kb-alert">{{ session('sukses') }}</div>
        @endif

        {{-- ===== DATA PASIEN (tetap di atas, tidak ikut scroll) ===== --}}
        <div class="kb-card">
            <div class="kb-card-title">
                <span>Data Pasien</span>
                <a href="{{ route('poli.jiwa.riwayat', $pasien->no_rm) }}" class="kb-kembali">&larr; Riwayat Kunjungan</a>
            </div>

            <div class="kb-pasien-grid">
                <div>
                    <div class="kb-field"><label>No RM</label><input type="text" value="{{ $pasien->no_rm }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>Nama</label><input type="text" value="{{ $pasien->nama }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>Jenis Kelamin</label><input type="text" value="{{ $pasien->jenis_kelamin }}" readonly></div>
                </div>
                <div>
                    <div class="kb-field"><label>Tempat, Tgl Lahir</label><input type="text" value="{{ ($pasien->tempat_lahir ?? '-') . '/' . $tglLahirTampil }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>Umur</label><input type="text" value="{{ $umurTampil }}" readonly></div>
                    <div class="kb-field" style="margin-top:6px"><label>No Hp</label><input type="text" value="{{ $pasien->no_hp }}" readonly></div>
                </div>
            </div>
        </div>

        {{-- ===== TAB ===== --}}
        <div class="kb-tabs" role="tablist">
            <button type="button" class="kb-tab aktif" data-tab="assesmen" role="tab"><i class="fa-solid fa-file-pen"></i> Assesment</button>
            <button type="button" class="kb-tab" data-tab="radiologi" role="tab"><i class="fa-solid fa-x-ray"></i> Radiologi</button>
            <button type="button" class="kb-tab" data-tab="resume" role="tab"><i class="fa-solid fa-file-lines"></i> Resume Medis</button>
        </div>

        <form method="POST" action="{{ route('poli.jiwa.assesmen.store') }}" class="kb-form" id="formAssesmen">
            @csrf
            <input type="hidden" name="no_rm" value="{{ $pasien->no_rm }}">
            <input type="hidden" name="id_pendaftaran" value="{{ $id_pendaftaran }}">

            {{-- ===== TAB ASSESMEN (area scroll) ===== --}}
            <div class="kb-pane aktif" id="tab-assesmen">

                {{-- TTV --}}
                <div class="kb-ttv">
                    <div class="kb-ttv-item"><label for="ttv_td">TD</label><input type="text" id="ttv_td" name="ttv_td" {{ $ro($bisaTtv) }} value="{{ old('ttv_td') }}"><small>mmHg</small></div>
                    <div class="kb-ttv-item"><label for="ttv_hr">HR</label><input type="text" id="ttv_hr" name="ttv_hr" {{ $ro($bisaTtv) }} value="{{ old('ttv_hr') }}"><small>x/menit</small></div>
                    <div class="kb-ttv-item"><label for="ttv_spo2">SpO2</label><input type="text" id="ttv_spo2" name="ttv_spo2" {{ $ro($bisaTtv) }} value="{{ old('ttv_spo2') }}"><small>%</small></div>

                    <div class="kb-ttv-item"><label for="ttv_suhu">Suhu</label><input type="text" id="ttv_suhu" name="ttv_suhu" {{ $ro($bisaTtv) }} value="{{ old('ttv_suhu') }}"><small>C</small></div>
                    <div class="kb-ttv-item"><label for="ttv_rr">RR</label><input type="text" id="ttv_rr" name="ttv_rr" {{ $ro($bisaTtv) }} value="{{ old('ttv_rr') }}"><small>x/menit</small></div>
                    <div class="kb-ttv-item"><label for="ttv_lainnya">Lainnya</label><input type="text" id="ttv_lainnya" name="ttv_lainnya" {{ $ro($bisaTtv) }} value="{{ old('ttv_lainnya') }}"></div>

                    <div class="kb-ttv-cek"><input type="checkbox" name="ttv_selesai" value="1" title="Centang bila TTV selesai" {{ $bisaTtv ? '' : 'disabled' }}></div>
                </div>

                <h3 class="kb-judul-soap">SOAP</h3>

                {{-- S, O, A --}}
                @foreach ($soap as $huruf => $items)
                    <div class="kb-soap">
                        <div class="kb-badge">{{ $huruf }}</div>
                        <div class="kb-soap-card">
                            @foreach ($items as $nama => $label)
                                <div class="kb-row">
                                    <label for="{{ $nama }}">{{ $loop->iteration }}. {{ $label }}</label>
                                    <textarea class="kb-input" rows="1" id="{{ $nama }}" name="{{ $nama }}" {{ $ro($boleh[$huruf]) }}>{{ old($nama) }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- P --}}
                <div class="kb-soap">
                    <div class="kb-badge">P</div>
                    <div class="kb-soap-card">
                        <div class="kb-row">
                            <label for="p_resep_obat">1. Resep obat</label>
                            <div class="kb-input-wrap">
                                <textarea class="kb-input" rows="1" id="p_resep_obat" name="p_resep_obat" style="padding-right:90px" {{ $ro($bisaAP) }}>{{ old('p_resep_obat') }}</textarea>
                                <button type="button" class="kb-btn-cetak" onclick="cetakResep()"><i class="fa-solid fa-print"></i> Cetak</button>
                            </div>
                        </div>

                        <div class="kb-grup-label">2. Non Farmakologis</div>
                        @foreach ($nonFarmakologis as $nama => $label)
                            <div class="kb-row sub">
                                <label for="{{ $nama }}">{{ $label }}</label>
                                <textarea class="kb-input" rows="1" id="{{ $nama }}" name="{{ $nama }}" {{ $ro($bisaAP) }}>{{ old($nama) }}</textarea>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kode ICD --}}
                <div class="kb-icd">
                    <h3 class="kb-judul-soap" style="margin-top:4px">Kode ICD</h3>
                    <div class="kb-icd-grid">
                        <div>
                            <div class="kb-icd-label">Penyakit</div>
                            <div class="kb-icd-baris">
                                <input type="text" class="kb-icd-input" id="icd_penyakit" name="icd_penyakit" placeholder="Cari Kode Penyakit ..." autocomplete="off" {{ $ro($bisaIcd) }} value="{{ old('icd_penyakit') }}">
                                <div class="kb-simbol">
                                    <button type="button" data-simbol="icd_dagger" title="Dagger" {{ $bisaIcd ? '' : 'disabled' }}>&dagger;</button>
                                    <button type="button" data-simbol="icd_asterisk" title="Asterisk" {{ $bisaIcd ? '' : 'disabled' }}>&lowast;</button>
                                </div>
                                <i class="fa-solid fa-check kb-centang" data-centang="icd_penyakit"></i>
                            </div>
                            <input type="hidden" name="icd_dagger" id="icd_dagger" value="0">
                            <input type="hidden" name="icd_asterisk" id="icd_asterisk" value="0">
                        </div>
                        <div>
                            <div class="kb-icd-label">Tindakan</div>
                            <div class="kb-icd-baris">
                                <input type="text" class="kb-icd-input" id="icd_tindakan" name="icd_tindakan" placeholder="Cari Kode Tindakan ..." autocomplete="off" {{ $ro($bisaIcd) }} value="{{ old('icd_tindakan') }}">
                                <i class="fa-solid fa-check kb-centang" data-centang="icd_tindakan"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kb-aksi">
                    <button type="submit" class="kb-btn simpan"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    @if ($bisaRujuk)
                        <button type="submit" class="kb-btn" formaction="{{ route('poli.jiwa.rujukan') }}"
                                onclick="return confirm('Kirim rujukan untuk pasien ini?')">Rujuk</button>
                    @endif
                </div>
            </div>

            {{-- ===== TAB RADIOLOGI ===== --}}
            <div class="kb-pane" id="tab-radiologi">
                <div class="kb-kosong">Belum ada hasil radiologi untuk kunjungan ini.</div>
            </div>

            {{-- ===== TAB RESUME MEDIS ===== --}}
            <div class="kb-pane" id="tab-resume">
                <div class="kb-kosong">Resume medis tersedia setelah assesmen disimpan.</div>
            </div>
        </form>
    </div>

    <script>
        // ----- Pindah tab -----
        document.querySelectorAll('.kb-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.kb-tab').forEach(function (t) { t.classList.remove('aktif'); });
                document.querySelectorAll('.kb-pane').forEach(function (p) { p.classList.remove('aktif'); });
                tab.classList.add('aktif');
                document.getElementById('tab-' + tab.dataset.tab).classList.add('aktif');
            });
        });

        // ----- Textarea otomatis membesar -----
        function sesuaikanTinggi(el) {
            el.style.height = 'auto';
            el.style.height = Math.min(el.scrollHeight + 2, 200) + 'px';
            el.style.overflowY = el.scrollHeight > 200 ? 'auto' : 'hidden';
        }
        document.querySelectorAll('textarea.kb-input').forEach(function (el) {
            sesuaikanTinggi(el);
            el.addEventListener('input', function () { sesuaikanTinggi(el); });
        });

        // ----- Tanda centang hijau kode ICD -----
        function perbaruiCentang(input) {
            var c = document.querySelector('[data-centang="' + input.id + '"]');
            if (c) c.classList.toggle('tampil', input.value.trim() !== '');
        }
        ['icd_penyakit', 'icd_tindakan'].forEach(function (id) {
            var el = document.getElementById(id);
            perbaruiCentang(el);
            el.addEventListener('input', function () { perbaruiCentang(el); });
        });

        // ----- Tombol dagger / asterisk -----
        document.querySelectorAll('.kb-simbol button').forEach(function (b) {
            b.addEventListener('click', function () {
                var hidden = document.getElementById(b.dataset.simbol);
                var aktif = hidden.value === '1';
                hidden.value = aktif ? '0' : '1';
                b.classList.toggle('aktif', !aktif);
            });
        });

        // ----- Cetak resep obat -----
        function cetakResep() {
            var isi = document.getElementById('p_resep_obat').value || '-';
            var w = window.open('', '_blank', 'width=600,height=700');
            w.document.write('<html><head><title>Resep Obat</title></head><body style="font-family:sans-serif">'
                + '<h3>Resep Obat</h3><p><b>No. RM:</b> {{ $pasien->no_rm }}<br><b>Nama:</b> {{ $pasien->nama }}</p>'
                + '<pre style="white-space:pre-wrap;font-family:inherit"></pre></body></html>');
            w.document.querySelector('pre').textContent = isi;
            w.document.close();
            w.focus();
            w.print();
        }
    </script>

@endsection