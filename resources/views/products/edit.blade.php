@extends('layouts.app')

@section('title', 'Edit: ' . $product->name)
@section('breadcrumb', '<i class="bi bi-grid-1x2-fill"></i> <a href="' . route('products.index') . '" style="text-decoration:none;color:inherit;">Katalog Produk</a> <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i> <span>Edit Produk</span>')

@section('content')
<div class="row g-4" style="max-width:920px;">
    <div class="col-lg-8">
        <div class="mb-4 d-flex align-items-start justify-content-between gap-2">
            <div>
                <h1 class="page-heading" style="font-size:1.3rem;">Edit Produk</h1>
                <p class="page-sub">{{ $product->name }}</p>
            </div>
            <span style="background:var(--clr-warning-light);color:#92400e;border:1px solid #fde68a;font-size:0.73rem;font-weight:700;padding:4px 12px;border-radius:20px;white-space:nowrap;align-self:flex-start;">
                ID #{{ $product->id }}
            </span>
        </div>

        @if($errors->any())
            <div class="alert-strip-danger d-flex gap-2 align-items-start mb-4">
                <i class="bi bi-exclamation-octagon-fill mt-1" style="color:var(--clr-danger);flex-shrink:0;"></i>
                <div>
                    <div style="font-weight:700;font-size:0.83rem;color:#991b1b;margin-bottom:4px;">Terdapat kesalahan:</div>
                    <ul style="margin:0;padding-left:16px;font-size:0.8rem;color:#991b1b;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf @method('PUT')

            <!-- Identitas -->
            <div class="card-glass p-4 mb-3">
                <div class="form-section-title"><i class="bi bi-tag-fill"></i> Identitas Produk</div>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label-custom">Nama Produk <span style="color:var(--clr-danger)">*</span></label>
                        <input type="text" name="name" class="form-control-custom {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">SKU <span style="color:var(--clr-danger)">*</span></label>
                        <input type="text" name="sku" class="form-control-custom {{ $errors->has('sku') ? 'is-invalid' : '' }}"
                            value="{{ old('sku', $product->sku) }}" style="text-transform:uppercase;" required>
                        @error('sku')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8">
                        <label class="form-label-custom">Kategori <span style="color:var(--clr-danger)">*</span></label>
                        <select name="category_id" class="form-select-custom" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Tipe Produk <span style="color:var(--clr-danger)">*</span></label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                            @foreach(['physical' => ['Barang Fisik','bi-box-seam'], 'digital' => ['Digital / Lisensi','bi-cloud-arrow-down'], 'service' => ['Layanan / Jasa','bi-people']] as $val => $item)
                                @php $selected = old('product_type', $product->product_type) == $val; @endphp
                                <label style="border:1.5px solid {{ $selected ? 'var(--clr-primary)' : 'var(--clr-border)' }};border-radius:10px;padding:12px;cursor:pointer;" id="type-label-{{ $val }}">
                                    <input type="radio" name="product_type" value="{{ $val }}" {{ $selected ? 'checked' : '' }} style="display:none;" onchange="setType(this)">
                                    <div style="font-size:1.2rem;color:{{ $selected ? 'var(--clr-primary)' : 'var(--clr-muted)' }};"><i class="bi {{ $item[1] }}"></i></div>
                                    <div style="font-size:0.78rem;font-weight:600;margin-top:4px;">{{ $item[0] }}</div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Harga & Stok -->
            <div class="card-glass p-4 mb-3">
                <div class="form-section-title"><i class="bi bi-currency-dollar"></i> Harga & Stok</div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Harga Jual <span style="color:var(--clr-danger)">*</span></label>
                        <div class="input-prefix">
                            <span class="prefix-label">Rp</span>
                            <input type="number" min="0" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
                        </div>
                        @error('price')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Harga Modal / COGS</label>
                        <div class="input-prefix">
                            <span class="prefix-label">Rp</span>
                            <input type="number" min="0" step="0.01" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Stok Tersedia <span style="color:var(--clr-danger)">*</span></label>
                        <input type="number" min="0" name="stock" class="form-control-custom"
                            value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')<div class="invalid-msg">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Alert Minimum Stok <span style="color:var(--clr-danger)">*</span></label>
                        <input type="number" min="1" name="min_stock_alert" class="form-control-custom"
                            value="{{ old('min_stock_alert', $product->min_stock_alert) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom">Status <span style="color:var(--clr-danger)">*</span></label>
                        <select name="status" class="form-select-custom">
                            <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Non-aktif</option>
                            <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="card-glass p-4 mb-4">
                <div class="form-section-title"><i class="bi bi-text-paragraph"></i> Deskripsi</div>
                <textarea name="description" rows="4" class="form-control-custom">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('products.index') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> Batal</a>
                <button type="submit" class="btn-primary-custom"><i class="bi bi-save"></i> Perbarui Data</button>
            </div>
        </form>
    </div>

    <!-- Info Panel -->
    <div class="col-lg-4">
        <div style="position:sticky;top:88px;">
            <div class="card-glass p-4 mb-3">
                <div style="font-weight:700;font-size:0.82rem;color:var(--clr-muted);margin-bottom:12px;text-transform:uppercase;letter-spacing:0.6px;">Informasi Rekaman</div>
                <div style="font-size:0.78rem;color:var(--clr-muted);">
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--clr-border);">
                        <span>SKU</span>
                        <span class="sku-chip">{{ $product->sku }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--clr-border);">
                        <span>Dibuat</span>
                        <span style="color:var(--clr-text);font-weight:500;">{{ $product->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid var(--clr-border);">
                        <span>Terakhir Update</span>
                        <span style="color:var(--clr-text);font-weight:500;">{{ $product->updated_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <span>Stok Saat Ini</span>
                        <span style="color:{{ $product->isLowStock() ? 'var(--clr-danger)' : 'var(--clr-success)' }};font-weight:700;">
                            {{ $product->stock }} unit
                        </span>
                    </div>
                </div>
            </div>
            <a href="{{ route('products.show', $product->id) }}" class="btn-ghost d-flex align-items-center justify-content-center gap-2 w-100">
                <i class="bi bi-clock-history"></i> Lihat Riwayat Audit Log
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
