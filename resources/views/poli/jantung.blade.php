<!DOCTYPE html>
<html>
<head>
    <title>Daftar Poli</title>
</head>
<body>
    <h2>Data Poli Klinik Utama Merah Putih</h2>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID Poli</th>
                <th>Nama Poli</th>
                <th>Nama Dokter</th>
            </tr>
        </thead>
        <tbody>
            @extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Poli Jantung</h2>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID Poli</th>
                <th>Nama Poli</th>
                <th>Nama Dokter</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data_poli ?? [] as $poli)
    <tr>
        <td>{{ $poli->id_poli }}</td>
        <td>{{ $poli->nama_poli }}</td>
        <td>{{ $poli->nama_dokter }}</td>
    </tr>
@empty
    <tr>
        <td colspan="3" class="text-center">Belum ada data untuk Poli Jantung di database.</td>
    </tr>
@endforelse
        </tbody>
    </table>
</div>
@endsection
        </tbody>
    </table>
</body>
</html>