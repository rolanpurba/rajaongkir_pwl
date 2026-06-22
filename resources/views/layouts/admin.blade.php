<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin · @yield('title', 'Dashboard') — CikopakOngkir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',system-ui,sans-serif;background:#f1f5f9;min-height:100vh;display:flex}
        .sidebar{width:220px;background:#0f2544;min-height:100vh;display:flex;flex-direction:column;flex-shrink:0;position:fixed;left:0;top:0;bottom:0;z-index:50}
        .sidebar-brand{padding:20px 20px 16px;border-bottom:1px solid rgba(255,255,255,0.08)}
        .sidebar-brand a{color:#fff;font-size:16px;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:8px}
        .sidebar-brand a i{color:#7dd3fc;font-size:20px}
        .sidebar-brand p{color:#475569;font-size:11px;margin-top:3px;padding-left:28px}
        .sidebar-nav{flex:1;padding:12px 10px}
        .nav-label{color:#475569;font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;padding:12px 10px 6px}
        .sidebar-link{display:flex;align-items:center;gap:10px;padding:8px 12px;border-radius:8px;color:#94a3b8;font-size:13.5px;text-decoration:none;transition:all .15s;margin-bottom:2px}
        .sidebar-link i{font-size:18px}
        .sidebar-link:hover{background:rgba(255,255,255,0.07);color:#fff}
        .sidebar-link.active{background:rgba(255,255,255,0.12);color:#fff}
        .sidebar-link.active i{color:#7dd3fc}
        .sidebar-footer{padding:14px 10px;border-top:1px solid rgba(255,255,255,0.08)}
        .sidebar-user{display:flex;align-items:center;gap:10px;padding:8px 10px;margin-bottom:6px}
        .user-avatar{width:32px;height:32px;border-radius:50%;background:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#fff;flex-shrink:0}
        .user-name{font-size:13px;font-weight:500;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-role{font-size:11px;color:#475569}
        .btn-logout{display:flex;align-items:center;gap:8px;width:100%;padding:7px 12px;border-radius:8px;border:none;background:none;color:#64748b;font-size:13px;cursor:pointer;transition:all .15s}
        .btn-logout:hover{background:rgba(220,38,38,0.12);color:#f87171}
        .main-wrap{margin-left:220px;flex:1;display:flex;flex-direction:column;min-height:100vh}
        .topbar{background:#fff;border-bottom:1px solid #e8edf3;padding:0 32px;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40}
        .topbar-title{font-size:15px;font-weight:600;color:#0f172a}
        .topbar-link{font-size:13px;color:#64748b;text-decoration:none;display:flex;align-items:center;gap:5px;padding:5px 10px;border-radius:6px;transition:all .15s}
        .topbar-link:hover{background:#f1f5f9;color:#0f2544}
        .page-content{padding:28px 32px;flex:1}
        .flash-bar{margin-bottom:20px}
        .flash-success{background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:8px;padding:10px 16px;font-size:13.5px;display:flex;align-items:center;gap:8px}
        .flash-error{background:#fef2f2;border:1px solid #fecaca;color:#dc2626;border-radius:8px;padding:10px 16px;font-size:13.5px;display:flex;align-items:center;gap:8px}
    </style>
    @stack('styles')
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('home') }}"><i class="ti ti-package"></i>CikopakOngkir</a>
        <p>Panel Admin</p>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">Kelola</div>
        <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="ti ti-package"></i> Produk
        </a>
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
            <i class="ti ti-receipt"></i> Pesanan
        </a>
        <div class="nav-label">Lainnya</div>
        <a href="{{ route('home') }}" class="sidebar-link"><i class="ti ti-building-store"></i> Lihat Toko</a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout"><i class="ti ti-logout" style="font-size:16px"></i> Keluar</button>
        </form>
    </div>
</aside>
<div class="main-wrap">
    <header class="topbar">
        <span class="topbar-title">@yield('title', 'Dashboard')</span>
        <a href="{{ route('home') }}" class="topbar-link"><i class="ti ti-external-link" style="font-size:15px"></i> Buka toko</a>
    </header>
    <div class="page-content">
        @if(session('success'))
            <div class="flash-bar"><div class="flash-success"><i class="ti ti-circle-check"></i>{{ session('success') }}</div></div>
        @endif
        @if(session('error'))
            <div class="flash-bar"><div class="flash-error"><i class="ti ti-circle-x"></i>{{ session('error') }}</div></div>
        @endif
        @yield('content')
    </div>
</div>
@stack('scripts')
</body>
</html>
