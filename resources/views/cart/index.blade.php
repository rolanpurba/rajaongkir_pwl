@extends('layouts.app')
@section('title', 'Keranjang')

@push('styles')
    <style>
        .page-head{display:flex;align-items:center;gap:10px;margin-bottom:28px}
        .page-head h1{font-size:22px;font-weight:600;color:#0f172a}
        .page-head span{font-size:13.5px;color:#94a3b8;background:#f1f5f9;padding:3px 10px;border-radius:20px}
        .cart-layout{display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start}
        .cart-items{display:flex;flex-direction:column;gap:12px}
        .cart-item{background:#fff;border:1px solid #e8edf3;border-radius:12px;padding:16px;display:flex;align-items:center;gap:16px;transition:border-color .15s}
        .cart-item:hover{border-color:#c7d2e0}
        .item-img{width:72px;height:72px;border-radius:9px;object-fit:cover;border:1px solid #e8edf3;flex-shrink:0}
        .item-img-empty{width:72px;height:72px;border-radius:9px;background:#eef2f7;display:flex;align-items:center;justify-content:center;font-size:28px;color:#94b4cc;flex-shrink:0}
        .item-info{flex:1;min-width:0}
        .item-name{font-size:14.5px;font-weight:500;color:#1e293b;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .item-meta{font-size:12px;color:#94a3b8;display:flex;align-items:center;gap:12px;margin-bottom:6px}
        .item-meta i{font-size:13px}
        .item-price{font-size:16px;font-weight:600;color:#0f2544}
        .item-sub{font-size:12px;color:#94a3b8;margin-top:1px}
        .btn-remove{background:none;border:1px solid #fecaca;color:#dc2626;font-size:12.5px;padding:6px 12px;border-radius:7px;cursor:pointer;display:flex;align-items:center;gap:5px;transition:all .15s;flex-shrink:0}
        .btn-remove:hover{background:#fef2f2}

        .summary-card{background:#fff;border:1px solid #e8edf3;border-radius:12px;padding:20px;position:sticky;top:80px}
        .summary-card h3{font-size:15px;font-weight:600;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9}
        .summary-row{display:flex;justify-content:space-between;align-items:center;padding:6px 0;font-size:13.5px}
        .summary-row .lbl{color:#64748b}
        .summary-row .val{font-weight:500;color:#1e293b}
        .summary-total{display:flex;justify-content:space-between;align-items:center;padding:12px 0 16px;margin-top:4px;border-top:1px solid #e8edf3}
        .summary-total .lbl{font-size:14px;font-weight:600;color:#0f172a}
        .summary-total .val{font-size:18px;font-weight:700;color:#0f2544}
        .summary-note{font-size:12px;color:#94a3b8;text-align:center;margin-bottom:14px}
        .btn-checkout{display:block;width:100%;background:#0f2544;color:#fff;text-align:center;font-size:14px;font-weight:500;padding:12px;border-radius:9px;text-decoration:none;transition:background .15s}
        .btn-checkout:hover{background:#1e3a5f}
        .btn-back{display:block;width:100%;text-align:center;font-size:13.5px;color:#64748b;margin-top:10px;text-decoration:none;padding:8px}
        .btn-back:hover{color:#0f2544}

        .empty-state{text-align:center;padding:80px 0;color:#94a3b8}
        .empty-state i{font-size:64px;display:block;margin-bottom:16px;color:#cbd5e1}
        .empty-state h2{font-size:18px;font-weight:500;color:#475569;margin-bottom:6px}
        .empty-state p{font-size:14px;margin-bottom:24px}
        .btn-shop{display:inline-flex;align-items:center;gap:7px;background:#0f2544;color:#fff;padding:11px 24px;border-radius:9px;text-decoration:none;font-size:14px;font-weight:500}
        .btn-shop:hover{background:#1e3a5f}
    </style>
@endpush

@section('content')
    @if($items->isEmpty())
        <div class="empty-state">
            <i class="ti ti-shopping-cart-off"></i>
            <h2>Keranjang masih kosong</h2>
            <p>Tambahkan produk dari halaman toko untuk mulai belanja.</p>
            <a href="{{ route('home') }}" class="btn-shop">
                <i class="ti ti-building-store"></i> Mulai belanja
            </a>
        </div>
    @else
        <div class="page-head">
            <h1>Keranjang Belanja</h1>
            <span>{{ $items->count() }} produk</span>
        </div>

        <div class="cart-layout">
            <div class="cart-items">
                @foreach($items as $item)
                    <div class="cart-item">
                        @if($item->product->photo)
                            <img src="{{ asset('storage/'.$item->product->photo) }}" class="item-img" alt="{{ $item->product->name }}">
                        @else
                            <div class="item-img-empty"><i class="ti ti-package"></i></div>
                        @endif
                        <div class="item-info">
                            <div class="item-name">{{ $item->product->name }}</div>
                            <div class="item-meta">
                                <span><i class="ti ti-weight"></i> {{ $item->product->weight * $item->quantity }}g</span>
                                <span>Qty: {{ $item->quantity }}</span>
                            </div>
                            <div class="item-price">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</div>
                            <div class="item-sub">Rp {{ number_format($item->product->price, 0, ',', '.') }} / item</div>
                        </div>
                        <form method="POST" action="{{ route('cart.remove', $item) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove">
                                <i class="ti ti-trash" style="font-size:14px"></i> Hapus
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="summary-card">
                <h3>Ringkasan Belanja</h3>
                @foreach($items as $item)
                    <div class="summary-row">
                        <span class="lbl" style="max-width:170px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->product->name }} ×{{ $item->quantity }}</span>
                        <span class="val">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <div class="summary-row" style="margin-top:6px">
                    <span class="lbl">Total berat</span>
                    <span class="val">{{ $totalWeight }}g</span>
                </div>
                <div class="summary-total">
                    <span class="lbl">Subtotal</span>
                    <span class="val">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="summary-note">Ongkos kirim dihitung di halaman checkout</div>
                <a href="{{ route('checkout.index') }}" class="btn-checkout">
                    Lanjut ke Checkout →
                </a>
                <a href="{{ route('home') }}" class="btn-back">← Lanjut belanja</a>
            </div>
        </div>
    @endif
@endsection
