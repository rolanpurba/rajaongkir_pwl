<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        h2   { text-align: center; margin-bottom: 4px; }
        .sub { text-align: center; color: #777; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f5f5; }
        .total-row td { font-weight: bold; }
        .info { margin-bottom: 16px; }
        .info p { margin: 3px 0; }
    </style>
</head><body>

<h2>CikopakOngkir — Invoice Pesanan</h2>
<p class="sub">No. Order #{{ $order->id }} | {{ $order->created_at->format('d M Y') }}</p>

<div class="info">
    <p><strong>Penerima:</strong> {{ $order->recipient_name }}</p>
    <p><strong>Telepon:</strong> {{ $order->phone }}</p>
    <p><strong>Alamat:</strong> {{ $order->address }}, {{ $order->city }}, {{ $order->province }}</p>
    <p><strong>Ekspedisi:</strong> {{ strtoupper($order->courier) }} {{ $order->courier_service }}</p>
    <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $order->status)) }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>Produk</th><th>Berat</th><th>Qty</th><th>Harga</th><th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->weight }}g</td>
            <td>{{ $item->quantity }}</td>
            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
        </tr>
    @endforeach
    <tr><td colspan="4">Ongkos Kirim</td>
        <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
    <tr class="total-row"><td colspan="4">TOTAL</td>
        <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td></tr>
    </tbody>
</table>
</body></html>
