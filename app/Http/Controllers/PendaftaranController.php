<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        // TODO: ganti dengan query Model Pasien/Pendaftaran, contoh:
        // $pasien = Pasien::when($request->keyword, fn ($q) =>
        //                $q->where('nama_pasien', 'like', '%'.$request->keyword.'%')
        //                  ->orWhere('nik', 'like', '%'.$request->keyword.'%'))
        //            ->when($request->dari && $request->sampai, fn ($q) =>
        //                $q->whereBetween('created_at', [$request->dari, $request->sampai]))
        //            ->latest()
        //            ->get();

        $pasien = collect(); // kosong dulu -> tabel tampil dengan baris placeholder

        return view('pendaftaran.index', compact('pasien'));
    }

    public function create()
    {
        return view('pendaftaran.create');
    }

    public function store(Request $request)
    {
        // TODO: validasi + simpan ke database
        return redirect()->route('pendaftaran.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show($id)
    {
        // TODO: tampilkan detail pasien
        return view('pendaftaran.show', compact('id'));
    }

    public function edit($id)
    {
        // TODO: form edit pasien
        return view('pendaftaran.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // TODO: validasi + update ke database
        return redirect()->route('pendaftaran.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // TODO: hapus data pasien
        return redirect()->route('pendaftaran.index')->with('success', 'Data pasien berhasil dihapus.');
    }

    public function tambah($id)
    {
        // Tombol "+" di kolom Aksi -> misal untuk tambah kunjungan baru bagi pasien ini
        return redirect()->route('pendaftaran.index');
    }
}