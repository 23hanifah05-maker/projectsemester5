<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poli Jantung</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .header {
            background: #b71c1c;
            color: white;
            padding: 20px 30px;
            font-size: 25px;
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
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            background: #b71c1c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <div class="header">
        🩺 Poli Jantung
    </div>

    <div class="container">
        <div class="card">

            <h1>Poli Jantung</h1>

            <p>
                Selamat datang di halaman Poli Jantung.
            </p>

            <a href="{{ route('dashboard') }}" class="button">
                ← Kembali ke Dashboard
            </a>

        </div>
    </div>

</body>
</html>