
@extends('layouts.app')
@section('title', 'Toko')

@push('styles')
    <style>
        /* ===== ANIMASI ===== */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 20px rgba(99,179,237,0.2); }
            50%       { box-shadow: 0 0 40px rgba(99,179,237,0.5); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes gradient-shift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes blob {
            0%, 100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; }
            50%       { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; }
        }
        @keyframes shimmer {
            0%   { left: -100%; }
            100% { left: 200%; }
        }

        /* ===== HERO ===== */
        .hero {
            position: relative;
            border-radius: 20px;
            padding: 56px 52px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 44px;
            gap: 24px;
            overflow: hidden;
            background: linear-gradient(135deg, #0a1628, #0f2544, #1a3a6b, #1d4ed8, #0f2544);
            background-size: 300% 300%;
            animation: gradient-shift 8s ease infinite;
        }

        /* Shimmer line */
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 60%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            animation: shimmer 4s ease-in-out infinite;
            pointer-events: none;
        }

        /* Blob decoration */
        .hero-blob {
            position: absolute;
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            pointer-events: none;
            animation: blob 8s ease-in-out infinite, float 6s ease-in-out infinite;
        }
        .hero-blob-1 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(99,179,237,0.12) 0%, transparent 70%);
            top: -80px; right: -60px;
        }
        .hero-blob-2 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, rgba(29,78,216,0.2) 0%, transparent 70%);
            bottom: -50px; left: 180px;
            animation-delay: -3s;
        }
        .hero-blob-3 {
            width: 120px; height: 120px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            top: 30%; left: 5%;
            animation-delay: -1.5s;
        }

        /* Floating dots */
        .hero-dots { position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none; overflow: hidden; }
        .hero-dots span {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .hero-dots span:nth-child(1) { width:80px;height:80px;top:10%;left:5%;   animation: float 4s ease-in-out infinite; }
        .hero-dots span:nth-child(2) { width:40px;height:40px;top:60%;left:15%;  animation: float 5s ease-in-out infinite .5s; }
        .hero-dots span:nth-child(3) { width:120px;height:120px;top:20%;right:25%;background:rgba(255,255,255,0.04); animation: float 6s ease-in-out infinite 1s; }
        .hero-dots span:nth-child(4) { width:20px;height:20px;bottom:20%;left:40%; animation: float 3.5s ease-in-out infinite 1.5s; }

        /* Hero text animations */
        .hero-text { position: relative; z-index: 1; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: #93c5fd; font-size: 12px; font-weight: 500;
            padding: 5px 12px; border-radius: 20px;
            margin-bottom: 16px;
            backdrop-filter: blur(4px);
            animation: fadeInLeft .6s ease both;
        }
        .hero-badge i { font-size: 14px; color: #fbbf24; }
        .hero-text h1 {
            color: #fff; font-size: 36px; font-weight: 700;
            line-height: 1.2; margin-bottom: 12px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.3);
            animation: fadeInUp .7s ease .1s both;
        }
        .hero-text h1 span { color: #60a5fa; }
        .hero-text p {
            color: #93c5fd; font-size: 14.5px; margin-bottom: 28px;
            line-height: 1.6; max-width: 380px;
            animation: fadeInUp .7s ease .2s both;
        }
        .hero-btns { display: flex; gap: 10px; flex-wrap: wrap; animation: fadeInUp .7s ease .3s both; }

        .hero-cta {
            display: inline-flex; align-items: center; gap: 7px;
            background: #fff; color: #0f2544; font-size: 13.5px; font-weight: 700;
            padding: 11px 24px; border-radius: 10px; text-decoration: none;
            transition: all .2s; box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        }
        .hero-cta:hover { background: #f0f9ff; transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.3); }

        .hero-cta2 {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,0.12); color: #fff; font-size: 13.5px; font-weight: 500;
            padding: 11px 20px; border-radius: 10px; text-decoration: none;
            border: 1px solid rgba(255,255,255,0.25);
            transition: all .2s; backdrop-filter: blur(4px);
        }
        .hero-cta2:hover { background: rgba(255,255,255,0.22); transform: translateY(-2px); }

        /* Hero stats */
        .hero-right { position: relative; z-index: 1; flex-shrink: 0; animation: fadeInUp .7s ease .4s both; }
        .hero-stats { display: flex; gap: 12px; }
        .hero-stat {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 16px;
            padding: 22px 28px;
            text-align: center;
            min-width: 120px;
            backdrop-filter: blur(8px);
            transition: transform .2s, box-shadow .2s;
            cursor: default;
        }
        .hero-stat:nth-child(1) { animation: float 3s ease-in-out infinite, pulse-glow 3s ease-in-out infinite; }
        .hero-stat:nth-child(2) { animation: float 3s ease-in-out infinite .6s, pulse-glow 3s ease-in-out infinite .6s; }
        .hero-stat:hover { transform: translateY(-5px) scale(1.03) !important; }
        .hero-stat .num { color: #fff; font-size: 32px; font-weight: 700; line-height: 1; }
        .hero-stat .lbl { color: #93c5fd; font-size: 12px; margin-top: 6px; }
        .hero-stat .icon { font-size: 22px; color: #60a5fa; margin-bottom: 8px; }
        .hero-stat .icon i { display: inline-block; animation: spin-slow 8s linear infinite; }
        .hero-stat:hover .icon i { animation: spin-slow 1.5s linear infinite; }

        /* ===== FEATURES BAR ===== */
        .features-bar {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 36px;
        }
        .feature-item {
            background: #fff;
            border: 1px solid #e8edf3;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: border-color .2s, box-shadow .2s, transform .2s;
            animation: fadeInUp .6s ease both;
        }
        .feature-item:nth-child(1) { animation-delay: .1s; }
        .feature-item:nth-child(2) { animation-delay: .2s; }
        .feature-item:nth-child(3) { animation-delay: .3s; }
        .feature-item:hover { border-color: #0f2544; box-shadow: 0 4px 16px rgba(15,37,68,0.1); transform: translateY(-2px); }
        .feature-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; transition: transform .2s; }
        .feature-item:hover .feature-icon { transform: scale(1.15) rotate(-5deg); }
        .fi-blue  { background: #dbeafe; color: #1d4ed8; }
        .fi-green { background: #dcfce7; color: #16a34a; }
        .fi-orange{ background: #ffedd5; color: #ea580c; }
        .feature-text h4 { font-size: 13px; font-weight: 600; color: #0f172a; margin-bottom: 2px; }
        .feature-text p  { font-size: 12px; color: #64748b; }

        /* ===== TOOLBAR ===== */
        .toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; gap: 16px; }
        .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }
        .pill {
            font-size: 12.5px; padding: 6px 16px; border-radius: 20px;
            border: 1px solid #e2e8f0; background: #fff; color: #64748b;
            cursor: pointer; transition: all .15s; user-select: none;
        }
        .pill:hover { border-color: #0f2544; color: #0f2544; }
        .pill.active { background: #0f2544; color: #fff; border-color: #0f2544; }
        .search-wrap {
            display: flex; align-items: center; gap: 8px;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 8px 14px; min-width: 240px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: box-shadow .15s, border-color .15s;
        }
        .search-wrap:focus-within { border-color: #0f2544; box-shadow: 0 0 0 3px rgba(15,37,68,0.08); }
        .search-wrap i { color: #94a3b8; font-size: 16px; }
        .search-wrap input { border: none; outline: none; font-size: 13.5px; background: transparent; color: #1e293b; width: 100%; }

        /* ===== SECTION HEAD ===== */
        .sec-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .sec-head h2 { font-size: 17px; font-weight: 700; color: #0f172a; }
        .sec-head span { font-size: 13px; color: #94a3b8; margin-left: 8px; }

        /* ===== PRODUCT GRID ===== */
        .prod-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px; }

        .prod-card {
            background: #fff;
            border: 1px solid #e8edf3;
            border-radius: 14px;
            overflow: hidden;
            transition: box-shadow .25s, border-color .25s, transform .25s;
            animation: fadeInUp .5s ease both;
        }
        .prod-card:nth-child(1) { animation-delay: .05s; }
        .prod-card:nth-child(2) { animation-delay: .1s; }
        .prod-card:nth-child(3) { animation-delay: .15s; }
        .prod-card:nth-child(4) { animation-delay: .2s; }
        .prod-card:nth-child(5) { animation-delay: .25s; }
        .prod-card:nth-child(6) { animation-delay: .3s; }
        .prod-card:hover { box-shadow: 0 10px 28px rgba(15,37,68,0.14); border-color: #0f2544; transform: translateY(-4px); }

        .prod-img {
            height: 160px;
            background: linear-gradient(135deg, #f0f4f8, #e8edf5);
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .prod-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s ease; }
        .prod-card:hover .prod-img img { transform: scale(1.08); }
        .prod-img .no-img { font-size: 48px; color: #94b4cc; transition: transform .3s; }
        .prod-card:hover .no-img { transform: scale(1.1) rotate(-5deg); }

        /* Overlay */
        .prod-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(15,37,68,0.7) 0%, transparent 60%);
            opacity: 0; transition: opacity .25s;
            display: flex; align-items: flex-end; padding: 12px;
        }
        .prod-card:hover .prod-overlay { opacity: 1; }
        .prod-overlay-text { color: #fff; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 5px; }

        .prod-tag {
            position: absolute; top: 10px; left: 10px;
            color: #fff; font-size: 10px; font-weight: 600;
            padding: 3px 9px; border-radius: 6px;
            letter-spacing: .04em; z-index: 1;
            background: #16a34a;
        }

        .prod-body { padding: 14px; }
        .prod-name { font-size: 13.5px; font-weight: 600; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 4px; }
        .prod-meta { font-size: 11.5px; color: #94a3b8; margin-bottom: 8px; display: flex; align-items: center; gap: 4px; }
        .prod-meta i { font-size: 13px; }
        .prod-price { font-size: 18px; font-weight: 700; color: #0f2544; margin-bottom: 12px; }
        .prod-foot { display: flex; align-items: center; justify-content: space-between; }
        .prod-stock { font-size: 11.5px; font-weight: 500; display: flex; align-items: center; gap: 4px; }
        .prod-stock.ok { color: #16a34a; }
        .prod-stock.out { color: #dc2626; }

        .btn-add {
            background: #0f2544; color: #fff;
            font-size: 12px; font-weight: 500;
            padding: 7px 13px; border-radius: 8px;
            border: none; cursor: pointer;
            display: flex; align-items: center; gap: 5px;
            transition: all .2s;
        }
        .btn-add:hover { background: #1d4ed8; transform: scale(1.05); box-shadow: 0 3px 10px rgba(29,78,216,0.3); }
        .btn-add:disabled { background: #cbd5e1; cursor: not-allowed; transform: none; box-shadow: none; }

        /* ===== EMPTY ===== */
        .empty { grid-column: 1 / -1; text-align: center; padding: 64px 0; color: #94a3b8; }
        .empty i { font-size: 52px; margin-bottom: 12px; display: block; color: #cbd5e1; }
        .empty p { font-size: 15px; }

        /* ===== PAGINATION ===== */
        .paging { margin-top: 36px; }
        .paging .pagination { display: flex; gap: 6px; justify-content: center; }
        .paging .page-item .page-link {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 8px;
            font-size: 13px; text-decoration: none;
            border: 1px solid #e2e8f0; color: #475569;
            transition: all .15s;
        }
        .paging .page-item.active .page-link { background: #0f2544; color: #fff; border-color: #0f2544; }
        .paging .page-item .page-link:hover:not(.active) { border-color: #0f2544; color: #0f2544; }
    </style>
@endpush

@section('content')

    {{-- Hero --}}
    <div class="hero">
        <div class="hero-dots">
            <span></span><span></span><span></span><span></span>
        </div>
        <div class="hero-blob hero-blob-1"></div>
        <div class="hero-blob hero-blob-2"></div>
        <div class="hero-blob hero-blob-3"></div>

        <div class="hero-text">
            <div class="hero-badge">
                <i class="ti ti-bolt"></i>
                Ongkir Real-Time · JNE · POS · TIKI
            </div>
            <h1>Belanja mudah,<br><span>ongkir</span> langsung tahu</h1>
            <p>Cek ongkos kirim ke seluruh Indonesia secara real-time langsung saat checkout. Transparan, cepat, dan terpercaya.</p>
            <div class="hero-btns">
                <a href="#produk" class="hero-cta">
                    <i class="ti ti-shopping-bag"></i> Belanja Sekarang
                </a>
                @guest
                    <a href="{{ route('register') }}" class="hero-cta2">
                        <i class="ti ti-user-plus"></i> Daftar Gratis
                    </a>
                @endguest
            </div>
        </div>

        <div class="hero-right">
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="icon"><i class="ti ti-package"></i></div>
                    <div class="num">{{ \App\Models\Product::where('stock', '>', 0)->count() }}</div>
                    <div class="lbl">Produk tersedia</div>
                </div>
                <div class="hero-stat">
                    <div class="icon"><i class="ti ti-truck"></i></div>
                    <div class="num">3</div>
                    <div class="lbl">Ekspedisi</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Features bar --}}
    <div class="features-bar">
        <div class="feature-item">
            <div class="feature-icon fi-blue"><i class="ti ti-map-pin"></i></div>
            <div class="feature-text">
                <h4>Kirim ke Seluruh Indonesia</h4>
                <p>34 provinsi, ratusan kota tersedia</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon fi-green"><i class="ti ti-shield-check"></i></div>
            <div class="feature-text">
                <h4>Transaksi Aman</h4>
                <p>Data terenkripsi & terlindungi</p>
            </div>
        </div>
        <div class="feature-item">
            <div class="feature-icon fi-orange"><i class="ti ti-file-invoice"></i></div>
            <div class="feature-text">
                <h4>Invoice PDF Otomatis</h4>
                <p>Cetak bukti pesanan kapan saja</p>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div id="produk">
        <div class="toolbar">
            <div class="filter-pills">
                <span class="pill active">Semua</span>
                <span class="pill">Tersedia</span>
                <span class="pill">Harga terendah</span>
                <span class="pill">Terbaru</span>
            </div>
            <div class="search-wrap">
                <i class="ti ti-search"></i>
                <input type="text" id="search-input" placeholder="Cari produk...">
            </div>
        </div>

        <div class="sec-head">
            <div>
                <h2>Semua produk <span>{{ $products->total() }} produk ditemukan</span></h2>
            </div>
        </div>

        {{-- Grid produk --}}
        <div class="prod-grid" id="prod-grid">
            @forelse($products as $product)
                <div class="prod-card">
                    <div class="prod-img">
                        @if($product->photo)
                            <img src="{{ asset('storage/'.$product->photo) }}" alt="{{ $product->name }}">
                        @else
                            <i class="ti ti-package no-img"></i>
                        @endif
                        @if($product->created_at->diffInDays() < 7)
                            <span class="prod-tag">✨ Baru</span>
                        @endif
                        <div class="prod-overlay">
                            <span class="prod-overlay-text"><i class="ti ti-eye"></i> Lihat detail</span>
                        </div>
                    </div>
                    <div class="prod-body">
                        <div class="prod-name">{{ $product->name }}</div>
                        <div class="prod-meta">
                            <i class="ti ti-weight"></i> {{ $product->weight }}g
                        </div>
                        <div class="prod-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="prod-foot">
                            @if($product->stock > 0)
                                <span class="prod-stock ok">
                            <i class="ti ti-circle-check"></i> Stok {{ $product->stock }}
                        </span>
                                @auth
                                    <form method="POST" action="{{ route('cart.add', $product) }}">
                                        @csrf
                                        <button type="submit" class="btn-add">
                                            <i class="ti ti-shopping-cart"></i> Beli
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn-add">
                                        <i class="ti ti-login"></i> Masuk
                                    </a>
                                @endauth
                            @else
                                <span class="prod-stock out">
                            <i class="ti ti-circle-x"></i> Habis
                        </span>
                                <button class="btn-add" disabled>Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty">
                    <i class="ti ti-package-off"></i>
                    <p>Belum ada produk tersedia.</p>
                </div>
            @endforelse
        </div>

        <div class="paging">{{ $products->links() }}</div>
    </div>

@endsection

@push('scripts')
    <script>
        // Filter pills
        document.querySelectorAll('.pill').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Search filter real-time
        document.getElementById('search-input').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.prod-card').forEach(card => {
                const name = card.querySelector('.prod-name').textContent.toLowerCase();
                card.style.display = name.includes(q) ? 'block' : 'none';
            });
        });
    </script>
@endpush
