@extends('layouts.app')

@section('title', 'Tambah Produk Baru')
@section('breadcrumb', '<i class="bi bi-grid-1x2-fill"></i> <a href="' . route('products.index') . '" style="text-decoration:none;color:inherit;">Katalog Produk</a> <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i> <span>Tambah Baru</span>')

@section('content')
<div class="row g-4" style="max-width:920px;">
    <!-- Left Column: Form -->
    <div class="col-lg-8">
        <div class="mb-4">
            <h1 class="page-heading" style="font-size:1.3rem;">Tambah Produk Baru</h1>
            <p class="page-sub">Isi semua field yang diperlukan untuk mendaftarkan produk ke inventaris sistem.</p>
        </div>

        @if($errors->any())
            <div class="alert-strip-danger d-flex gap-2 align-items-start mb-4">
                <i class="bi bi-exclamation-octagon-fill mt-1" style="color:var(--clr-danger);flex-shrink:0;"></i>
                <div>
                    <div style="font-weight:700;font-size:0.83rem;color:#991b1b;margin-bottom:4px;">Terdapat kesalahan:</div>
                    <ul style="margin:0;padding-left:16px;font-size:0.8rem;color:#991b1b;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" id="createProductForm">
            @csrf

            <!-- Section 1: Identitas -->
            <div class="card-glass p-4 mb-3">
                <div class="form-section-title"><i class="bi bi-tag-fill"></i> Identitas Produk</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom">Nama Produk <span style="color:var(--clr-danger)">*</span></label>
                        <input type="text" name="name" class="form-control-custom {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            placeholder="Contoh: MacBook Air M3 15 inch" value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-5">
                        <label class="form-label-custom d-flex justify-content-between">
                            <span>SKU <span style="color:var(--clr-danger)">*</span></span>
                            <button type="button" onclick="genSku()" style="background:none;border:none;padding:0;font-size:0.75rem;color:var(--clr-primary);font-weight:600;cursor:pointer;">
                                <i class="bi bi-lightning-charge-fill"></i> Auto
                            </button>
                        </label>
                        <input type="text" name="sku" id="skuInput" class="form-control-custom {{ $errors->has('sku') ? 'is-invalid' : '' }}"
                            placeholder="PRD-0001" value="{{ old('sku') }}" style="text-transform:uppercase;" required>
                        @error('sku')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-7">
                        <label class="form-label-custom">Kategori <span style="color:var(--clr-danger)">*</span></label>
                        <select name="category_id" class="form-select-custom {{ $errors->has('category_id') ? 'is-invalid' : '' }}" required>
                            <option value="">— Pilih Kategori —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Tipe Produk <span style="color:var(--clr-danger)">*</span></label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                            @foreach(['physical' => ['Barang Fisik','bi-box-seam'], 'digital' => ['Digital / Lisensi','bi-cloud-arrow-down'], 'service' => ['Layanan / Jasa','bi-people']] as $val => $item)
                                <label style="border:1.5px solid {{ old('product_type','physical') == $val ? 'var(--clr-primary)' : 'var(--clr-border)' }};border-radius:10px;padding:12px;cursor:pointer;transition:all 0.15s;" id="type-label-{{ $val }}">
                                    <input type="radio" name="product_type" value="{{ $val }}" {{ old('product_type','physical') == $val ? 'checked' : '' }} style="display:none;" onchange="setType(this)">
                                    <div style="font-size:1.2rem;color:{{ old('product_type','physical') == $val ? 'var(--clr-primary)' : 'var(--clr-muted)' }};"><i class="bi {{ $item[1] }}"></i></div>
                                    <div style="font-size:0.78rem;font-weight:600;margin-top:4px;">{{ $item[0] }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Harga & Stok -->
            <div class="card-glass p-4 mb-3">
                <div class="form-section-title"><i class="bi bi-currency-dollar"></i> Harga & Stok Inventaris</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Harga Jual <span style="color:var(--clr-danger)">*</span></label>
                        <div class="input-prefix">
                            <span class="prefix-label">Rp</span>
                            <input type="number" min="0" step="0.01" name="price" value="{{ old('price') }}" placeholder="150000" required>
                        </div>
                        @error('price')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Harga Modal / COGS</label>
                        <div class="input-prefix">
                            <span class="prefix-label">Rp</span>
                            <input type="number" min="0" step="0.01" name="cost_price" value="{{ old('cost_price') }}" placeholder="100000">
                        </div>
                        @error('cost_price')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Stok Awal <span style="color:var(--clr-danger)">*</span></label>
                        <input type="number" min="0" name="stock" class="form-control-custom {{ $errors->has('stock') ? 'is-invalid' : '' }}"
                            placeholder="10" value="{{ old('stock', 10) }}" required>
                        @error('stock')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Alert Minimum Stok <span style="color:var(--clr-danger)">*</span></label>
                        <input type="number" min="1" name="min_stock_alert" class="form-control-custom {{ $errors->has('min_stock_alert') ? 'is-invalid' : '' }}"
                            placeholder="5" value="{{ old('min_stock_alert', 5) }}" required>
                        <div style="font-size:0.72rem;color:var(--clr-muted);margin-top:3px;">Alert jika stok &le; angka ini</div>
                        @error('min_stock_alert')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Status <span style="color:var(--clr-danger)">*</span></label>
                        <select name="status" class="form-select-custom">
                            <option value="active" {{ old('status','active') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 3: Deskripsi -->
            <div class="card-glass p-4 mb-4">
                <div class="form-section-title"><i class="bi bi-text-paragraph"></i> Deskripsi Produk</div>
                <textarea name="description" rows="4" class="form-control-custom"
                    placeholder="Tuliskan spesifikasi, fitur utama, atau keterangan produk...">{{ old('description') }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('products.index') }}" class="btn-ghost">
                    <i class="bi bi-arrow-left"></i> Batal
                </a>
                <button type="submit" class="btn-primary-custom">
                    <i class="bi bi-check2-circle"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>

    <!-- Right Column: Tips -->
    <div class="col-lg-4">
        <div style="position:sticky;top:88px;">
            <div class="card-glass p-4" style="background:#1c1008;border-color:rgba(224,123,0,0.2);">
                <div style="color:#fdd996;font-weight:700;font-size:0.85rem;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-lightbulb-fill" style="color:#f59e0b;"></i> Tips Pengisian
                </div>
                <div style="font-size:0.78rem;color:rgba(255,255,255,0.5);line-height:1.7;">
                    <div class="mb-3">
                        <span style="color:#f59e0b;font-weight:600;">SKU (Stock Keeping Unit)</span><br>
                        Gunakan format PREFIX-NOMOR, misal <code style="background:rgba(224,123,0,0.2);padding:1px 5px;border-radius:4px;color:#fdd996;">ELC-001</code>. Klik tombol Auto untuk generate otomatis.
                    </div>
                    <div class="mb-3">
                        <span style="color:#f59e0b;font-weight:600;">Harga Modal (COGS)</span><br>
                        Opsional, digunakan untuk kalkulasi margin keuntungan pada laporan bisnis.
                    </div>
                    <div class="mb-3">
                        <span style="color:#f59e0b;font-weight:600;">Alert Minimum Stok</span><br>
                        Sistem akan otomatis memicu notifikasi peringatan jika stok menyentuh atau melewati batas ini.
                    </div>
                    <div>
                        <span style="color:#f59e0b;font-weight:600;">Tipe Produk</span><br>
                        Pisahkan antara barang fisik, lisensi software/digital, dan layanan konsultasi untuk pelaporan yang akurat.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function genSku() {
        const prefix = ['PRD','ELC','OFF','SFT','SRV','FNB'];
        const p = prefix[Math.floor(Math.random() * prefix.length)];
        document.getElementById('skuInput').value = p + '-' + String(Math.floor(1000 + Math.random() * 9000));
    }

    function setType(radio) {
        document.querySelectorAll('[id^="type-label-"]').forEach(el => {
            el.style.borderColor = 'var(--clr-border)';
            el.querySelector('div').style.color = 'var(--clr-muted)';
        });
        const label = document.getElementById('type-label-' + radio.value);
        if (label) {
            label.style.borderColor = 'var(--clr-primary)';
            label.querySelector('div').style.color = 'var(--clr-primary)';
        }
    }
</script>
@endpush
