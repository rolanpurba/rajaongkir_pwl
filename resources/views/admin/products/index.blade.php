@extends('layouts.admin')
@section('title', 'Kelola Produk')

@push('styles')
    <style>
        .page-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:24px}
        .page-head h1{font-size:20px;font-weight:600;color:#0f172a}
        .btn-primary{background:#0f2544;color:#fff;font-size:13.5px;font-weight:500;padding:9px 18px;border-radius:8px;text-decoration:none;display:inline-flex;align-items:center;gap:7px;border:none;cursor:pointer;transition:background .15s}
        .btn-primary:hover{background:#1e3a5f}
        .card{background:#fff;border:1px solid #e8edf3;border-radius:12px;overflow:hidden}
        .table-wrap{overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13.5px}
        thead{background:#f8fafc;border-bottom:1px solid #e8edf3}
        th{padding:11px 16px;text-align:left;font-size:12px;font-weight:600;color:#64748b;letter-spacing:.04em;text-transform:uppercase}
        td{padding:13px 16px;border-bottom:1px solid #f1f5f9;color:#1e293b;vertical-align:middle}
        tr:last-child td{border-bottom:none}
        tr:hover td{background:#fafbfc}
        .prod-thumb{width:44px;height:44px;border-radius:8px;object-fit:cover;border:1px solid #e8edf3}
        .prod-thumb-empty{width:44px;height:44px;border-radius:8px;background:#eef2f7;display:flex;align-items:center;justify-content:center;color:#94b4cc;font-size:20px}
        .badge-stock-ok{background:#f0fdf4;color:#15803d;font-size:11.5px;font-weight:500;padding:3px 9px;border-radius:5px}
        .badge-stock-out{background:#fef2f2;color:#dc2626;font-size:11.5px;font-weight:500;padding:3px 9px;border-radius:5px}
        .btn-edit{font-size:12.5px;padding:5px 12px;border-radius:6px;border:1px solid #e2e8f0;color:#475569;text-decoration:none;transition:all .15s}
        .btn-edit:hover{border-color:#0f2544;color:#0f2544}
        .btn-del{font-size:12.5px;padding:5px 12px;border-radius:6px;border:1px solid #fee2e2;color:#dc2626;background:none;cursor:pointer;transition:all .15s}
        .btn-del:hover{background:#fef2f2}
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
        <h1>Daftar Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn-primary">
            <i class="ti ti-plus"></i> Tambah Produk
        </a>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Berat</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->photo)
                                <img src="{{ asset('storage/'.$product->photo) }}" class="prod-thumb" alt="{{ $product->name }}">
                            @else
                                <div class="prod-thumb-empty"><i class="ti ti-package"></i></div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:500">{{ $product->name }}</div>
                            @if($product->description)
                                <div style="font-size:12px;color:#94a3b8;margin-top:2px;max-width:240px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $product->description }}</div>
                            @endif
                        </td>
                        <td style="font-weight:500">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td style="color:#64748b">{{ $product->weight }}g</td>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge-stock-ok">{{ $product->stock }} tersedia</span>
                            @else
                                <span class="badge-stock-out">Habis</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:8px;align-items:center">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-edit">
                                    <i class="ti ti-edit" style="font-size:13px"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                      onsubmit="return confirm('Hapus produk ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-del">
                                        <i class="ti ti-trash" style="font-size:13px"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="6">
                            <i class="ti ti-package-off"></i>
                            Belum ada produk. <a href="{{ route('admin.products.create') }}" style="color:#0f2544">Tambah sekarang</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="paging">{{ $products->links() }}</div>
        @endif
    </div>
@endsection
