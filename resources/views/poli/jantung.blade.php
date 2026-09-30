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
            @foreach ($data_poli as $poli)
                <tr>
                    <td>{{ $poli->id_poli }}</td>
                    <td>{{ $poli->nama_poli }}</td>
                    <td>{{ $poli->nama_dokter }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>