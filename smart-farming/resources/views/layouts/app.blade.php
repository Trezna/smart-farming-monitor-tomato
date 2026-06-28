<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Smart Farming Tomat - Sistem monitoring dan analisis data sensor IoT irigasi tomat berbasis Machine Learning">
    <title>@yield('title', 'Smart Farming Tomat') | Smart Farming</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --green-primary: #3b6d11;
            --green-dark:    #27500A;
            --green-light:   #639922;
            --green-50:      #f0f7e8;
            --green-100:     #d9eebc;
            --sidebar-bg:    #27500A;
        }
        * { font-family: 'Inter', sans-serif; }
        body { background-color: #f8f9fa; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #27500A;
            position: fixed;
            left: 0; top: 0;
            display: flex; flex-direction: column;
            z-index: 50;
            box-shadow: 4px 0 20px rgba(0,0,0,0.18);
            transition: transform 0.3s ease;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .sidebar-brand h1 {
            font-size: 1.1rem;
            font-weight: 800;
            color: white;
            letter-spacing: -0.3px;
        }
        .sidebar-brand p { font-size: 0.72rem; color: rgba(255,255,255,0.6); margin-top: 2px; }
        .sidebar-icon-box {
            width: 42px; height: 42px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-right: 12px; flex-shrink: 0;
        }
        .nav-item {
            display: flex; align-items: center;
            padding: 10px 16px;
            margin: 2px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-item:hover { background: rgba(255,255,255,0.10); color: white; }
        .nav-item.active { background: #3b6d11; color: white; font-weight: 600; }
        .nav-item svg { margin-right: 10px; flex-shrink: 0; }
        .nav-section-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: rgba(255,255,255,0.4);
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 16px 28px 6px;
        }
        /* Main content */
        .main-content { margin-left: 260px; min-height: 100vh; }
        .topbar {
            background: white;
            border-bottom: 1px solid #e8f0e0;
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
            box-shadow: 0 1px 8px rgba(0,0,0,0.06);
        }
        .topbar-title { font-size: 1.125rem; font-weight: 600; color: #1a1a1a; }
        .topbar-subtitle { font-size: 0.8125rem; color: #6b7280; margin-top: 1px; }
        .page-content { padding: 28px; }
        /* Cards */
        .card { background: #ffffff; border-radius: 12px; border: 1px solid #e9ecef; box-shadow: 0 1px 4px rgba(0,0,0,0.05); }
        .card-header {
            padding: 18px 22px 0;
            font-size: 0.875rem; font-weight: 700; color: #1a1a1a;
        }
        .card-body { padding: 18px 22px; }
        /* Metric cards */
        .metric-card {
            background: white; border-radius: 12px;
            padding: 22px; display: flex; align-items: flex-start;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #e9ecef;
            transition: transform 0.2s, box-shadow 0.2s;
            border-left: 4px solid var(--green-primary);
        }
        .metric-card:hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.09); }
        .metric-icon {
            width: 48px; height: 48px;
            background: var(--green-50);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-right: 16px;
        }
        .metric-value { font-size: 1.75rem; font-weight: 700; color: #1a1a1a; line-height: 1; }
        .metric-label { font-size: 0.8125rem; color: #6b7280; margin-top: 4px; font-weight: 500; }
        /* Tables */
        .table-wrapper { overflow-x: auto; border-radius: 8px; border: 1px solid #e9ecef; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #f8f9fa;
            color: #374151;
            font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.5px;
            padding: 12px 14px;
            text-align: left; white-space: nowrap;
            border-bottom: 1px solid #e9ecef;
        }
        tbody tr { border-bottom: 1px solid #f3f4f6; transition: background 0.15s; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tbody tr:hover { background: #f0f7e8; }
        tbody td { padding: 11px 14px; font-size: 0.8125rem; color: #374151; }
        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-yellow { background: #fefce8; color: #854d0e; }
        .badge-orange { background: #ffedd5; color: #9a3412; }
        .badge-admin  { background: #fef3c7; color: #92400e; }
        .badge-viewer { background: #e0f2fe; color: #075985; }
        /* Buttons */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 0.8125rem; font-weight: 600;
            cursor: pointer; border: none;
            transition: all 0.2s; text-decoration: none;
        }
        .btn-primary {
            background: #3b6d11; color: white;
        }
        .btn-primary:hover { background: #639922; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,109,17,0.25); }
        .btn-secondary { background: transparent; color: #3b6d11; border: 1.5px solid #3b6d11; }
        .btn-secondary:hover { background: #f0f7e8; }
        .btn-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .btn-danger:hover { background: #fecaca; }
        .btn-sm { padding: 5px 10px; font-size: 0.75rem; }
        /* Forms */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 0.83rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 9px 12px;
            border: 1.5px solid #e5e7eb; border-radius: 8px;
            font-size: 0.875rem; color: #111827;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: white; box-sizing: border-box;
        }
        .form-control:focus { outline: none; border-color: var(--green-primary); box-shadow: 0 0 0 3px rgba(59,109,17,0.1); }
        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { font-size: 0.78rem; color: #ef4444; margin-top: 4px; }
        /* Alerts */
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 0.875rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        /* Avatar */
        .avatar {
            width: 36px; height: 36px;
            background: var(--green-primary);
            border-radius: 50%; display: flex;
            align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 0.85rem;
        }
        /* User dropdown */
        .user-menu { position: relative; }
        .user-menu-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 10px; border-radius: 10px;
            cursor: pointer; transition: background 0.2s;
            background: none; border: none;
        }
        .user-menu-btn:hover { background: #f3f4f6; }
        /* Pagination */
        .pagination { display: flex; gap: 4px; align-items: center; flex-wrap: wrap; }
        .page-link {
            padding: 6px 12px; border-radius: 8px;
            font-size: 0.82rem; font-weight: 500;
            color: #374151; text-decoration: none;
            border: 1px solid #e5e7eb; transition: all 0.15s;
        }
        .page-link:hover { background: var(--green-50); border-color: var(--green-primary); color: var(--green-primary); }
        .page-link.active { background: var(--green-primary); color: white; border-color: var(--green-primary); }
        /* Misc */
        .section-divider { height: 1px; background: #f0f0f0; margin: 8px 0; }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 10px center; background-size: 16px; padding-right: 36px; }

        /* Modal */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 100; display: flex;
            align-items: center; justify-content: center;
            opacity: 0; pointer-events: none;
            transition: opacity 0.2s;
        }
        .modal-overlay.active { opacity: 1; pointer-events: all; }
        .modal-box {
            background: white; border-radius: 16px;
            padding: 28px; width: 100%;
            max-width: 500px; max-height: 90vh;
            overflow-y: auto; margin: 20px;
            transform: translateY(-10px);
            transition: transform 0.2s;
        }
        .modal-overlay.active .modal-box { transform: translateY(0); }
        .modal-title { font-size: 1.1rem; font-weight: 700; color: #1a2e0a; margin-bottom: 20px; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="background:rgba(255,255,255,0.2);border-radius:10px;padding:8px;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h1>Smart Farming</h1>
                <p>Budidaya Tomat IoT</p>
            </div>
        </div>
    </div>

    <nav style="flex:1;padding:12px 0;overflow-y:auto;">
        <p class="nav-section-label">Menu Utama</p>

        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('sensor.index') }}" class="nav-item {{ request()->routeIs('sensor.*') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Data Sensor
        </a>

        <p class="nav-section-label">Analisis ML</p>

        <a href="{{ route('klasifikasi.index') }}" class="nav-item {{ request()->routeIs('klasifikasi.*') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
            </svg>
            Hasil Klasifikasi
        </a>

        <a href="{{ route('clustering.index') }}" class="nav-item {{ request()->routeIs('clustering.*') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
            </svg>
            Hasil Clustering
        </a>

        <a href="{{ route('prediksi.index') }}" class="nav-item {{ request()->routeIs('prediksi.*') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            Prediksi Irigasi
        </a>

        @if(auth()->user()->isAdmin())
        <p class="nav-section-label">Administrasi</p>
        <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            Manajemen User
        </a>
        @endif
    </nav>

    <!-- User info at bottom -->
    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,0.12);">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div style="flex:1;min-width:0;">
                <p style="color:white;font-size:0.83rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
                <p style="color:rgba(255,255,255,0.55);font-size:0.72rem;">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" style="background:none;border:none;cursor:pointer;padding:4px;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,0.6)" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">
    <!-- Topbar -->
    <header class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="sidebarToggle" style="display:none;background:none;border:none;cursor:pointer;padding:6px;" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <p class="topbar-title">@yield('page-title', 'Dashboard')</p>
                <p class="topbar-subtitle">@yield('page-subtitle', 'Smart Farming Tomat — Sistem Monitoring IoT')</p>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <span class="badge badge-green" style="font-size:0.72rem;">
                <span style="width:6px;height:6px;background:#10b981;border-radius:50%;margin-right:5px;display:inline-block;"></span>
                Sistem Aktif
            </span>
            <div style="text-align:right;">
                <p style="font-size:0.82rem;font-weight:600;color:#1a2e0a;">{{ auth()->user()->name }}</p>
                <p style="font-size:0.72rem;color:#6b7280;">{{ ucfirst(auth()->user()->role) }}</p>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        @if(session('success'))
        <div class="alert alert-success" id="flash-message">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
            <button onclick="document.getElementById('flash-message').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;">✕</button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger" id="flash-error">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
            <button onclick="document.getElementById('flash-error').remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;">✕</button>
        </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
// Auto-hide flash messages
setTimeout(() => {
    document.getElementById('flash-message')?.remove();
    document.getElementById('flash-error')?.remove();
}, 5000);

// Mobile sidebar
if (window.innerWidth <= 768) {
    document.getElementById('sidebarToggle').style.display = 'block';
}
</script>

</body>
</html>
