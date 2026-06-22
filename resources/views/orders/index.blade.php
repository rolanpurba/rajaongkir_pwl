@extends('layouts.app')
@section('title', 'Pesanan Saya')

@push('styles')
    <style>
        .page-head{display:flex;align-items:center;gap:10px;margin-bottom:28px}
        .page-head h1{font-size:22px;font-weight:600;color:#0f172a}
        .order-card{background:#fff;border:1px solid #e8edf3;border-radius:12px;margin-bottom:14px;overflow:hidden;transition:border-color .15s}
        .order-card:hover{border-color:#c7d2e0}
        .order-head{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid #f1f5f9;background:#fafbfc}
        .order-id{font-size:13px;font-weight:600;color:#0f172a}
        .order-date{font-size:12px;color:#94a3b8;margin-top:1px}
        .badge{font-size:11.5px;font-weight:500;padding:4px 11px;border-radius:5px;display:inline-flex;align-items:center;gap:5px}
        .badge i{font-size:12px}
        .badge-yellow{background:#fef9c3;color:#a16207}
        .badge-blue{background:#dbeafe;color:#1d4ed8}
        .badge-green{background:#dcfce7;color:#15803d}
        .order-body{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;gap:16px}
        .order-info{flex:1}
        .order-info p{font-size:13.5px;color:#475569;margin-bottom:3px;display:flex;align-items:center;gap:6px}
        .order-info p i{font-size:14px;color:#94a3b8}
        .order-price{font-size:18px;font-weight:700;color:#0f2544;white-space:nowrap}
        .order-actions{display:flex;gap:8px;align-items:center;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end}
        .btn-detail{font-size:12.5px;color:#0f2544;border:1px solid #c7d2e0;padding:6px 13px;border-radius:6px;text-decoration:none;transition:all .15s}
        .btn-detail:hover{background:#f1f5f9}
        .btn-invoice{font-size:12.5px;color:#16a34a;border:1px solid #bbf7d0;padding:6px 13px;border-radius:6px;text-decoration:none;display:inline-flex;align-items:center;gap:5px;transition:all .15s}
        .btn-invoice:hover{background:#f0fdf4}
        .btn-hapus{font-size:12.5px;color:#dc2626;border:1px solid #fecaca;padding:6px 13px;border-radius:6px;background:none;cursor:pointer;display:inline-flex;align-items:center;gap:5px;transition:all .15s}
        .btn-hapus:hover{background:#fef2f2}
        .empty-state{text-align:center;padding:72px 0;color:#94a3b8}
        .empty-state i{font-size:60px;display:block;margin-bottom:14px;color:#cbd5e1}
        .empty-state h2{font-size:17px;font-weight:500;color:#475569;margin-bottom:6px}
        .empty-state p{font-size:14px;margin-bottom:22px}
        .btn-shop{display:inline-flex;align-items:center;gap:7px;background:#0f2544;color:#fff;padding:10px 22px;border-radius:9px;text-decoration:none;font-size:13.5px;font-weight:500}
    </style>
@endpush

@section('content')
    <div class="page-head">
        <h1>Pesanan Saya</h1>
        @if($orders->count())
            <span style="font-size:13.5px;color:#94a3b8;background:#f1f5f9;padding:3px 10px;border-radius:20px">
            {{ $orders->count() }} pesanan
        </span>
        @endif
    </div>

    @forelse($orders as $order)
        <div class="order-card">
            <div class="order-head">
                <div>
                    <div class="order-id">Order #{{ $order->id }}</div>
                    <div class="order-date">
                        <i class="ti ti-calendar" style="font-size:12px;vertical-align:-1px"></i>
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                @if($order->status === 'selesai')
                    <span class="badge badge-green"><i class="ti ti-circle-check"></i> Selesai</span>
                @elseif($order->status === 'dikirim')
                    <span class="badge badge-blue"><i class="ti ti-truck"></i> Dikirim</span>
                @else
                    <span class="badge badge-yellow"><i class="ti ti-clock"></i> Belum bayar</span>
                @endif
            </div>

            <div class="order-body">
                <div class="order-info">
                    <p><i class="ti ti-package"></i> {{ $order->items->count() }} produk</p>
                    <p><i class="ti ti-truck"></i> {{ strtoupper($order->courier) }} {{ $order->courier_service }}</p>
                    <p><i class="ti ti-map-pin"></i> {{ $order->city }}, {{ $order->province }}</p>
                </div>

                <div style="text-align:right">
                    <div class="order-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    <div style="font-size:12px;color:#94a3b8;margin-top:2px">termasuk ongkir</div>
                </div>

                <div class="order-actions">
                    <a href="{{ route('orders.show', $order) }}" class="btn-detail">Detail</a>
                    <a href="{{ route('orders.invoice', $order) }}" class="btn-invoice">
                        <i class="ti ti-file-type-pdf" style="font-size:14px"></i> Invoice
                    </a>
                    {{-- Tombol hapus hanya muncul jika status masih belum bayar --}}
                    @if($order->status === 'belum_bayar')
                        <form method="POST" action="{{ route('orders.destroy', $order) }}"
                              onsubmit="return confirm('Yakin ingin menghapus Order #{{ $order->id }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-hapus">
                                <i class="ti ti-trash" style="font-size:14px"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="ti ti-receipt-off"></i>
            <h2>Belum ada pesanan</h2>
            <p>Mulai belanja dan lakukan checkout untuk membuat pesanan.</p>
            <a href="{{ route('home') }}" class="btn-shop">
                <i class="ti ti-building-store"></i> Mulai belanja
            </a>
        </div>
    @endforelse
@endsection
