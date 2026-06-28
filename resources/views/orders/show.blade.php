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
        .bank-box{background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:16px;margin-bottom:16px}
        .bank-box .bank-title{font-size:13px;font-weight:600;color:#0369a1;margin-bottom:10px;display:flex;align-items:center;gap:6px}
        .bank-row{display:flex;justify-content:space-between;font-size:13.5px;padding:4px 0}
        .bank-row .lbl{color:#64748b}
        .bank-row .val{font-weight:600;color:#0f172a}
        .upload-area{border:2px dashed #e2e8f0;border-radius:10px;padding:20px;text-align:center;cursor:pointer;transition:border-color .15s;margin-bottom:12px}
        .upload-area:hover{border-color:#0f2544}
        .upload-area i{font-size:32px;color:#94a3b8;display:block;margin-bottom:8px}
        .upload-area p{font-size:13px;color:#64748b;margin:0}
        .btn-upload{display:block;width:100%;background:#16a34a;color:#fff;text-align:center;font-size:13.5px;font-weight:500;padding:11px;border-radius:8px;border:none;cursor:pointer;transition:background .15s}
        .btn-upload:hover{background:#15803d}
        .proof-img{width:100%;border-radius:8px;border:1px solid #e8edf3;margin-bottom:12px}
        .alert-success{background:#dcfce7;color:#15803d;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;display:flex;align-items:center;gap:7px}
        .alert-error{background:#fee2e2;color:#dc2626;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;display:flex;align-items:center;gap:7px}
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

    @if(session('success'))
        <div class="alert-success"><i class="ti ti-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error"><i class="ti ti-alert-circle"></i> {{ session('error') }}</div>
    @endif

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

            {{-- Form Upload Bukti Bayar --}}
            @if($order->status === 'belum_bayar')
                <div class="det-card">
                    <h3><i class="ti ti-credit-card"></i>Pembayaran</h3>
                    <div class="bank-box">
                        <div class="bank-title"><i class="ti ti-building-bank"></i> Informasi Rekening</div>
                        <div class="bank-row"><span class="lbl">Bank</span><span class="val">BCA</span></div>
                        <div class="bank-row"><span class="lbl">No. Rekening</span><span class="val">1234567890</span></div>
                        <div class="bank-row"><span class="lbl">Atas Nama</span><span class="val">Roland Purba</span></div>
                        <div class="bank-row"><span class="lbl">Jumlah Transfer</span><span class="val" style="color:#0f2544">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></div>
                    </div>
                    <form method="POST" action="{{ route('orders.payment', $order) }}" enctype="multipart/form-data">
                        @csrf
                        <label class="upload-area" for="payment_proof">
                            <i class="ti ti-upload"></i>
                            <p>Klik untuk upload bukti transfer</p>
                            <p style="font-size:11.5px;color:#94a3b8;margin-top:4px">JPG, JPEG, PNG — Maks 2MB</p>
                        </label>
                        <input type="file" id="payment_proof" name="payment_proof" accept="image/*" style="display:none" onchange="previewImage(this)">
                        <img id="preview" src="" style="display:none" class="proof-img">
                        @error('payment_proof')
                        <div style="color:#dc2626;font-size:12.5px;margin-bottom:8px">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn-upload">
                            <i class="ti ti-upload" style="font-size:14px"></i> Upload Bukti Bayar
                        </button>
                    </form>
                </div>

                {{-- Tampilkan bukti bayar setelah dikirim --}}
            @elseif($order->status === 'dikirim' && $order->payment_proof)
                <div class="det-card">
                    <h3><i class="ti ti-credit-card"></i>Bukti Pembayaran</h3>
                    <img src="{{ asset('storage/'.$order->payment_proof) }}" class="proof-img" alt="Bukti Bayar">
                    @if($order->paid_at)
                        <div style="font-size:12.5px;color:#94a3b8">
                            Dibayar: {{ $order->paid_at->format('d M Y, H:i') }}
                        </div>
                    @endif
                </div>
            @endif
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

@push('scripts')
    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
