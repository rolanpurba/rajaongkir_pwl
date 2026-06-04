@extends('layouts.admin')
@section('title', 'Tambah Produk')

@push('styles')
    <style>
        .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13.5px;color:#64748b;text-decoration:none;margin-bottom:20px;transition:color .15s}
        .back-link:hover{color:#0f2544}
        .form-card{background:#fff;border:1px solid #e8edf3;border-radius:12px;padding:28px;max-width:640px}
        .form-card h2{font-size:17px;font-weight:600;color:#0f172a;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #f1f5f9}
        .form-group{margin-bottom:18px}
        .form-group label{display:block;font-size:13px;font-weight:500;color:#374151;margin-bottom:6px}
        .form-group input,.form-group textarea,.form-group select{width:100%;border:1px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:13.5px;color:#1e293b;outline:none;transition:border-color .15s;background:#fff}
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:#0f2544;box-shadow:0 0 0 3px rgba(15,37,68,0.07)}
        .form-group textarea{resize:vertical;min-height:80px}
        .form-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
        .form-hint{font-size:12px;color:#94a3b8;margin-top:4px}
        .file-input-wrap{border:2px dashed #e2e8f0;border-radius:8px;padding:20px;text-align:center;transition:border-color .15s;cursor:pointer}
        .file-input-wrap:hover{border-color:#0f2544}
        .file-input-wrap input{display:none}
        .file-input-wrap i{font-size:28px;color:#94a3b8;display:block;margin-bottom:6px}
        .file-input-wrap p{font-size:13px;color:#64748b}
        .file-input-wrap span{font-size:12px;color:#94a3b8}
        .errors{background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:12px 16px;margin-bottom:20px}
        .errors ul{list-style:none;font-size:13.5px;color:#dc2626}
        .errors li{display:flex;align-items:center;gap:6px;padding:2px 0}
        .form-actions{display:flex;gap:10px;margin-top:24px;padding-top:20px;border-top:1px solid #f1f5f9}
        .btn-primary{background:#0f2544;color:#fff;font-size:13.5px;font-weight:500;padding:9px 22px;border-radius:8px;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:7px;transition:background .15s;text-decoration:none}
        .btn-primary:hover{background:#1e3a5f}
        .btn-secondary{background:#fff;color:#475569;font-size:13.5px;padding:9px 18px;border-radius:8px;border:1px solid #e2e8f0;text-decoration:none;transition:all .15s}
        .btn-secondary:hover{border-color:#0f2544;color:#0f2544}
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.products.index') }}" class="back-link">
        <i class="ti ti-arrow-left"></i> Kembali ke daftar produk
    </a>

    <div class="form-card">
        <h2><i class="ti ti-package" style="font-size:18px;vertical-align:-2px;margin-right:8px;color:#0f2544"></i>Tambah Produk Baru</h2>

        @if($errors->any())
            <div class="errors">
                <ul>
                    @foreach($errors->all() as $e)
                        <li><i class="ti ti-circle-x" style="font-size:15px"></i>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>Nama Produk *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Kemeja Flannel Pria" required>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" placeholder="Deskripsi singkat produk (opsional)">{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group" style="margin-bottom:0">
                    <label>Harga (Rp) *</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" placeholder="150000" required>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label>Stok *</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label>Berat (gram) *</label>
                    <input type="number" name="weight" value="{{ old('weight') }}" min="1" placeholder="500" required>
                    <div class="form-hint">Digunakan untuk hitung ongkir</div>
                </div>
            </div>

            <div class="form-group" style="margin-top:18px">
                <label>Foto Produk</label>
                <div class="file-input-wrap" onclick="document.getElementById('photo').click()">
                    <input type="file" name="photo" id="photo" accept="image/*" onchange="previewPhoto(this)">
                    <i class="ti ti-photo-up"></i>
                    <p id="file-label">Klik untuk upload foto</p>
                    <span>JPG, PNG — maks. 2MB</span>
                    <img id="preview" src="" style="display:none;max-height:120px;margin:10px auto 0;border-radius:6px">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Simpan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('preview').style.display = 'block';
                    document.getElementById('file-label').textContent = input.files[0].name;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
