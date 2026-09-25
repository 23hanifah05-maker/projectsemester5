<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poli Obgyn</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f5f5f5;
        }

        .header {
            background: #b71c1c;
            color: white;
            padding: 20px 30px;
            font-size: 24px;
            font-weight: bold;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            color: #b71c1c;
            margin-bottom: 15px;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background: #b71c1c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .button:hover {
            background: #8e0000;
        }
    </style>
</head>

<body>

    <div class="header">
        👩‍⚕️ Poli Obgyn
    </div>

    <div class="container">

        <div class="card">
            <h1>Poli Obgyn</h1>

            <p>
                Selamat datang di halaman Poli Obgyn.
                Halaman ini digunakan untuk pengelolaan informasi pelayanan pasien Poli Obgyn.
            </p>

            <a href="{{ route('dashboard') }}" class="button">
                ← Kembali ke Dashboard
            </a>
        </div>

    </div>

</body>
</html>