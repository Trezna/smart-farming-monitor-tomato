<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Smart Farming Tomat">
    <title>Login | Smart Farming Tomat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3a05 0%, #3b6d11 50%, #4a8915 100%);
            display: flex; align-items: center; justify-content: center;
            padding: 20px;
            position: relative; overflow: hidden;
        }
        /* Decorative circles */
        body::before {
            content: '';
            position: absolute; top: -100px; right: -100px;
            width: 400px; height: 400px;
            background: rgba(255,255,255,0.05); border-radius: 50%;
        }
        body::after {
            content: '';
            position: absolute; bottom: -80px; left: -80px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.04); border-radius: 50%;
        }
        .login-container {
            width: 100%; max-width: 420px;
            position: relative; z-index: 10;
        }
        .login-logo {
            text-align: center; margin-bottom: 28px;
        }
        .login-logo .icon-box {
            width: 70px; height: 70px;
            background: rgba(255,255,255,0.15);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .login-logo h1 { color: white; font-size: 1.5rem; font-weight: 800; }
        .login-logo p { color: rgba(255,255,255,0.65); font-size: 0.85rem; margin-top: 4px; }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 32px 30px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }
        .login-card h2 { font-size: 1.3rem; font-weight: 700; color: #1a2e0a; margin-bottom: 6px; }
        .login-card p.subtitle { font-size: 0.84rem; color: #6b7280; margin-bottom: 24px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.84rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .input-wrapper { position: relative; }
        .input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #9ca3af;
        }
        .form-input {
            width: 100%; padding: 11px 12px 11px 38px;
            border: 1.5px solid #e5e7eb; border-radius: 10px;
            font-size: 0.88rem; color: #111827;
            transition: all 0.2s; background: #fafafa;
        }
        .form-input:focus { outline: none; border-color: #3b6d11; box-shadow: 0 0 0 3px rgba(59,109,17,0.1); background: white; }
        .form-input.error { border-color: #ef4444; }
        .error-text { font-size: 0.78rem; color: #ef4444; margin-top: 5px; display: flex; align-items: center; gap: 4px; }
        .btn-login {
            width: 100%; padding: 12px;
            background: linear-gradient(135deg, #3b6d11, #4a8915);
            color: white; border: none; border-radius: 10px;
            font-size: 0.95rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s;
            margin-top: 8px;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(59,109,17,0.4); }
        .btn-login:active { transform: translateY(0); }
        .remember-row { display: flex; align-items: center; margin-bottom: 20px; }
        .remember-row input[type="checkbox"] { margin-right: 8px; accent-color: #3b6d11; }
        .remember-row label { font-size: 0.84rem; color: #374151; cursor: pointer; }
        .demo-accounts {
            margin-top: 20px; padding: 14px 16px;
            background: #f0f7e8; border-radius: 10px;
            border: 1px dashed #a8d26b;
        }
        .demo-accounts p { font-size: 0.78rem; color: #3b6d11; font-weight: 700; margin-bottom: 8px; }
        .demo-accounts .account { font-size: 0.78rem; color: #4a5568; margin-bottom: 4px; }
        .demo-accounts .account span { font-weight: 600; color: #1a2e0a; }
    </style>
</head>
<body>
<div class="login-container">
    <div class="login-logo">
        <div class="icon-box">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1>Smart Farming Tomat</h1>
        <p>Sistem Monitoring & Analisis IoT</p>
    </div>

    <div class="login-card">
        <h2>Selamat Datang</h2>
        <p class="subtitle">Masuk untuk mengakses sistem monitoring</p>

        @if($errors->any())
        <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:12px 14px;margin-bottom:18px;font-size:0.84rem;color:#991b1b;display:flex;align-items:center;gap:8px;">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ $errors->first('email') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" id="loginForm">
            @csrf

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <input type="email" id="email" name="email"
                           class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                           value="{{ old('email') }}"
                           placeholder="admin@smartfarming.com"
                           autocomplete="email" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input type="password" id="password" name="password"
                           class="form-input"
                           placeholder="••••••••"
                           autocomplete="current-password" required>
                </div>
            </div>

            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                Masuk ke Sistem
            </button>
        </form>

    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.textContent = 'Memproses...';
    btn.disabled = true;
});
</script>
</body>
</html>
