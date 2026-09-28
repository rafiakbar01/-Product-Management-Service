@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- Navigation breadcrumb -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('products.index') }}" class="text-decoration-none text-muted small fw-medium mb-1 d-inline-block">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Produk
                </a>
                <h3 class="fw-bold mb-0">Tambah Produk Baru</h3>
            </div>
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                <i class="bi bi-shield-lock me-1"></i> Form Validated (Request Layer)
            </span>
        </div>

        @if($errors->any())
            <div class="alert alert-danger rounded-3 shadow-sm mb-4">
                <div class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i>Terdapat kesalahan pengisian data:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-stat">
            <div class="card-body p-4">
                <form action="{{ route('products.store') }}" method="POST" id="createProductForm">
                    @csrf

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Informasi Utama</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                placeholder="Contoh: MacBook Pro M3 16 Inch" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                                <span>SKU Produk <span class="text-danger">*</span></span>
                                <button type="button" class="btn btn-link p-0 text-decoration-none small text-primary" onclick="generateSku()">
                                    <i class="bi bi-magic"></i> Auto-Generate
                                </button>
                            </label>
                            <input type="text" name="sku" id="skuInput" class="form-control text-uppercase @error('sku') is-invalid @enderror" 
                                placeholder="PRD-001" value="{{ old('sku') }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori Produk <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipe Produk <span class="text-danger">*</span></label>
                            <select name="product_type" class="form-select @error('product_type') is-invalid @enderror" required>
                                <option value="physical" {{ old('product_type', 'physical') == 'physical' ? 'selected' : '' }}>Barang Fisik (Physical)</option>
                                <option value="digital" {{ old('product_type') == 'digital' ? 'selected' : '' }}>Produk Digital / Lisensi (Digital)</option>
                                <option value="service" {{ old('product_type') == 'service' ? 'selected' : '' }}>Jasa Konsultasi / Layanan (Service)</option>
                            </select>
                            @error('product_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Harga & Manajemen Inventaris</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Jual (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" 
                                    placeholder="150000" value="{{ old('price') }}" required>
                            </div>
                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga Pokok / Modal (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" step="0.01" min="0" name="cost_price" class="form-control @error('cost_price') is-invalid @enderror" 
                                    placeholder="100000" value="{{ old('cost_price') }}">
                            </div>
                            @error('cost_price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Jumlah Stok Awal <span class="text-danger">*</span></label>
                            <input type="number" min="0" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                                placeholder="10" value="{{ old('stock', 10) }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Batas Minimum Peringatan (Alert) <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="min_stock_alert" class="form-control @error('min_stock_alert') is-invalid @enderror" 
                                placeholder="5" value="{{ old('min_stock_alert', 5) }}" required>
                            <div class="form-text small">Sistem memicu notifikasi jika stok &le; angka ini.</div>
                            @error('min_stock_alert')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status Produk <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif (Dapat Dijual)</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">Deskripsi Produk</h5>
                    <div class="mb-4">
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror" 
                            placeholder="Tuliskan spesifikasi, fitur lengkap, atau keterangan produk...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function generateSku() {
        const rand = Math.floor(1000 + Math.random() * 9000);
        document.getElementById('skuInput').value = 'PRD-' + rand;
    }
</script>
@endpush
