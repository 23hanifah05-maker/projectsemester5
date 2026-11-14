<?php

namespace App\Http\Controllers;

use App\Models\GeneralConsent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralConsentController extends Controller
{
    public function simpan(Request $request)
    {
        $data = $request->validate([
            'id_pendaftaran'        => ['required', 'integer', 'exists:pendaftaran,id_pendaftaran'],
            'gc_nama_pemberi'       => ['required', 'string', 'max:150'],
            'gc_ttd_pemberi'        => ['required', 'string'],
            'gc_nama_penandatangan' => ['required', 'string', 'max:150'],
            'gc_ttd_pasien'         => ['required', 'string'],
            'gc_anggota_keluarga'   => ['nullable', 'string'],
            'gc_tanggal'            => ['required', 'string', 'max:20'],
            'gc_jam'                => ['required', 'string', 'max:10'],
        ]);

        $idPendaftaran = (int) $data['id_pendaftaran'];

        $pathPemberi = $this->simpanTtd($data['gc_ttd_pemberi'], 'pemberi', $idPendaftaran);
        $pathPasien  = $this->simpanTtd($data['gc_ttd_pasien'], 'pasien', $idPendaftaran);

        if (!$pathPemberi || !$pathPasien) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanda tangan tidak valid. Ulangi tanda tangan lalu simpan kembali.',
            ], 422);
        }

        // Hapus file tanda tangan lama bila consent diisi ulang
        $lama = GeneralConsent::where('id_pendaftaran', $idPendaftaran)->first();

        if ($lama) {
            foreach ([$lama->ttd_pemberi, $lama->ttd_pasien] as $file) {
                if ($file && Storage::disk('public')->exists($file)) {
                    Storage::disk('public')->delete($file);
                }
            }
        }

        $anggota = json_decode($data['gc_anggota_keluarga'] ?? '[]', true);

        GeneralConsent::updateOrCreate(
            ['id_pendaftaran' => $idPendaftaran],
            [
                'hak_kewajiban'          => 'setuju',
                'persetujuan_pelayanan'  => 'setuju',
                'pelepasan_informasi'    => 'setuju',
                'pengajuan_keluhan'      => 'setuju',
                'informasi_rawat_jalan'  => 'setuju',
                'kewajiban_pembayaran'   => 'setuju',
                'pernyataan_persetujuan' => 'setuju',
                'konfirmasi'             => true,
                'anggota_keluarga'       => is_array($anggota) ? $anggota : [],
                'ttd_pemberi'            => $pathPemberi,
                'nama_pemberi'           => $data['gc_nama_pemberi'],
                'ttd_pasien'             => $pathPasien,
                'nama_penandatangan'     => $data['gc_nama_penandatangan'],
                'tanggal'                => $data['gc_tanggal'],
                'jam'                    => $data['gc_jam'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'General Consent berhasil disimpan.',
        ]);
    }

    // Simpan data URL PNG sebagai file, kembalikan path (muat di kolom string 255).
    private function simpanTtd(string $dataUrl, string $jenis, int $idPendaftaran): ?string
    {
        if (!preg_match('/^data:image\/png;base64,(.+)$/s', $dataUrl, $cocok)) {
            return null;
        }

        $biner = base64_decode($cocok[1], true);

        if ($biner === false) {
            return null;
        }

        $path = 'general_consent/ttd_' . $jenis . '_' . $idPendaftaran . '_' . time() . '.png';

        Storage::disk('public')->put($path, $biner);

        return $path;
    }
}