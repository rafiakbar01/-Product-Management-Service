@extends('layouts.app')

@section('title', 'Katalog & Pengelolaan Produk')

@section('content')
<!-- Header Page Title & Action -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Pengelolaan Data Produk (CRUD)</h3>
        <p class="text-muted mb-0">Kelola inventaris, kategori, harga, dan pantau stok modul internal Product Management Service.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-speedometer2"></i> Telemetri & Log
        </a>
        <a href="{{ route('products.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-plus-circle-fill"></i> Tambah Produk Baru
        </a>
    </div>
</div>

<!-- Alert Box: Low Stock Products (UK J.620100.044.01 - Alert Notification) -->
@if($lowStockAlerts->count() > 0)
    <div class="alert alert-warning border-warning-subtle shadow-sm rounded-3 mb-4 p-3" role="alert">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-warning text-dark px-2 py-1 fs-6">
                    <i class="bi bi-bell-fill"></i> Peringatan Sistem
                </span>
                <span class="fw-semibold">
                    Terdapat {{ $lowStockAlerts->count() }} produk dengan stok di bawah batas ambang minimum (Alert Threshold)!
                </span>
            </div>
            <a href="{{ route('products.index', ['stock_status' => 'low_stock']) }}" class="btn btn-sm btn-outline-dark fw-medium">
                Tampilkan Produk Kritis <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="mt-2 pt-2 border-top border-warning-subtle d-flex flex-wrap gap-2">
            @foreach($lowStockAlerts as $alertProduct)
                <span class="badge bg-white text-dark border border-warning shadow-xs py-1 px-2">
                    <strong>{{ $alertProduct->sku }}</strong>: {{ $alertProduct->name }} (Sisa: <span class="text-danger fw-bold">{{ $alertProduct->stock }}</span> / Min: {{ $alertProduct->min_stock_alert }})
                </span>
            @endforeach
        </div>
    </div>
@endif

<!-- Summary Metric Cards (UK J.620100.045.01 - Pemantauan Sederhana) -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary-subtle text-primary">
                    <i class="bi bi-boxes"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Total Produk</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['total_products']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-success-subtle text-success">
                    <i class="bi bi-check2-circle"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Produk Aktif</div>
                    <div class="fs-4 fw-bold">{{ number_format($stats['active_products']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning-subtle text-warning">
                    <i class="bi bi-exclamation-octagon"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Stok Kritis / Menipis</div>
                    <div class="fs-4 fw-bold text-warning">{{ number_format($stats['low_stock_count']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon bg-info-subtle text-info">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <div class="text-muted small fw-medium">Nilai Total Inventaris</div>
                    <div class="fs-5 fw-bold text-dark">Rp {{ number_format($stats['total_inventory_value'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search & Filtering Section (UK J.620100.011.02 - Algoritma Pencarian) -->
<div class="card card-stat mb-4">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row g-2 align-items-center">
                <!-- Search Keyword -->
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Cari nama, SKU, atau deskripsi..." value="{{ request('q') }}">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-2">
                    <select name="category_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Type Filter -->
                <div class="col-md-2">
                    <select name="product_type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="physical" {{ request('product_type') == 'physical' ? 'selected' : '' }}>Fisik (Physical)</option>
                        <option value="digital" {{ request('product_type') == 'digital' ? 'selected' : '' }}>Digital (Lisensi)</option>
                        <option value="service" {{ request('product_type') == 'service' ? 'selected' : '' }}>Jasa (Service)</option>
                    </select>
                </div>

                <!-- Stock Condition Filter -->
                <div class="col-md-2">
                    <select name="stock_status" class="form-select">
                        <option value="">Semua Status Stok</option>
                        <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Tersedia (Aman)</option>
                        <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Stok Menipis (&le; Min)</option>
                        <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Habis (0)</option>
                    </select>
                </div>

                <!-- Submit and Reset Buttons -->
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                    @if(request()->hasAny(['q', 'category_id', 'product_type', 'stock_status', 'min_price', 'max_price']))
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Product Table List -->
<div class="card card-stat">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Daftar Produk</h5>
        <span class="text-muted small">Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</span>
    </div>
    <div class="table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th style="width: 140px;">SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori & Tipe</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-center" style="width: 130px;">Stok</th>
                    <th class="text-center" style="width: 110px;">Status</th>
                    <th class="text-end" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>
                            <code class="fw-semibold text-primary bg-primary-subtle px-2 py-1 rounded">{{ $product->sku }}</code>
                        </td>
                        <td>
                            <a href="{{ route('products.show', $product->id) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                {{ $product->name }}
                            </a>
                            <div class="text-muted small text-truncate" style="max-width: 320px;">
                                {{ $product->description ?: 'Tidak ada deskripsi' }}
                            </div>
                        </td>
                        <td>
                            <div>{{ $product->category->name }}</div>
                            <span class="badge bg-light text-secondary border mt-1">
                                <i class="bi bi-tag-fill me-1"></i> {{ ucfirst($product->product_type) }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-dark">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($product->stock <= 0)
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Habis (0)
                                </span>
                            @elseif($product->isLowStock())
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle px-2 py-1" title="Batas minimum: {{ $product->min_stock_alert }}">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $product->stock }} (Kritis)
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                    {{ $product->stock }} Unit
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($product->status === 'active')
                                <span class="badge bg-success px-2 py-1">Aktif</span>
                            @elseif($product->status === 'inactive')
                                <span class="badge bg-secondary px-2 py-1">Non-aktif</span>
                            @else
                                <span class="badge bg-warning text-dark px-2 py-1">Draft</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-info" title="Lihat Detail & Audit Log">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-primary" title="Edit Produk">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger" title="Hapus Produk" 
                                    onclick="confirmDelete('{{ $product->id }}', '{{ addslashes($product->name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-search fs-1 d-block mb-2 text-secondary"></i>
                            <div class="fw-semibold">Tidak ada produk yang cocok dengan kriteria pencarian.</div>
                            <div class="small">Coba ubah kata kunci atau hapus filter pencarian.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
        <div class="card-footer bg-white py-3 border-top d-flex justify-content-end">
            {{ $products->links() }}
        </div>
    @endif
</div>

<!-- Modal Konfirmasi Hapus Produk (UX Safety) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold" id="deleteModalLabel"><i class="bi bi-trash-fill me-2"></i>Konfirmasi Hapus Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong id="deleteProductName"></strong> dari inventaris?</p>
                <div class="alert alert-info py-2 small mb-0">
                    <i class="bi bi-info-circle me-1"></i> Data akan di-soft delete sehingga riwayat audit trail tetap tersimpan dengan aman.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger fw-semibold">Ya, Hapus Produk</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id, name) {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        document.getElementById('deleteProductName').innerText = name;
        document.getElementById('deleteForm').action = '/products/' + id;
        modal.show();
    }
</script>
@endpush
