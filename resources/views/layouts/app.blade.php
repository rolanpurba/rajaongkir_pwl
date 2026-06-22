<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CikopakOngkir - @yield('title', 'Toko Online')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background: #0f2544;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand {
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-brand i { color: #7dd3fc; font-size: 22px; }
        .navbar-links { display: flex; align-items: center; gap: 4px; }
        .nav-link {
            color: #94a3b8;
            font-size: 13.5px;
            padding: 7px 13px;
            border-radius: 7px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background .15s, color .15s;
        }
        .nav-link:hover { background: rgba(255,255,255,0.08); color: #fff; }
        .nav-link.active { background: rgba(255,255,255,0.12); color: #fff; }
        .nav-cart {
            background: #1d4ed8;
            color: #fff;
            font-size: 13.5px;
            padding: 7px 16px;
            border-radius: 7px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background .15s;
            margin-left: 6px;
        }
        .nav-cart:hover { background: #1e40af; }
        .nav-badge {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 1px 7px;
            font-size: 11px;
        }

        /* Flash message */
        .flash {
            max-width: 1200px;
            margin: 16px auto 0;
            padding: 0 40px;
        }
        .flash-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .flash-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 13.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Main content — flex:1 agar otomatis isi ruang kosong */
        .main-content {
            flex: 1;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 32px 40px 32px;
        }

        /* Footer — selalu di bawah otomatis */
        .site-footer {
            background: #0f2544;
            padding: 24px 40px;
            margin-top: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .site-footer .brand { color: #7dd3fc; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 7px; }
        .site-footer p { color: #475569; font-size: 12px; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">
        <i class="ti ti-package"></i>
        CikopakOngkir
    </a>
    <div class="navbar-links">
        @auth
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="ti ti-home"></i> Toko
            </a>
            <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <i class="ti ti-receipt"></i> Pesanan
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.products.index') }}" class="nav-link">
                    <i class="ti ti-settings"></i> Admin
                </a>
            @endif
            <a href="{{ route('cart.index') }}" class="nav-cart">
                <i class="ti ti-shopping-cart"></i> Keranjang
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin-left:6px">
                @csrf
                <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:13.5px;padding:7px 13px;border-radius:7px;display:flex;align-items:center;gap:6px">
                    <i class="ti ti-logout"></i> Keluar
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link">
                <i class="ti ti-login"></i> Masuk
            </a>
            <a href="{{ route('register') }}" class="nav-cart">
                <i class="ti ti-user-plus"></i> Daftar
            </a>
        @endauth
    </div>
</nav>

@if(session('success'))
    <div class="flash">
        <div class="flash-success">
            <i class="ti ti-circle-check"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="flash">
        <div class="flash-error">
            <i class="ti ti-circle-x"></i>
            {{ session('error') }}
        </div>
    </div>
@endif

<div class="main-content">
    @yield('content')
</div>

<footer class="site-footer">
    <div class="brand"><i class="ti ti-package"></i> CikopakOngkir</div>
    <p>Toko Online UMKM · Ongkir Real-Time</p>
</footer>

@stack('scripts')
</body>
</html>
