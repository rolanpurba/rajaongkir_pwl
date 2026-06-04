@extends('layouts.app')
@section('title', 'Detail Pesanan #'.$order->id)

@push('styles')
    <style>
        .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13.5px;color:#64748b;text-decoration:none;margin-bottom:20px}
        .back-link:hover{color:#0f2544}
        .detail-layout{display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start}
        .det-card{background:#fff;border:1px solid #e8edf3;border-radius:12px;padding:20px;margin-bottom:16px}
        .det-card h3{font-size:14.5px;font-weight:600;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:7px}
        .det-card h3 i{font-size:17px;color:#0f2544}
        .info-row{display:flex;padding:7px 0;border-bottom:1px solid #f8fafc;font-size:13.5px}
        .info-row:last-child{border-bottom:none}
        .info-row .lbl{color:#64748b;width:140px;flex-shrink:0}
        .info-row .val{color:#1e293b;font-weight:500}
        .item-row{display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f8fafc}
        .item-row:last-child{border-bottom:none}
        .item-thumb{width:48px;height:48px;border-radius:7px;object-fit:cover;border:1px solid #e8edf3}
        .item-thumb-empty{width:48px;height:48px;border-radius:7px;background:#eef2f7;display:flex;align-items:center;justify-content:center;color:#94b4cc;font-size:22px}
        .item-nm{font-size:13.5px;font-weight:500;color:#1e293b}
        .item-qty{font-size:12px;color:#94a3b8;margin-top:2px}
        .item-subtotal{margin-left:auto;font-size:14px;font-weight:600;color:#0f2544}
        .badge{font-size:12px;font-weight:500;padding:4px 12px;border-radius:5px;display:inline-flex;align-items:center;gap:5px}
        .badge-yellow{background:#fef9c3;color:#a16207}
        .badge-blue{background:#dbeafe;color:#1d4ed8}
        .badge-green{background:#dcfce7;color:#15803d}
        .sum-row{display:flex;justify-content:space-between;font-size:13.5px;padding:5px 0}
        .sum-row .lbl{color:#64748b}
        .sum-row .val{font-weight:500;color:#1e293b}
        .sum-total{display:flex;justify-content:space-between;padding:12px 0 0;margin-top:8px;border-top:1px solid #e8edf3}
        .sum-total .lbl{font-size:14.5px;font-weight:600;color:#0f172a}
        .sum-total .val{font-size:20px;font-weight:700;color:#0f2544}
        .btn-invoice{display:block;width:100%;background:#0f2544;color:#fff;text-align:center;font-size:13.5px;font-weight:500;padding:11px;border-radius:8px;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:7px;margin-top:16px}
        .btn-invoice:hover{background:#1e3a5f}
    </style>
@endpush

@section('content')
    <a href="{{ route('orders.index') }}" class="back-link">
        <i class="ti ti-arrow-left"></i> Kembali ke pesanan saya
    </a>

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <h1 style="font-size:21px;font-weight:600;color:#0f172a">Detail Pesanan #{{ $order->id }}</h1>
        @if($order->status === 'selesai')
            <span class="badge badge-green"><i class="ti ti-circle-check"></i> Selesai</span>
        @elseif($order->status === 'dikirim')
            <span class="badge badge-blue"><i class="ti ti-truck"></i> Sedang dikirim</span>
        @else
            <span class="badge badge-yellow"><i class="ti ti-clock"></i> Menunggu pembayaran</span>
        @endif
    </div>

    <div class="detail-layout">
        <div>
            <div class="det-card">
                <h3><i class="ti ti-package"></i>Produk Dipesan</h3>
                @foreach($order->items as $item)
                    <div class="item-row">
                        @if($item->product->photo)
                            <img src="{{ asset('storage/'.$item->product->photo) }}" class="item-thumb" alt="{{ $item->product->name }}">
                        @else
                            <div class="item-thumb-empty"><i class="ti ti-package"></i></div>
                        @endif
                        <div style="flex:1;min-width:0">
                            <div class="item-nm">{{ $item->product->name }}</div>
                            <div class="item-qty">{{ $item->weight }}g × {{ $item->quantity }} pcs</div>
                        </div>
                        <div class="item-subtotal">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="det-card">
                <h3><i class="ti ti-map-pin"></i>Alamat Pengiriman</h3>
                <div class="info-row"><span class="lbl">Penerima</span><span class="val">{{ $order->recipient_name }}</span></div>
                <div class="info-row"><span class="lbl">Nomor HP</span><span class="val">{{ $order->phone }}</span></div>
                <div class="info-row"><span class="lbl">Alamat</span><span class="val">{{ $order->address }}</span></div>
                <div class="info-row"><span class="lbl">Kota</span><span class="val">{{ $order->city }}, {{ $order->province }}</span></div>
            </div>
        </div>

        <div>
            <div class="det-card">
                <h3><i class="ti ti-receipt"></i>Ringkasan Biaya</h3>
                <div class="sum-row"><span class="lbl">Subtotal produk</span><span class="val">Rp {{ number_format($order->total_price - $order->shipping_cost, 0, ',', '.') }}</span></div>
                <div class="sum-row"><span class="lbl">Ongkos kirim</span><span class="val">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></div>
                <div class="sum-row"><span class="lbl">Ekspedisi</span><span class="val">{{ strtoupper($order->courier) }} {{ $order->courier_service }}</span></div>
                <div class="sum-row"><span class="lbl">Total berat</span><span class="val">{{ $order->total_weight }}g</span></div>
                <div class="sum-total">
                    <span class="lbl">Total</span>
                    <span class="val">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('orders.invoice', $order) }}" class="btn-invoice">
                    <i class="ti ti-file-type-pdf" style="font-size:16px"></i> Download Invoice PDF
                </a>
            </div>
        </div>
    </div>
@endsection
