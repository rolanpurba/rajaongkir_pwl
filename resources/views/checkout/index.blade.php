@extends('layouts.app')
@section('title', 'Checkout')

@push('styles')
    <style>
        .checkout-layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
        .section-card{background:#fff;border:1px solid #e8edf3;border-radius:12px;padding:22px;margin-bottom:16px}
        .section-card h3{font-size:15px;font-weight:600;color:#0f172a;margin-bottom:18px;display:flex;align-items:center;gap:8px}
        .section-card h3 i{font-size:18px;color:#0f2544}
        .form-group{margin-bottom:14px}
        .form-group label{display:block;font-size:12.5px;font-weight:500;color:#374151;margin-bottom:5px}
        .form-group input,.form-group textarea,.form-group select{width:100%;border:1px solid #e2e8f0;border-radius:7px;padding:8px 12px;font-size:13.5px;color:#1e293b;outline:none;transition:border-color .15s;background:#fff}
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus{border-color:#0f2544;box-shadow:0 0 0 3px rgba(15,37,68,0.06)}
        .form-group select:disabled{background:#f8fafc;color:#94a3b8}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .kurir-select{display:flex;gap:10px;flex-wrap:wrap}
        .kurir-btn{border:1px solid #e2e8f0;border-radius:8px;padding:8px 16px;font-size:13px;color:#475569;cursor:pointer;transition:all .15s;background:#fff;display:flex;align-items:center;gap:6px}
        .kurir-btn:hover{border-color:#0f2544;color:#0f2544}
        .kurir-btn.active{background:#0f2544;color:#fff;border-color:#0f2544}
        .ongkir-loading{font-size:13.5px;color:#64748b;padding:14px 0;display:none;align-items:center;gap:8px}
        .ongkir-error{font-size:13.5px;color:#dc2626;padding:10px;background:#fef2f2;border-radius:7px;display:none;align-items:center;gap:8px}
        .ongkir-container{display:none}
        .ongkir-hint{font-size:12px;color:#94a3b8;margin-bottom:10px}
        .ongkir-table{width:100%;border-collapse:collapse;font-size:13px}
        .ongkir-table th{padding:8px 12px;background:#f8fafc;text-align:left;font-size:11.5px;font-weight:600;color:#64748b;border-bottom:1px solid #e8edf3}
        .ongkir-table td{padding:10px 12px;border-bottom:1px solid #f1f5f9;color:#374151;vertical-align:middle}
        .ongkir-table tr:last-child td{border-bottom:none}
        .ongkir-table tr:hover td{background:#f8fafc}
        .ongkir-table input[type=radio]{width:16px;height:16px;accent-color:#0f2544;cursor:pointer}
        .ongkir-service{font-weight:500;color:#0f172a}
        .ongkir-price{font-weight:600;color:#0f2544}
        .ongkir-etd{font-size:12px;color:#64748b}
        .order-summary{background:#fff;border:1px solid #e8edf3;border-radius:12px;padding:20px;position:sticky;top:80px}
        .order-summary h3{font-size:15px;font-weight:600;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9}
        .sum-item{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px}
        .sum-item .lbl{color:#64748b;flex:1;margin-right:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .sum-item .val{font-weight:500;color:#1e293b;flex-shrink:0}
        .sum-divider{border:none;border-top:1px solid #f1f5f9;margin:10px 0}
        .sum-total{display:flex;justify-content:space-between;align-items:center;padding:10px 0}
        .sum-total .lbl{font-size:14px;font-weight:600;color:#0f172a}
        .sum-total .val{font-size:19px;font-weight:700;color:#0f2544}
        .sum-ongkir{display:flex;justify-content:space-between;align-items:center;padding:5px 0;font-size:13px}
        .sum-ongkir .lbl{color:#64748b}
        .sum-ongkir .val{font-weight:500;color:#1e293b}
        .btn-order{display:block;width:100%;background:#0f2544;color:#fff;text-align:center;font-size:14px;font-weight:500;padding:13px;border-radius:9px;border:none;cursor:pointer;margin-top:16px;transition:background .15s}
        .btn-order:hover:not(:disabled){background:#1e3a5f}
        .btn-order:disabled{opacity:.4;cursor:not-allowed}
        .btn-order-note{font-size:12px;color:#94a3b8;text-align:center;margin-top:8px}
    </style>
@endpush

@section('content')
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px">
        <h1 style="font-size:22px;font-weight:600;color:#0f172a">Checkout</h1>
    </div>

    <form method="POST" action="{{ route('orders.store') }}" id="checkout-form">
        @csrf
        <input type="hidden" name="courier_service" id="input_courier_service">
        <input type="hidden" name="shipping_cost"   id="input_shipping_cost">
        <input type="hidden" name="total_weight"    id="total_weight" value="{{ $totalWeight }}">
        {{-- Nama provinsi & kota teks untuk disimpan ke DB --}}
        <input type="hidden" name="province" id="input_province_name">
        <input type="hidden" name="city"     id="input_city_name">
        <input type="hidden" name="city_id"  id="city_id">

        <div class="checkout-layout">
            <div>
                {{-- Alamat --}}
                <div class="section-card">
                    <h3><i class="ti ti-map-pin"></i>Alamat Pengiriman</h3>
                    <div class="form-group">
                        <label>Nama Penerima *</label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP *</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat Lengkap *</label>
                        <textarea name="address" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..." required>{{ old('address') }}</textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0">
                            <label>Provinsi *</label>
                            {{--
                                API V2: response provinsi pakai key 'id' dan 'name'
                                (bukan 'province_id' dan 'province' seperti API lama)
                                Kita cek dua kemungkinan dengan null coalescing
                            --}}
                            <select id="province" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $prov)
                                    <option
                                        value="{{ $prov['id'] ?? $prov['province_id'] ?? '' }}"
                                        data-name="{{ $prov['name'] ?? $prov['province'] ?? '' }}">
                                        {{ $prov['name'] ?? $prov['province'] ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label>Kota/Kabupaten *</label>
                            <select id="city" disabled required>
                                <option value="">Pilih Kota</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Ekspedisi --}}
                <div class="section-card">
                    <h3><i class="ti ti-truck"></i>Ekspedisi & Ongkos Kirim</h3>
                    <div class="form-group">
                        <label>Pilih Kurir *</label>
                        <div class="kurir-select">
                            <button type="button" class="kurir-btn" data-kurir="jne" onclick="pilihKurir(this)">
                                <i class="ti ti-truck" style="font-size:14px"></i> JNE
                            </button>
                            <button type="button" class="kurir-btn" data-kurir="pos" onclick="pilihKurir(this)">
                                <i class="ti ti-truck" style="font-size:14px"></i> POS Indonesia
                            </button>
                            <button type="button" class="kurir-btn" data-kurir="tiki" onclick="pilihKurir(this)">
                                <i class="ti ti-truck" style="font-size:14px"></i> TIKI
                            </button>
                        </div>
                        <input type="hidden" name="courier" id="courier-hidden">
                    </div>
                    <div style="font-size:12.5px;color:#94a3b8;margin-top:4px;display:flex;align-items:center;gap:5px">
                        <i class="ti ti-weight" style="font-size:14px"></i>
                        Total berat: <strong style="color:#475569">{{ $totalWeight }}g</strong>
                    </div>

                    <div class="ongkir-loading" id="ongkir-loading">
                        <i class="ti ti-loader-2" style="font-size:16px"></i> Menghitung ongkir...
                    </div>
                    <div class="ongkir-error" id="ongkir-error">
                        <i class="ti ti-alert-circle" style="font-size:16px"></i> Gagal mengambil data ongkir. Coba lagi.
                    </div>
                    <div class="ongkir-container" id="ongkir-container">
                        <div class="ongkir-hint">Pilih layanan pengiriman:</div>
                        <table class="ongkir-table">
                            <thead>
                            <tr>
                                <th>Layanan</th>
                                <th>Keterangan</th>
                                <th>Biaya</th>
                                <th>Estimasi</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="ongkir-list"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="order-summary">
                <h3>Ringkasan Pesanan</h3>
                @foreach($items as $item)
                    <div class="sum-item">
                        <span class="lbl">{{ $item->product->name }} ×{{ $item->quantity }}</span>
                        <span class="val">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>
                @endforeach
                <hr class="sum-divider">
                <div class="sum-ongkir">
                    <span class="lbl">Subtotal produk</span>
                    <span class="val">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="sum-ongkir">
                    <span class="lbl">Ongkos kirim</span>
                    <span class="val" id="display-ongkir" style="color:#94a3b8">—</span>
                </div>
                <hr class="sum-divider">
                <div class="sum-total">
                    <span class="lbl">Total</span>
                    <span class="val" id="display-total">—</span>
                </div>
                <button type="submit" class="btn-order" id="btn-order" disabled>
                    <i class="ti ti-lock" style="font-size:15px;vertical-align:-2px;margin-right:4px"></i>
                    Buat Pesanan
                </button>
                <div class="btn-order-note">Pilih layanan ongkir untuk melanjutkan</div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const subtotal = {{ $totalPrice }};
        let selectedKurir = '';

        function pilihKurir(el) {
            document.querySelectorAll('.kurir-btn').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            selectedKurir = el.dataset.kurir;
            document.getElementById('courier-hidden').value = selectedKurir;
            hitungOngkir();
        }

        // ── Provinsi berubah → load kota via AJAX ──
        document.getElementById('province').addEventListener('change', async function() {
            const provId   = this.value;
            const provName = this.options[this.selectedIndex].dataset.name;
            const cityEl   = document.getElementById('city');

            cityEl.innerHTML = '<option value="">Memuat kota...</option>';
            cityEl.disabled  = true;
            resetOngkir();

            // Simpan nama provinsi ke hidden input
            document.getElementById('input_province_name').value = provName || provId;

            try {
                const res    = await fetch(`/checkout/cities?province_id=${provId}`);
                const cities = await res.json();

                cityEl.innerHTML = '<option value="">Pilih Kota</option>';
                cities.forEach(c => {
                    // API V2: key 'id' dan 'name' — fallback ke key lama jika pakai hardcode
                    const cityId   = c.id   ?? c.city_id   ?? '';
                    const cityName = c.name ?? c.city_name ?? '';
                    const cityType = c.type ?? '';
                    cityEl.innerHTML += `<option value="${cityId}" data-name="${cityName}">${cityType} ${cityName}`.trim() + `</option>`;
                });
                cityEl.disabled = false;
            } catch(e) {
                cityEl.innerHTML = '<option value="">Gagal memuat kota</option>';
            }
        });

        // ── Kota berubah → simpan ke hidden, hitung ongkir ──
        document.getElementById('city').addEventListener('change', function() {
            const opt      = this.options[this.selectedIndex];
            const cityId   = this.value;
            const cityName = opt.dataset.name || opt.text.trim();

            document.getElementById('city_id').value          = cityId;
            document.getElementById('input_city_name').value  = cityName;
            hitungOngkir();
        });

        // ── Hitung ongkir via AJAX ──
        async function hitungOngkir() {
            const cityId = document.getElementById('city_id').value;
            if (!cityId || !selectedKurir) return;
            const weight = document.getElementById('total_weight').value;

            document.getElementById('ongkir-loading').style.display   = 'flex';
            document.getElementById('ongkir-container').style.display = 'none';
            document.getElementById('ongkir-error').style.display     = 'none';
            resetOngkir();

            try {
                const res  = await fetch(`/checkout/cost?city_id=${cityId}&courier=${selectedKurir}&weight=${weight}`);
                const data = await res.json();

                // Tangani jika API error (misal key 'error' ada di response)
                if (!Array.isArray(data) || data.length === 0) {
                    throw new Error('Data ongkir kosong atau error');
                }

                const tbody = document.getElementById('ongkir-list');
                tbody.innerHTML = data.map(c => {
                    // API V2 mungkin pakai struktur berbeda — handle dua kemungkinan
                    const svc   = c.service     ?? c.code        ?? '-';
                    const desc  = c.description ?? c.name        ?? '-';
                    const price = c.cost?.[0]?.value ?? c.price  ?? 0;
                    const etd   = c.cost?.[0]?.etd   ?? c.etd    ?? '-';
                    return `
            <tr>
                <td><span class="ongkir-service">${selectedKurir.toUpperCase()} ${svc}</span></td>
                <td style="font-size:12px;color:#64748b">${desc}</td>
                <td><span class="ongkir-price">Rp ${price.toLocaleString('id-ID')}</span></td>
                <td><span class="ongkir-etd">${etd} hari</span></td>
                <td style="text-align:center">
                    <input type="radio" name="selected_service_radio"
                        value="${svc}:${price}"
                        onchange="piliService('${svc}', ${price})">
                </td>
            </tr>`;
                }).join('');

                document.getElementById('ongkir-loading').style.display   = 'none';
                document.getElementById('ongkir-container').style.display = 'block';
            } catch(e) {
                document.getElementById('ongkir-loading').style.display = 'none';
                document.getElementById('ongkir-error').style.display   = 'flex';
            }
        }

        // ── User pilih layanan ongkir ──
        function piliService(service, cost) {
            document.getElementById('input_courier_service').value = service;
            document.getElementById('input_shipping_cost').value   = cost;
            document.getElementById('display-ongkir').textContent  = `Rp ${cost.toLocaleString('id-ID')}`;
            document.getElementById('display-ongkir').style.color  = '#1e293b';
            document.getElementById('display-total').textContent   = `Rp ${(subtotal + cost).toLocaleString('id-ID')}`;
            document.getElementById('btn-order').disabled          = false;
            document.querySelector('.btn-order-note').style.display = 'none';
        }

        function resetOngkir() {
            document.getElementById('input_courier_service').value  = '';
            document.getElementById('input_shipping_cost').value    = '';
            document.getElementById('display-ongkir').textContent   = '—';
            document.getElementById('display-ongkir').style.color   = '#94a3b8';
            document.getElementById('display-total').textContent    = '—';
            document.getElementById('btn-order').disabled           = true;
            document.querySelector('.btn-order-note').style.display = 'block';
        }
    </script>
@endpush
