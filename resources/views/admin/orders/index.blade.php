@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@push('styles')
    <style>
        .page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
        .page-head h1{font-size:20px;font-weight:600;color:#0f172a}
        .stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px}
        .stat-card{background:#fff;border:1px solid #e8edf3;border-radius:10px;padding:16px 20px;display:flex;align-items:center;gap:14px}
        .stat-icon{width:40px;height:40px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0}
        .stat-icon.yellow{background:#fef9c3;color:#ca8a04}
        .stat-icon.blue{background:#dbeafe;color:#1d4ed8}
        .stat-icon.green{background:#dcfce7;color:#16a34a}
        .stat-val{font-size:22px;font-weight:600;color:#0f172a}
        .stat-lbl{font-size:12px;color:#64748b;margin-top:1px}
        .card{background:#fff;border:1px solid #e8edf3;border-radius:12px;overflow:hidden}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13.5px}
        thead{background:#f8fafc;border-bottom:1px solid #e8edf3}
        th{padding:11px 16px;text-align:left;font-size:12px;font-weight:600;color:#64748b;letter-spacing:.04em;text-transform:uppercase;white-space:nowrap}
        td{padding:13px 16px;border-bottom:1px solid #f1f5f9;color:#1e293b;vertical-align:middle}
        tr:last-child td{border-bottom:none}
        tr:hover td{background:#fafbfc}
        .badge{font-size:11.5px;font-weight:500;padding:3px 10px;border-radius:5px}
        .badge-yellow{background:#fef9c3;color:#a16207}
        .badge-blue{background:#dbeafe;color:#1d4ed8}
        .badge-green{background:#dcfce7;color:#15803d}
        .status-form{display:flex;align-items:center;gap:7px}
        .status-form select{border:1px solid #e2e8f0;border-radius:6px;font-size:12.5px;padding:5px 8px;color:#374151;outline:none;transition:border-color .15s}
        .status-form select:focus{border-color:#0f2544}
        .btn-save{background:#0f2544;color:#fff;font-size:12px;padding:5px 12px;border-radius:6px;border:none;cursor:pointer;transition:background .15s;white-space:nowrap}
        .btn-save:hover{background:#1e3a5f}
        .btn-pdf{font-size:12.5px;color:#16a34a;text-decoration:none;display:inline-flex;align-items:center;gap:5px;padding:5px 10px;border:1px solid #bbf7d0;border-radius:6px;transition:all .15s;white-space:nowrap}
        .btn-pdf:hover{background:#f0fdf4}
        .empty-row td{text-align:center;padding:56px;color:#94a3b8}
        .empty-row i{font-size:40px;display:block;margin-bottom:10px;color:#cbd5e1}
        .paging{padding:16px 20px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end}
        .paging .pagination{display:flex;gap:5px}
        .paging .page-item .page-link{display:flex;align-items:center;justify-content:center;min-width:34px;height:34px;border-radius:7px;font-size:13px;text-decoration:none;border:1px solid #e2e8f0;color:#475569;padding:0 8px}
        .paging .page-item.active .page-link{background:#0f2544;color:#fff;border-color:#0f2544}
    </style>
@endpush

@section('content')
    <div class="page-head">
        <h1>Kelola Pesanan</h1>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="ti ti-clock"></i></div>
            <div>
                <div class="stat-val">{{ $orders->where('status','belum_bayar')->count() }}</div>
                <div class="stat-lbl">Belum bayar</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="ti ti-truck"></i></div>
            <div>
                <div class="stat-val">{{ $orders->where('status','dikirim')->count() }}</div>
                <div class="stat-lbl">Sedang dikirim</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="ti ti-circle-check"></i></div>
            <div>
                <div class="stat-val">{{ $orders->where('status','selesai')->count() }}</div>
                <div class="stat-lbl">Selesai</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Pembeli</th>
                    <th>Total</th>
                    <th>Ekspedisi</th>
                    <th>Tujuan</th>
                    <th>Status</th>
                    <th>Update</th>
                    <th>Invoice</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td style="color:#94a3b8;font-size:12.5px">#{{ $order->id }}</td>
                        <td>
                            <div style="font-weight:500">{{ $order->recipient_name }}</div>
                            <div style="font-size:12px;color:#94a3b8">{{ $order->user->email }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            <div style="font-size:12px;color:#94a3b8">{{ $order->created_at->format('d M Y') }}</div>
                        </td>
                        <td style="font-size:12.5px">{{ strtoupper($order->courier) }} {{ $order->courier_service }}</td>
                        <td style="font-size:12.5px;max-width:130px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $order->city }}, {{ $order->province }}</td>
                        <td>
                            @if($order->status === 'selesai')
                                <span class="badge badge-green"><i class="ti ti-circle-check" style="font-size:12px;vertical-align:-1px"></i> Selesai</span>
                            @elseif($order->status === 'dikirim')
                                <span class="badge badge-blue"><i class="ti ti-truck" style="font-size:12px;vertical-align:-1px"></i> Dikirim</span>
                            @else
                                <span class="badge badge-yellow"><i class="ti ti-clock" style="font-size:12px;vertical-align:-1px"></i> Belum bayar</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="status-form">
                                @csrf @method('PATCH')
                                <select name="status">
                                    <option value="belum_bayar" {{ $order->status==='belum_bayar'?'selected':'' }}>Belum bayar</option>
                                    <option value="dikirim"    {{ $order->status==='dikirim'?'selected':'' }}>Dikirim</option>
                                    <option value="selesai"    {{ $order->status==='selesai'?'selected':'' }}>Selesai</option>
                                </select>
                                <button type="submit" class="btn-save">Simpan</button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('orders.invoice', $order) }}" class="btn-pdf">
                                <i class="ti ti-file-type-pdf" style="font-size:14px"></i> PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="8">
                            <i class="ti ti-receipt-off"></i>
                            Belum ada pesanan masuk.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="paging">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection
