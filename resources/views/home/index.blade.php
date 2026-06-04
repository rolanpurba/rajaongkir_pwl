@extends('layouts.app')
@section('title', 'Toko')

@push('styles')
    <style>
        /* Hero */
        .hero {
            background: linear-gradient(135deg, #0f2544 0%, #1e3a5f 55%, #1d4ed8 100%);
            border-radius: 16px;
            padding: 48px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 24px;
        }
        .hero-text h1 { color: #fff; font-size: 30px; font-weight: 600; line-height: 1.3; margin-bottom: 10px; }
        .hero-text p  { color: #93c5fd; font-size: 14.5px; margin-bottom: 24px; }
        .hero-cta {
            display: inline-flex; align-items: center; gap: 7px;
            background: #fff; color: #0f2544; font-size: 13.5px; font-weight: 600;
            padding: 10px 22px; border-radius: 8px; text-decoration: none;
            transition: background .15s;
        }
        .hero-cta:hover { background: #f1f5f9; }
        .hero-stats { display: flex; gap: 12px; flex-shrink: 0; }
        .hero-stat {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 20px 24px;
            text-align: center;
            min-width: 120px;
        }
        .hero-stat .num { color: #fff; font-size: 28px; font-weight: 600; }
        .hero-stat .lbl { color: #93c5fd; font-size: 12px; margin-top: 3px; }

        /* Filter & search bar */
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            gap: 16px;
        }
        .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }
        .pill {
            font-size: 12.5px; padding: 5px 15px; border-radius: 20px;
            border: 1px solid #e2e8f0; background: #fff; color: #64748b;
            cursor: pointer; transition: all .15s; user-select: none;
        }
        .pill:hover { border-color: #0f2544; color: #0f2544; }
        .pill.active { background: #0f2544; color: #fff; border-color: #0f2544; }
        .search-wrap {
            display: flex; align-items: center; gap: 8px;
            background: #fff; border: 1px solid #e2e8f0; border-radius: 8px;
            padding: 7px 12px; min-width: 220px;
        }
        .search-wrap i { color: #94a3b8; font-size: 16px; }
        .search-wrap input {
            border: none; outline: none; font-size: 13.5px;
            background: transparent; color: #1e293b; width: 100%;
        }

        /* Section heading */
        .sec-head { display: flex; align-items: baseline; gap: 8px; margin-bottom: 20px; }
        .sec-head h2 { font-size: 16px; font-weight: 600; color: #0f172a; }
        .sec-head span { font-size: 13px; color: #94a3b8; }

        /* Product grid */
        .prod-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 16px;
        }
        .prod-card {
            background: #fff;
            border: 1px solid #e8edf3;
            border-radius: 12px;
            overflow: hidden;
            transition: box-shadow .15s, border-color .15s;
        }
        .prod-card:hover { box-shadow: 0 4px 16px rgba(15,37,68,0.10); border-color: #c7d2e0; }
        .prod-img {
            height: 145px;
            background: #eef2f7;
            display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden;
        }
        .prod-img img { width: 100%; height: 100%; object-fit: cover; }
        .prod-img .no-img { font-size: 44px; color: #94b4cc; }
        .prod-tag {
            position: absolute; top: 8px; left: 8px;
            background: #0f2544; color: #fff;
            font-size: 10px; font-weight: 600; padding: 3px 8px; border-radius: 5px;
            letter-spacing: .03em;
        }
        .prod-body { padding: 14px; }
        .prod-name {
            font-size: 13.5px; font-weight: 600; color: #1e293b;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            margin-bottom: 3px;
        }
        .prod-meta {
            font-size: 11.5px; color: #94a3b8; margin-bottom: 8px;
            display: flex; align-items: center; gap: 4px;
        }
        .prod-meta i { font-size: 13px; }
        .prod-price { font-size: 17px; font-weight: 700; color: #0f2544; margin-bottom: 12px; }
        .prod-foot { display: flex; align-items: center; justify-content: space-between; }
        .prod-stock {
            font-size: 11.5px; font-weight: 500;
            display: flex; align-items: center; gap: 4px;
        }
        .prod-stock.ok { color: #16a34a; }
        .prod-stock.out { color: #dc2626; }
        .btn-add {
            background: #0f2544; color: #fff;
            font-size: 12px; font-weight: 500;
            padding: 6px 12px; border-radius: 7px;
            border: none; cursor: pointer;
            display: flex; align-items: center; gap: 5px;
            transition: background .15s;
        }
        .btn-add:hover { background: #1e3a5f; }
        .btn-add:disabled { background: #cbd5e1; cursor: not-allowed; }

        /* Empty state */
        .empty {
            grid-column: 1 / -1;
            text-align: center; padding: 64px 0; color: #94a3b8;
        }
        .empty i { font-size: 52px; margin-bottom: 12px; display: block; color: #cbd5e1; }
        .empty p { font-size: 15px; }

        /* Pagination */
        .paging { margin-top: 32px; }
        .paging .pagination { display: flex; gap: 6px; justify-content: center; }
        .paging .page-item .page-link {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 7px;
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
        <div class="hero-text">
            <h1>Belanja mudah,<br>ongkir langsung tahu</h1>
            <p>Cek ongkos kirim JNE, POS, dan TIKI secara real-time saat checkout</p>
            <a href="#produk" class="hero-cta">
                <i class="ti ti-arrow-down"></i>
                Lihat produk
            </a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="num">{{ \App\Models\Product::where('stock', '>', 0)->count() }}</div>
                <div class="lbl">Produk tersedia</div>
            </div>
            <div class="hero-stat">
                <div class="num">3</div>
                <div class="lbl">Ekspedisi</div>
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
                <input type="text" placeholder="Cari produk...">
            </div>
        </div>

        <div class="sec-head">
            <h2>Semua produk</h2>
            <span>{{ $products->total() }} produk ditemukan</span>
        </div>

        {{-- Grid produk --}}
        <div class="prod-grid">
            @forelse($products as $product)
                <div class="prod-card">
                    <div class="prod-img">
                        @if($product->photo)
                            <img src="{{ asset('storage/'.$product->photo) }}" alt="{{ $product->name }}">
                        @else
                            <i class="ti ti-package no-img"></i>
                        @endif
                        @if($product->created_at->diffInDays() < 7)
                            <span class="prod-tag">Baru</span>
                        @endif
                    </div>
                    <div class="prod-body">
                        <div class="prod-name">{{ $product->name }}</div>
                        <div class="prod-meta">
                            <i class="ti ti-weight"></i>
                            {{ $product->weight }}g
                        </div>
                        <div class="prod-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        <div class="prod-foot">
                            @if($product->stock > 0)
                                <span class="prod-stock ok">
              <i class="ti ti-circle-check"></i>
              Stok {{ $product->stock }}
            </span>
                                @auth
                                    <form method="POST" action="{{ route('cart.add', $product) }}">
                                        @csrf
                                        <button type="submit" class="btn-add">
                                            <i class="ti ti-plus"></i> Beli
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn-add">
                                        <i class="ti ti-login"></i> Masuk
                                    </a>
                                @endauth
                            @else
                                <span class="prod-stock out">
              <i class="ti ti-circle-x"></i>
              Habis
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
        // Filter pills (UI only, tanpa reload)
        document.querySelectorAll('.pill').forEach(pill => {
            pill.addEventListener('click', function() {
                document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
@endpush
