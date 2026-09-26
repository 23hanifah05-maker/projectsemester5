@extends('layouts.app')

@section('title', 'Poli Obgyn - Klinik Utama Merah Putih')
@section('header-icon', '🩺')
@section('header-title', 'Poli Obgyn')

@section('extra-css')
    {{-- Pakai CSS yang sama dengan halaman Pendaftaran karena struktur tabel & modalnya sama --}}
    <link rel="stylesheet" href="{{ asset('css/pendaftaran.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pendaftaran-modal.css') }}">
@endsection

@section('content')

    {{-- =====================================================
         DATA DUMMY PASIEN POLI OBGYN
         (5 pasien perempuan dari Master Data Pendaftaran:
         RM-0002, RM-0004, RM-0006, RM-0008, RM-0010)
         Data dilengkapi penuh (NIK, PJ, dll) agar modal Detail
         dan Edit bisa berfungsi sama seperti di Master Data.
    ===================================================== --}}
    @php
        $pasien = $pasien ?? [
            (object) [
                'no_rm' => 'RM-0002',
                'nama_pasien' => 'Siti Aminah',
                'nik' => '3515025205850002',
                'tgl_lahir' => '1985-05-12',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Diponegoro No. 25',
                'no_hp' => '081355667788',
                'tempat_lahir' => 'Surabaya',
                'suku' => 'Jawa',
                'bangsa' => 'Indonesia',
                'bahasa' => 'Indonesia',
                'umur' => '41 Tahun',
                'kelurahan' => 'Lemahputro',
                'agama' => 'Islam',
                'kecamatan' => 'Sidoarjo',
                'pendidikan' => 'S1',
                'kabupaten' => 'Sidoarjo',
                'pekerjaan' => 'Guru',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '61213',
                'pembayaran' => 'BPJS',
                'poli' => 'Poli Obgyn',
                'dokter' => '',
                'status' => 'Sudah Terdaftar',
                'pj_nama' => 'Budi Santoso',
                'pj_hubungan' => 'Suami',
                'pj_jenis_kelamin' => 'Laki-laki',
                'pj_no_hp' => '081234567890',
            ],
            (object) [
                'no_rm' => 'RM-0004',
                'nama_pasien' => 'Dewi Lestari',
                'nik' => '3515044507920004',
                'tgl_lahir' => '1992-07-05',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Ahmad Yani No. 15',
                'no_hp' => '082233445566',
                'tempat_lahir' => 'Surabaya',
                'suku' => 'Jawa',
                'bangsa' => 'Indonesia',
                'bahasa' => 'Indonesia',
                'umur' => '34 Tahun',
                'kelurahan' => 'Krian',
                'agama' => 'Islam',
                'kecamatan' => 'Krian',
                'pendidikan' => 'S1',
                'kabupaten' => 'Sidoarjo',
                'pekerjaan' => 'Pegawai Swasta',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '61262',
                'pembayaran' => 'BPJS',
                'poli' => 'Poli Obgyn',
                'dokter' => '',
                'status' => 'Sudah Terdaftar',
                'pj_nama' => 'Andi Pratama',
                'pj_hubungan' => 'Suami',
                'pj_jenis_kelamin' => 'Laki-laki',
                'pj_no_hp' => '082112223333',
            ],
            (object) [
                'no_rm' => 'RM-0006',
                'nama_pasien' => 'Lina Marlina',
                'nik' => '3515065508800006',
                'tgl_lahir' => '1980-08-15',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Pahlawan No. 32',
                'no_hp' => '081366778899',
                'tempat_lahir' => 'Sidoarjo',
                'suku' => 'Madura',
                'bangsa' => 'Indonesia',
                'bahasa' => 'Indonesia',
                'umur' => '46 Tahun',
                'kelurahan' => 'Buduran',
                'agama' => 'Islam',
                'kecamatan' => 'Buduran',
                'pendidikan' => 'SMA',
                'kabupaten' => 'Sidoarjo',
                'pekerjaan' => 'Ibu Rumah Tangga',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '61252',
                'pembayaran' => 'Umum',
                'poli' => 'Poli Obgyn',
                'dokter' => '',
                'status' => 'Sudah Terdaftar',
                'pj_nama' => 'Rudi Hartono',
                'pj_hubungan' => 'Suami',
                'pj_jenis_kelamin' => 'Laki-laki',
                'pj_no_hp' => '081377889900',
            ],
            (object) [
                'no_rm' => 'RM-0008',
                'nama_pasien' => 'Nur Aisyah',
                'nik' => '3515084203840008',
                'tgl_lahir' => '1984-03-02',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Hasanudin No. 18',
                'no_hp' => '083855667788',
                'tempat_lahir' => 'Gresik',
                'suku' => 'Jawa',
                'bangsa' => 'Indonesia',
                'bahasa' => 'Indonesia',
                'umur' => '42 Tahun',
                'kelurahan' => 'Gedangan',
                'agama' => 'Islam',
                'kecamatan' => 'Gedangan',
                'pendidikan' => 'SMA',
                'kabupaten' => 'Sidoarjo',
                'pekerjaan' => 'Penjahit',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '61254',
                'pembayaran' => 'Umum',
                'poli' => 'Poli Obgyn',
                'dokter' => '',
                'status' => 'Sudah Terdaftar',
                'pj_nama' => 'Fajar Ramadhan',
                'pj_hubungan' => 'Anak',
                'pj_jenis_kelamin' => 'Laki-laki',
                'pj_no_hp' => '083811223344',
            ],
            (object) [
                'no_rm' => 'RM-0010',
                'nama_pasien' => 'Sri Wahyuni',
                'nik' => '3515105807750010',
                'tgl_lahir' => '1975-07-18',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Mawar No. 12',
                'no_hp' => '081298112233',
                'tempat_lahir' => 'Sidoarjo',
                'suku' => 'Jawa',
                'bangsa' => 'Indonesia',
                'bahasa' => 'Indonesia',
                'umur' => '51 Tahun',
                'kelurahan' => 'Candi',
                'agama' => 'Islam',
                'kecamatan' => 'Candi',
                'pendidikan' => 'SMA',
                'kabupaten' => 'Sidoarjo',
                'pekerjaan' => 'Wiraswasta',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '61271',
                'pembayaran' => 'Umum',
                'poli' => 'Poli Obgyn',
                'dokter' => '',
                'status' => 'Sudah Terdaftar',
                'pj_nama' => 'Agus Setiawan',
                'pj_hubungan' => 'Suami',
                'pj_jenis_kelamin' => 'Laki-laki',
                'pj_no_hp' => '081245678901',
            ],
        ];
    @endphp

    <div class="panel">

        <div class="panel-head">
            <a href="#" class="master-data-title">Daftar Pasien</a>
        </div>

        <form method="GET" action="{{ route('poli.obgyn') }}" class="filter-row">
            <div class="filter-item">
                <label>Periode</label>
                <input type="date" name="dari" value="{{ request('dari') }}">
                <span>s.d</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}">
            </div>

            <div class="filter-item filter-keyword">
                <label>Keyword</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari nama, NIK, atau No. RM">
            </div>
        </form>

        <table class="table-pendaftaran">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>NIK</th>
                    <th>Tgl. Lahir</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse (($pasien ?? []) as $index => $item)
                    <tr class="row-pasien" data-pasien='@json($item)' title="Klik dua kali untuk melihat detail">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->no_rm }}</td>
                        <td>{{ $item->nama_pasien }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d-m-Y') }}</td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->status }}</td>
                        <td class="aksi-cell">
                            <button type="button" class="btn-aksi" title="Lihat Detail"
                                    onclick="event.stopPropagation(); bukaDetail(this)">✔</button>
                            <button type="button" class="btn-aksi" title="Tambah Kunjungan"
                                    onclick="event.stopPropagation(); bukaKunjungan(this)">+</button>
                            <button type="button" class="btn-aksi" title="Edit Data"
                                    onclick="event.stopPropagation(); bukaEdit(this)">✎</button>
                            <button type="button" class="btn-aksi" title="Hapus Data"
                                    onclick="event.stopPropagation(); hapusPasien(this)">🗑</button>
                        </td>
                    </tr>
                @empty
                    {{-- Baris kosong sebagai placeholder, selama belum ada data --}}
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="aksi-cell">
                                <span class="btn-aksi">✔</span>
                                <span class="btn-aksi">+</span>
                                <span class="btn-aksi">✎</span>
                                <span class="btn-aksi">🗑</span>
                            </td>
                        </tr>
                    @endfor
                @endforelse
            </tbody>
        </table>

    </div>


    {{-- =====================================================
         MODAL DETAIL PASIEN
    ===================================================== --}}
    <div class="modal-overlay" id="pasienModal">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Data Pasien</h3>
                <button type="button" class="modal-close" onclick="closePasienModal()">&times;</button>
            </div>

            <div class="modal-section-title">Identitas Pasien</div>
            <div class="modal-grid">
                <div class="modal-row"><span class="modal-label">No. Rekam Medis</span><span>:</span><span id="m-no-rm"></span></div>
                <div class="modal-row"><span class="modal-label">No. HP</span><span>:</span><span id="m-hp"></span></div>
                <div class="modal-row"><span class="modal-label">Nama Lengkap</span><span>:</span><span id="m-nama"></span></div>
                <div class="modal-row"><span class="modal-label">Suku</span><span>:</span><span id="m-suku"></span></div>
                <div class="modal-row"><span class="modal-label">NIK</span><span>:</span><span id="m-nik"></span></div>
                <div class="modal-row"><span class="modal-label">Bangsa</span><span>:</span><span id="m-bangsa"></span></div>
                <div class="modal-row"><span class="modal-label">Tempat/Tgl Lahir</span><span>:</span><span id="m-ttl"></span></div>
                <div class="modal-row"><span class="modal-label">Bahasa</span><span>:</span><span id="m-bahasa"></span></div>
                <div class="modal-row"><span class="modal-label">Umur</span><span>:</span><span id="m-umur"></span></div>
                <div class="modal-row"><span class="modal-label">Alamat</span><span>:</span><span id="m-alamat"></span></div>
                <div class="modal-row"><span class="modal-label">Jenis Kelamin</span><span>:</span><span id="m-jk"></span></div>
                <div class="modal-row"><span class="modal-label">Kelurahan</span><span>:</span><span id="m-kelurahan"></span></div>
                <div class="modal-row"><span class="modal-label">Agama</span><span>:</span><span id="m-agama"></span></div>
                <div class="modal-row"><span class="modal-label">Kecamatan</span><span>:</span><span id="m-kecamatan"></span></div>
                <div class="modal-row"><span class="modal-label">Pendidikan</span><span>:</span><span id="m-pendidikan"></span></div>
                <div class="modal-row"><span class="modal-label">Kota/Kabupaten</span><span>:</span><span id="m-kabupaten"></span></div>
                <div class="modal-row"><span class="modal-label">Pekerjaan</span><span>:</span><span id="m-pekerjaan"></span></div>
                <div class="modal-row"><span class="modal-label">Provinsi</span><span>:</span><span id="m-provinsi"></span></div>
                <div class="modal-row"><span class="modal-label">Kode Pos</span><span>:</span><span id="m-kodepos"></span></div>
                <div class="modal-row"><span class="modal-label">Pembayaran</span><span>:</span><span id="m-pembayaran"></span></div>
                <div class="modal-row"><span class="modal-label">Poli</span><span>:</span><span id="m-poli"></span></div>
                <div class="modal-row"><span class="modal-label">Dokter</span><span>:</span><span id="m-dokter"></span></div>
            </div>

            <div class="modal-section-title">Penanggung Jawab</div>
            <div class="modal-grid">
                <div class="modal-row"><span class="modal-label">Nama</span><span>:</span><span id="m-pj-nama"></span></div>
                <div class="modal-row"><span class="modal-label">Hubungan</span><span>:</span><span id="m-pj-hubungan"></span></div>
                <div class="modal-row"><span class="modal-label">Jenis Kelamin</span><span>:</span><span id="m-pj-jk"></span></div>
                <div class="modal-row"><span class="modal-label">No HP</span><span>:</span><span id="m-pj-hp"></span></div>
            </div>
        </div>
    </div>


    {{-- =====================================================
         MODAL TAMBAH KUNJUNGAN
    ===================================================== --}}
    <div class="custom-modal-overlay" id="kunjunganModal">
        <div class="custom-modal-box">
            <div class="custom-modal-header">
                <h3>Tambah Kunjungan</h3>
                <button type="button" class="custom-modal-close" onclick="tutupKunjungan()">&times;</button>
            </div>

            <div class="custom-modal-body">
                <div class="form-group">
                    <label>No. Rekam Medis</label>
                    <input type="text" id="kunjunganRm" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" id="kunjunganNama" readonly>
                </div>
                <div class="form-group">
                    <label>Tanggal Kunjungan</label>
                    <input type="date" id="kunjunganTanggal">
                </div>
                <div class="form-group">
                    <label>Poli</label>
                    <select id="kunjunganPoli">
                        <option value="">-- Pilih Poli --</option>
                        <option value="Poli Umum">Poli Umum</option>
                        <option value="Poli Syaraf">Poli Syaraf</option>
                        <option value="Poli Obgyn">Poli Obgyn</option>
                        <option value="Poli Anak">Poli Anak</option>
                        <option value="Poli Gigi">Poli Gigi</option>
                        <option value="Poli Mata">Poli Mata</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Keluhan</label>
                    <textarea id="kunjunganKeluhan" placeholder="Masukkan keluhan pasien..."></textarea>
                </div>
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="tutupKunjungan()">Batal</button>
                <button type="button" class="btn-modal btn-modal-primary" onclick="simpanKunjungan()">Simpan Kunjungan</button>
            </div>
        </div>
    </div>


    {{-- =====================================================
         MODAL EDIT
         Nama Pasien dan NIK dibuat READONLY (terkunci).
         Hanya Alamat dan No. HP yang bisa diubah.
    ===================================================== --}}
    <div class="custom-modal-overlay" id="editModal">
        <div class="custom-modal-box">
            <div class="custom-modal-header">
                <h3>Edit Data Pasien</h3>
                <button type="button" class="custom-modal-close" onclick="tutupEdit()">&times;</button>
            </div>

            <div class="custom-modal-body">
                <input type="hidden" id="editRow">

                <div class="form-group">
                    <label>No. Rekam Medis</label>
                    <input type="text" id="editRm" readonly style="background-color:#e9e9e9; color:#666; cursor:not-allowed;">
                </div>

                <div class="form-group">
                    <label>Nama Pasien</label>
                    <input type="text" id="editNama" readonly class="input-locked"
                           title="Nama tidak dapat diubah dari sini"
                           style="background-color:#e9e9e9; color:#666; cursor:not-allowed;">
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" id="editNik" inputmode="numeric" readonly class="input-locked"
                           title="NIK tidak dapat diubah dari sini"
                           style="background-color:#e9e9e9; color:#666; cursor:not-allowed;">
                </div>

                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" id="editHp" inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea id="editAlamat"></textarea>
                </div>
            </div>

            <div class="custom-modal-footer">
                <button type="button" class="btn-modal btn-modal-secondary" onclick="tutupEdit()">Batal</button>
                <button type="button" class="btn-modal btn-modal-primary" onclick="simpanEdit()">Simpan Perubahan</button>
            </div>
        </div>
    </div>

@endsection


{{-- =====================================================
     JAVASCRIPT
===================================================== --}}
@section('extra-js')
<script>

function ambilDataBaris(button) {
    const row = button.closest('.row-pasien');
    if (!row) return null;
    try {
        return { row: row, data: JSON.parse(row.getAttribute('data-pasien')) };
    } catch (error) {
        console.error(error);
        return null;
    }
}

function formatTanggal(tanggal) {
    if (!tanggal) return '-';
    const d = new Date(tanggal);
    if (isNaN(d.getTime())) return tanggal;
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' });
}

function isiDetail(data) {
    document.getElementById('m-no-rm').textContent = data.no_rm ?? '-';
    document.getElementById('m-hp').textContent = data.no_hp ?? '-';
    document.getElementById('m-nama').textContent = data.nama_pasien ?? '-';
    document.getElementById('m-suku').textContent = data.suku ?? '-';
    document.getElementById('m-nik').textContent = data.nik ?? '-';
    document.getElementById('m-bangsa').textContent = data.bangsa ?? '-';
    document.getElementById('m-ttl').textContent = (data.tempat_lahir ?? '-') + ' / ' + formatTanggal(data.tgl_lahir);
    document.getElementById('m-bahasa').textContent = data.bahasa ?? '-';
    document.getElementById('m-umur').textContent = data.umur ?? '-';
    document.getElementById('m-alamat').textContent = data.alamat ?? '-';
    document.getElementById('m-jk').textContent = data.jenis_kelamin ?? '-';
    document.getElementById('m-kelurahan').textContent = data.kelurahan ?? '-';
    document.getElementById('m-agama').textContent = data.agama ?? '-';
    document.getElementById('m-kecamatan').textContent = data.kecamatan ?? '-';
    document.getElementById('m-pendidikan').textContent = data.pendidikan ?? '-';
    document.getElementById('m-kabupaten').textContent = data.kabupaten ?? '-';
    document.getElementById('m-pekerjaan').textContent = data.pekerjaan ?? '-';
    document.getElementById('m-provinsi').textContent = data.provinsi ?? '-';
    document.getElementById('m-kodepos').textContent = data.kode_pos ?? '-';
    document.getElementById('m-pembayaran').textContent = data.pembayaran ?? '-';
    document.getElementById('m-poli').textContent = data.poli ?? '-';
    document.getElementById('m-dokter').textContent = (data.dokter && data.dokter.length > 0) ? data.dokter : '-';
    document.getElementById('m-pj-nama').textContent = data.pj_nama ?? '-';
    document.getElementById('m-pj-hubungan').textContent = data.pj_hubungan ?? '-';
    document.getElementById('m-pj-jk').textContent = data.pj_jenis_kelamin ?? '-';
    document.getElementById('m-pj-hp').textContent = data.pj_no_hp ?? '-';
}

function bukaDetail(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;
    isiDetail(hasil.data);
    document.getElementById('pasienModal').classList.add('show');
}

function closePasienModal() {
    document.getElementById('pasienModal').classList.remove('show');
}

function bukaKunjungan(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;
    const data = hasil.data;

    document.getElementById('kunjunganRm').value = data.no_rm ?? '';
    document.getElementById('kunjunganNama').value = data.nama_pasien ?? '';

    const today = new Date();
    const tanggal = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
    document.getElementById('kunjunganTanggal').value = tanggal;
    document.getElementById('kunjunganPoli').value = data.poli ?? '';
    document.getElementById('kunjunganKeluhan').value = '';

    document.getElementById('kunjunganModal').classList.add('show');
}

function tutupKunjungan() {
    document.getElementById('kunjunganModal').classList.remove('show');
}

function simpanKunjungan() {
    const rm = document.getElementById('kunjunganRm').value;
    const nama = document.getElementById('kunjunganNama').value;
    const tanggal = document.getElementById('kunjunganTanggal').value;
    const poli = document.getElementById('kunjunganPoli').value;
    const keluhan = document.getElementById('kunjunganKeluhan').value;

    if (!tanggal) { alert('Tanggal kunjungan wajib diisi.'); return; }
    if (!poli) { alert('Silakan pilih poli.'); return; }

    alert(
        'Kunjungan berhasil dibuat.\n\n' +
        'No. RM : ' + rm + '\n' +
        'Nama   : ' + nama + '\n' +
        'Tanggal: ' + formatTanggal(tanggal) + '\n' +
        'Poli   : ' + poli + '\n' +
        'Keluhan: ' + (keluhan || '-')
    );

    tutupKunjungan();
}

function bukaEdit(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;
    const data = hasil.data;
    const row = hasil.row;

    document.getElementById('editRow').value = Array.from(document.querySelectorAll('.row-pasien')).indexOf(row);
    document.getElementById('editRm').value = data.no_rm ?? '';
    document.getElementById('editNama').value = data.nama_pasien ?? '';
    document.getElementById('editNik').value = data.nik ?? '';
    document.getElementById('editHp').value = data.no_hp ?? '';
    document.getElementById('editAlamat').value = data.alamat ?? '';

    document.getElementById('editModal').classList.add('show');
}

function tutupEdit() {
    document.getElementById('editModal').classList.remove('show');
}

function simpanEdit() {
    const index = parseInt(document.getElementById('editRow').value);
    const rows = document.querySelectorAll('.row-pasien');
    const row = rows[index];

    if (!row) { alert('Data pasien tidak ditemukan.'); return; }

    const data = JSON.parse(row.getAttribute('data-pasien'));

    // Hanya Alamat & No. HP yang boleh diubah. Nama & NIK sengaja tidak disentuh.
    data.no_hp = document.getElementById('editHp').value;
    data.alamat = document.getElementById('editAlamat').value;

    row.setAttribute('data-pasien', JSON.stringify(data));

    const kolom = row.querySelectorAll('td');
    kolom[6].textContent = data.alamat; // kolom Alamat

    alert('Data pasien berhasil diperbarui.');
    tutupEdit();
}

function hapusPasien(button) {
    const hasil = ambilDataBaris(button);
    if (!hasil) return;
    const data = hasil.data;
    const row = hasil.row;

    const yakin = confirm(
        'Apakah kamu yakin ingin menghapus data pasien ini?\n\n' +
        'No. RM : ' + data.no_rm + '\n' +
        'Nama   : ' + data.nama_pasien
    );

    if (!yakin) return;

    row.remove();
    perbaruiNomor();
    alert('Data pasien berhasil dihapus.');
}

function perbaruiNomor() {
    document.querySelectorAll('.row-pasien').forEach(function (row, index) {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.row-pasien').forEach(function (row) {
        row.addEventListener('dblclick', function () {
            const data = JSON.parse(this.getAttribute('data-pasien'));
            isiDetail(data);
            document.getElementById('pasienModal').classList.add('show');
        });
    });

    document.getElementById('pasienModal')?.addEventListener('click', function (event) {
        if (event.target === this) closePasienModal();
    });

    document.getElementById('kunjunganModal')?.addEventListener('click', function (event) {
        if (event.target === this) tutupKunjungan();
    });

    document.getElementById('editModal')?.addEventListener('click', function (event) {
        if (event.target === this) tutupEdit();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePasienModal();
            tutupKunjungan();
            tutupEdit();
        }
    });
});

</script>
@endsection