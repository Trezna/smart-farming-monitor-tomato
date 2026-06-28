<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Smart Farming</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f7e8, #d9eebc);
            display: flex; align-items: center; justify-content: center;
        }
        .container { text-align: center; padding: 40px; }
        .icon { font-size: 5rem; margin-bottom: 16px; }
        h1 { font-size: 1.8rem; font-weight: 800; color: #1a2e0a; margin-bottom: 8px; }
        p { color: #6b7280; font-size: 1rem; margin-bottom: 24px; }
        a {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px; background: #3b6d11;
            color: white; border-radius: 10px;
            font-weight: 600; text-decoration: none;
            transition: all 0.2s;
        }
        a:hover { background: #4a8915; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔒</div>
        <h1>403 — Akses Ditolak</h1>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini.<br>Halaman ini hanya dapat diakses oleh Administrator.</p>
        <a href="{{ url()->previous() }}">← Kembali</a>
    </div>
</body>
</html>
