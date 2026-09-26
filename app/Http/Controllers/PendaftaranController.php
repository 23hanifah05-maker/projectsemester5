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

        $pasien = Pasien::query()

            // SEARCH KEYWORD
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama_pasien', 'like', '%' . $keyword . '%')
                      ->orWhere('no_rm', 'like', '%' . $keyword . '%')
                      ->orWhere('nik', 'like', '%' . $keyword . '%');
                });
            })

            // FILTER TANGGAL
            ->when($dari, function ($query) use ($dari) {
                $query->whereDate('created_at', '>=', $dari);
            })

            ->when($sampai, function ($query) use ($sampai) {
                $query->whereDate('created_at', '<=', $sampai);
            })

            ->latest()
            ->get();

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
            'no_rm' => 'required|unique:pasiens,no_rm',
            'nama_pasien' => 'required',
            'nik' => 'nullable',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable',
            'alamat' => 'nullable',
        ]);

        Pasien::create($request->all());

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pasien = Pasien::findOrFail($id);

        return view('pendaftaran.show', compact('pasien'));
    }

    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);

        return view('pendaftaran.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'no_rm' => 'required|unique:pasiens,no_rm,' . $id,
            'nama_pasien' => 'required',
            'nik' => 'nullable',
            'tgl_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable',
            'alamat' => 'nullable',
        ]);

        $pasien->update($request->all());

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);

        $pasien->delete();

        return redirect()
            ->route('pendaftaran.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }

    public function tambah($id)
    {
        $pasien = Pasien::findOrFail($id);

        return redirect()->route('pendaftaran.index');
    }
}