<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        // Sementara tidak mengambil data dari tabel pasiens
        $pasien = collect();

        return view('pendaftaran.index', compact(
            'pasien',
            'keyword',
            'dari',
            'sampai'
        ));
    }

    public function create()
    {
        return view('pendaftaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required',
            'nama_pasien' => 'required',
            'nik' => 'nullable',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable',
            'alamat' => 'nullable',
        ]);

        // Sementara belum menyimpan ke database pasien

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data pendaftaran berhasil.');
    }

    public function show($id)
    {
        return redirect()->route('pendaftaran.index');
    }

    public function edit($id)
    {
        return redirect()->route('pendaftaran.index');
    }

    public function update(Request $request, $id)
    {
        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function tambah($id)
    {
        return redirect()->route('pendaftaran.index');
    }
}