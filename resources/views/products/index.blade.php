@extends('layouts.app')

@section('title', 'Master Katalog IT — Inventaris Aset & Produk Kantor')
@section('breadcrumb')
@endsection

@section('content')
<div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
    <div>
        <h1 class="page-heading">Master Katalog Inventaris IT Kantor</h1>
        <p class="page-sub mb-0">Kelola aset hardware, lisensi software, dan kontrak layanan IT perusahaan — pantau stok, nilai, dan riwayat audit.</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn-primary-custom" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Registrasi Aset / Produk Baru
        </button>
    </div>
</div>

<!-- Low Stock Alert Strip -->
@if($lowStockAlerts->count() > 0)
<div class="alert-strip mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div class="d-flex align-items-center gap-2">
        <span style="width:34px;height:34px;background:#f59e0b;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.95rem;flex-shrink:0;">
            <i class="bi bi-bell-fill"></i>
        </span>
        <div>
            <div style="font-weight:700;font-size:0.87rem;color:#92400e;">
                {{ $lowStockAlerts->count() }} Produk Stok Kritis Terdeteksi
            </div>
            <div class="d-flex flex-wrap gap-1 mt-1">
                @foreach($lowStockAlerts as $a)
                    <span style="background:#fef3c7;border:1px solid #fde68a;padding:2px 8px;border-radius:20px;font-size:0.7rem;font-weight:600;color:#92400e;">
                        {{ $a->sku }}: <span style="color:#dc2626;">{{ $a->stock }}</span>/{{ $a->min_stock_alert }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="{{ route('products.index', ['stock_status' => 'low_stock']) }}" style="font-size:0.78rem;color:#92400e;font-weight:700;text-decoration:none;white-space:nowrap;">
            Filter Stok Menipis <i class="bi bi-arrow-right"></i>
        </a>
    </div>
</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card orange">
            <div class="icon-wrap"><i class="bi bi-archive"></i></div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-label">Total Item Terdaftar</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card green">
            <div class="icon-wrap"><i class="bi bi-check-circle"></i></div>
            <div class="stat-value">{{ number_format($stats['active_products']) }}</div>
            <div class="stat-label">Aset / Lisensi Aktif</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card amber">
            <div class="icon-wrap"><i class="bi bi-exclamation-octagon"></i></div>
            <div class="stat-value" style="color:var(--clr-accent);">{{ number_format($stats['low_stock_count']) }}</div>
            <div class="stat-label">Stok Kritis / Perlu Reorder</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card gold">
            <div class="icon-wrap"><i class="bi bi-coin"></i></div>
            <div class="stat-value" style="font-size:1.15rem;">Rp {{ number_format($stats['total_inventory_value'], 0, ',', '.') }}</div>
            <div class="stat-label">Total Nilai Aset Kantor</div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="card-glass mb-4 p-4">
    <form method="GET" action="{{ route('products.index') }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label-custom">Cari Produk</label>
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" class="form-control-custom search-input" placeholder="Nama, SKU, atau deskripsi..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Kategori</label>
                <select name="category_id" class="form-select-custom">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Tipe Aset</label>
                <select name="product_type" class="form-select-custom">
                    <option value="">Semua Tipe</option>
                    <option value="physical" {{ request('product_type') == 'physical' ? 'selected' : '' }}>Hardware Asset</option>
                    <option value="digital" {{ request('product_type') == 'digital' ? 'selected' : '' }}>Lisensi / SaaS</option>
                    <option value="service" {{ request('product_type') == 'service' ? 'selected' : '' }}>Layanan IT</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label-custom">Kondisi Stok</label>
                <select name="stock_status" class="form-select-custom">
                    <option value="">Semua Kondisi</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Tersedia Aman</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Menipis (&le; Alert)</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Stok Habis (0)</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-primary-custom w-100" style="justify-content:center;">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request()->hasAny(['q','category_id','product_type','stock_status']))
                    <a href="{{ route('products.index') }}" class="btn-ghost" title="Reset Filter" style="padding:9px 12px;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Product Table -->
<div class="card-glass">
    <div style="padding:18px 22px;border-bottom:1px solid var(--clr-border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
        <div class="d-flex align-items-center gap-2">
            <span style="font-weight:700;font-size:0.92rem;color:var(--clr-text);">Daftar Master Produk</span>
            <span class="badge" style="background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.72rem;padding:3px 8px;border-radius:20px;">
                {{ $products->total() }} Data
            </span>
        </div>
        <div style="font-size:0.75rem;color:var(--clr-muted);">
            Menampilkan {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:130px;">SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori &amp; Tipe</th>
                    <th class="text-end">Harga Jual</th>
                    <th class="text-center" style="width:120px;">Stok</th>
                    <th class="text-center" style="width:100px;">Status</th>
                    <th class="text-center" style="width:140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    @php
                        $productJson = json_encode([
                            'id' => $product->id,
                            'name' => $product->name,
                            'sku' => $product->sku,
                            'category_id' => $product->category_id,
                            'category_name' => $product->category->name ?? '—',
                            'product_type' => $product->product_type,
                            'price' => (float) $product->price,
                            'cost_price' => $product->cost_price ? (float) $product->cost_price : null,
                            'stock' => (int) $product->stock,
                            'min_stock_alert' => (int) $product->min_stock_alert,
                            'status' => $product->status,
                            'description' => $product->description ?? '',
                            'created_at_fmt' => $product->created_at ? $product->created_at->format('d M Y, H:i') : '—',
                            'updated_at_fmt' => $product->updated_at ? $product->updated_at->format('d M Y, H:i') : '—',
                        ]);
                    @endphp
                    <tr>
                        <td><span class="sku-chip">{{ $product->sku }}</span></td>
                        <td>
                            <div style="font-weight:600;color:var(--clr-text);cursor:pointer;" onclick="openViewModalFromRow({{ $product->id }})" title="Klik untuk lihat detail">
                                {{ $product->name }}
                            </div>
                            <div style="font-size:0.75rem;color:var(--clr-muted);margin-top:2px;max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ $product->description ?: 'Tidak ada deskripsi' }}
                            </div>
                        </td>
                        <td>
                            <div style="font-size:0.82rem;font-weight:500;">{{ $product->category->name ?? '—' }}</div>
                            <span class="type-chip mt-1">{{ ucfirst($product->product_type) }}</span>
                        </td>
                        <td class="text-end" style="font-weight:700;font-size:0.87rem;">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($product->stock <= 0)
                                <span class="badge-status badge-out"><i class="bi bi-x-circle me-1"></i>Habis</span>
                            @elseif($product->isLowStock())
                                <span class="badge-status badge-low"><i class="bi bi-exclamation-triangle me-1"></i>{{ $product->stock }} sisa</span>
                            @else
                                <span class="badge-status badge-ok">{{ $product->stock }} unit</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($product->status === 'active')
                                <span class="badge-status badge-active">Aktif</span>
                            @elseif($product->status === 'inactive')
                                <span class="badge-status badge-inactive">Non-aktif</span>
                            @else
                                <span class="badge-status badge-draft">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <!-- View Modal Trigger -->
                                <button type="button" class="btn-view-sm" title="Lihat Detail Produk (Modal)"
                                    data-product='{{ $productJson }}'
                                    onclick="openViewModal(this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <!-- Edit Modal Trigger -->
                                <button type="button" class="btn-edit-sm" title="Edit Data Produk (Modal)"
                                    data-product='{{ $productJson }}'
                                    onclick="openEditModal(this)">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <!-- Delete Modal Trigger -->
                                <button type="button" class="btn-danger-sm" title="Hapus Produk" 
                                    onclick="confirmDelete('{{ $product->id }}', '{{ addslashes($product->name) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:56px 20px;color:var(--clr-muted);">
                            <i class="bi bi-inbox" style="font-size:2.4rem;display:block;margin-bottom:8px;opacity:0.3;"></i>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--clr-text);">Tidak ada produk ditemukan</div>
                            <div style="font-size:0.78rem;margin-top:4px;">Coba sesuaikan kata kunci atau gunakan tombol "Tambah Produk Baru".</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--clr-border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <div style="font-size:0.78rem;color:var(--clr-muted);">
                Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
            </div>
            <div>
                {{ $products->links() }}
            </div>
        </div>
    @endif
</div>

<!-- ========================================================
     MODAL 1: CREATE PRODUCT (TAMBAH PRODUK BARU)
======================================================== -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:18px;border:1px solid var(--clr-border);overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,0.15);">
            <!-- Modal Header -->
            <div style="background:#1c1008;padding:20px 26px;border-bottom:1px solid rgba(224,123,0,0.25);display:flex;align-items:center;justify-content:space-between;">
                <div class="d-flex align-items-center gap-3">
                    <span style="width:38px;height:38px;background:var(--clr-primary);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                        <i class="bi bi-plus-lg"></i>
                    </span>
                    <div>
                        <h5 class="modal-title m-0" id="createProductModalLabel" style="color:#fdf0dc;font-weight:700;font-size:1.05rem;">
                            Tambah Produk Baru
                        </h5>
                        <p class="m-0" style="color:rgba(255,255,255,0.5);font-size:0.75rem;">
                            Lengkapi parameter inventaris untuk mendaftarkan SKU baru
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Form -->
            <form action="{{ route('products.store') }}" method="POST" id="createProductForm">
                @csrf
                <div class="modal-body p-4" style="max-height:calc(85vh - 140px);overflow-y:auto;background:var(--clr-bg);">
                    
                    @if($errors->any() && !old('_edit_id'))
                        <div class="alert-strip-danger d-flex gap-2 align-items-start mb-4">
                            <i class="bi bi-exclamation-octagon-fill mt-1" style="color:var(--clr-danger);flex-shrink:0;"></i>
                            <div>
                                <div style="font-weight:700;font-size:0.83rem;color:#991b1b;margin-bottom:4px;">Terdapat kesalahan validasi:</div>
                                <ul style="margin:0;padding-left:16px;font-size:0.8rem;color:#991b1b;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Section: Identitas Produk -->
                    <div class="card-glass p-3 mb-3">
                        <div class="form-section-title"><i class="bi bi-tag-fill"></i> Identitas &amp; Pengelompokan</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-custom">Nama Produk <span style="color:var(--clr-danger)">*</span></label>
                                <input type="text" name="name" class="form-control-custom {{ $errors->has('name') && !old('_edit_id') ? 'is-invalid' : '' }}"
                                    placeholder="Contoh: Asus ROG Zephyrus G16" value="{{ !old('_edit_id') ? old('name') : '' }}" required>
                                @if(!old('_edit_id')) @error('name')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-5">
                                <label class="form-label-custom d-flex justify-content-between">
                                    <span>SKU <span style="color:var(--clr-danger)">*</span></span>
                                    <button type="button" onclick="genSku('createSkuInput')" style="background:none;border:none;padding:0;font-size:0.75rem;color:var(--clr-primary);font-weight:700;cursor:pointer;">
                                        <i class="bi bi-lightning-charge-fill"></i> Auto Generate
                                    </button>
                                </label>
                                <input type="text" name="sku" id="createSkuInput" class="form-control-custom {{ $errors->has('sku') && !old('_edit_id') ? 'is-invalid' : '' }}"
                                    placeholder="PRD-0001" value="{{ !old('_edit_id') ? old('sku') : '' }}" style="text-transform:uppercase;" required>
                                @if(!old('_edit_id')) @error('sku')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-7">
                                <label class="form-label-custom">Kategori <span style="color:var(--clr-danger)">*</span></label>
                                <select name="category_id" class="form-select-custom {{ $errors->has('category_id') && !old('_edit_id') ? 'is-invalid' : '' }}" required>
                                    <option value="">— Pilih Kategori Produk —</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (!old('_edit_id') && old('category_id') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @if(!old('_edit_id')) @error('category_id')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">Tipe Aset / Produk <span style="color:var(--clr-danger)">*</span></label>
                                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                                    @foreach(['physical' => ['Hardware Asset','bi-cpu'], 'digital' => ['Lisensi / SaaS','bi-cloud-check'], 'service' => ['Layanan IT','bi-headset']] as $val => $item)
                                        <label style="border:1.5px solid {{ (!old('_edit_id') && old('product_type','physical') == $val) ? 'var(--clr-primary)' : 'var(--clr-border)' }};border-radius:10px;padding:10px;cursor:pointer;transition:all 0.15s;" id="create-type-label-{{ $val }}">
                                            <input type="radio" name="product_type" value="{{ $val }}" {{ (!old('_edit_id') && old('product_type','physical') == $val) ? 'checked' : '' }} style="display:none;" onchange="setCreateType(this)">
                                            <div style="font-size:1.1rem;color:{{ (!old('_edit_id') && old('product_type','physical') == $val) ? 'var(--clr-primary)' : 'var(--clr-muted)' }};"><i class="bi {{ $item[1] }}"></i></div>
                                            <div style="font-size:0.75rem;font-weight:600;margin-top:3px;">{{ $item[0] }}</div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Finansial & Inventaris -->
                    <div class="card-glass p-3 mb-3">
                        <div class="form-section-title"><i class="bi bi-cash-stack"></i> Harga &amp; Pengelolaan Stok</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Harga Jual <span style="color:var(--clr-danger)">*</span></label>
                                <div class="input-prefix">
                                    <span class="prefix-label">Rp</span>
                                    <input type="number" min="0" step="0.01" name="price" value="{{ !old('_edit_id') ? old('price') : '' }}" placeholder="150000" required>
                                </div>
                                @if(!old('_edit_id')) @error('price')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Harga Modal / COGS (Opsional)</label>
                                <div class="input-prefix">
                                    <span class="prefix-label">Rp</span>
                                    <input type="number" min="0" step="0.01" name="cost_price" value="{{ !old('_edit_id') ? old('cost_price') : '' }}" placeholder="100000">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Stok Awal <span style="color:var(--clr-danger)">*</span></label>
                                <input type="number" min="0" name="stock" class="form-control-custom"
                                    placeholder="10" value="{{ !old('_edit_id') ? old('stock', 10) : 10 }}" required>
                                @if(!old('_edit_id')) @error('stock')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Ambang Alert Stok <span style="color:var(--clr-danger)">*</span></label>
                                <input type="number" min="1" name="min_stock_alert" class="form-control-custom"
                                    placeholder="5" value="{{ !old('_edit_id') ? old('min_stock_alert', 5) : 5 }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Status <span style="color:var(--clr-danger)">*</span></label>
                                <select name="status" class="form-select-custom">
                                    <option value="active" {{ (!old('_edit_id') && old('status','active') == 'active') ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive" {{ (!old('_edit_id') && old('status') == 'inactive') ? 'selected' : '' }}>Non-aktif</option>
                                    <option value="draft" {{ (!old('_edit_id') && old('status') == 'draft') ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Deskripsi -->
                    <div class="card-glass p-3">
                        <div class="form-section-title"><i class="bi bi-text-paragraph"></i> Deskripsi &amp; Spesifikasi</div>
                        <textarea name="description" rows="3" class="form-control-custom"
                            placeholder="Tuliskan spesifikasi produk, kelengkapan paket, garansi, dll...">{{ !old('_edit_id') ? old('description') : '' }}</textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer" style="background:#fff;border-top:1px solid var(--clr-border);padding:14px 24px;">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check2-circle"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL 2: EDIT PRODUCT (EDIT DATA PRODUK)
======================================================== -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:18px;border:1px solid var(--clr-border);overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,0.15);">
            <!-- Modal Header -->
            <div style="background:#1c1008;padding:20px 26px;border-bottom:1px solid rgba(224,123,0,0.25);display:flex;align-items:center;justify-content:space-between;">
                <div class="d-flex align-items-center gap-3">
                    <span style="width:38px;height:38px;background:var(--clr-gold);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;">
                        <i class="bi bi-pencil-square"></i>
                    </span>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="modal-title m-0" id="editProductModalLabel" style="color:#fdf0dc;font-weight:700;font-size:1.05rem;">
                                Edit Data Produk
                            </h5>
                            <span id="editBadgeId" class="badge" style="background:rgba(224,123,0,0.25);color:#f59e0b;border:1px solid rgba(224,123,0,0.3);font-size:0.7rem;">ID #—</span>
                        </div>
                        <p class="m-0" id="editHeaderSubtitle" style="color:rgba(255,255,255,0.5);font-size:0.75rem;">
                            Perbarui rincian inventaris dan harga jual
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Form -->
            <form action="" method="POST" id="editProductForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="_edit_id" id="editHiddenId" value="{{ old('_edit_id') }}">

                <div class="modal-body p-4" style="max-height:calc(85vh - 140px);overflow-y:auto;background:var(--clr-bg);">
                    
                    @if($errors->any() && old('_edit_id'))
                        <div class="alert-strip-danger d-flex gap-2 align-items-start mb-4">
                            <i class="bi bi-exclamation-octagon-fill mt-1" style="color:var(--clr-danger);flex-shrink:0;"></i>
                            <div>
                                <div style="font-weight:700;font-size:0.83rem;color:#991b1b;margin-bottom:4px;">Terdapat kesalahan validasi pembaruan:</div>
                                <ul style="margin:0;padding-left:16px;font-size:0.8rem;color:#991b1b;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <!-- Section: Identitas Produk -->
                    <div class="card-glass p-3 mb-3">
                        <div class="form-section-title"><i class="bi bi-tag-fill"></i> Identitas &amp; Pengelompokan</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label-custom">Nama Produk <span style="color:var(--clr-danger)">*</span></label>
                                <input type="text" name="name" id="editName" class="form-control-custom {{ $errors->has('name') && old('_edit_id') ? 'is-invalid' : '' }}"
                                    value="{{ old('_edit_id') ? old('name') : '' }}" required>
                                @if(old('_edit_id')) @error('name')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-5">
                                <label class="form-label-custom">SKU <span style="color:var(--clr-danger)">*</span></label>
                                <input type="text" name="sku" id="editSku" class="form-control-custom {{ $errors->has('sku') && old('_edit_id') ? 'is-invalid' : '' }}"
                                    value="{{ old('_edit_id') ? old('sku') : '' }}" style="text-transform:uppercase;" required>
                                @if(old('_edit_id')) @error('sku')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-7">
                                <label class="form-label-custom">Kategori <span style="color:var(--clr-danger)">*</span></label>
                                <select name="category_id" id="editCategory" class="form-select-custom" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('_edit_id') && old('category_id') == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label-custom">Tipe Aset / Produk <span style="color:var(--clr-danger)">*</span></label>
                                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;">
                                    @foreach(['physical' => ['Hardware Asset','bi-cpu'], 'digital' => ['Lisensi / SaaS','bi-cloud-check'], 'service' => ['Layanan IT','bi-headset']] as $val => $item)
                                        <label style="border:1.5px solid var(--clr-border);border-radius:10px;padding:10px;cursor:pointer;transition:all 0.15s;" id="edit-type-label-{{ $val }}">
                                            <input type="radio" name="product_type" id="edit-type-radio-{{ $val }}" value="{{ $val }}" style="display:none;" onchange="setEditType(this)">
                                            <div style="font-size:1.1rem;color:var(--clr-muted);"><i class="bi {{ $item[1] }}"></i></div>
                                            <div style="font-size:0.75rem;font-weight:600;margin-top:3px;">{{ $item[0] }}</div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Finansial & Inventaris -->
                    <div class="card-glass p-3 mb-3">
                        <div class="form-section-title"><i class="bi bi-cash-stack"></i> Harga &amp; Pengelolaan Stok</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-custom">Harga Jual <span style="color:var(--clr-danger)">*</span></label>
                                <div class="input-prefix">
                                    <span class="prefix-label">Rp</span>
                                    <input type="number" min="0" step="0.01" name="price" id="editPrice" value="{{ old('_edit_id') ? old('price') : '' }}" required>
                                </div>
                                @if(old('_edit_id')) @error('price')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-custom">Harga Modal / COGS (Opsional)</label>
                                <div class="input-prefix">
                                    <span class="prefix-label">Rp</span>
                                    <input type="number" min="0" step="0.01" name="cost_price" id="editCostPrice" value="{{ old('_edit_id') ? old('cost_price') : '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Stok Tersedia <span style="color:var(--clr-danger)">*</span></label>
                                <input type="number" min="0" name="stock" id="editStock" class="form-control-custom"
                                    value="{{ old('_edit_id') ? old('stock') : '' }}" required>
                                @if(old('_edit_id')) @error('stock')<div class="invalid-msg">{{ $message }}</div>@enderror @endif
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Ambang Alert Stok <span style="color:var(--clr-danger)">*</span></label>
                                <input type="number" min="1" name="min_stock_alert" id="editMinStock" class="form-control-custom"
                                    value="{{ old('_edit_id') ? old('min_stock_alert') : '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label-custom">Status <span style="color:var(--clr-danger)">*</span></label>
                                <select name="status" id="editStatus" class="form-select-custom">
                                    <option value="active">Aktif</option>
                                    <option value="inactive">Non-aktif</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Deskripsi -->
                    <div class="card-glass p-3">
                        <div class="form-section-title"><i class="bi bi-text-paragraph"></i> Deskripsi &amp; Spesifikasi</div>
                        <textarea name="description" id="editDescription" rows="3" class="form-control-custom">{{ old('_edit_id') ? old('description') : '' }}</textarea>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer" style="background:#fff;border-top:1px solid var(--clr-border);padding:14px 24px;">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Batal
                    </button>
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-check2-circle"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL 3: VIEW PRODUCT DETAIL (DETAIL & AUDIT LOGS)
======================================================== -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-labelledby="viewProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:18px;border:1px solid var(--clr-border);overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,0.15);">
            <!-- Modal Header -->
            <div style="background:#1c1008;padding:22px 26px;border-bottom:1px solid rgba(224,123,0,0.25);display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span id="viewSkuChip" class="sku-chip" style="background:rgba(224,123,0,0.2);color:#f59e0b;border-color:rgba(224,123,0,0.3);font-size:0.75rem;">
                            SKU-—
                        </span>
                        <span id="viewStatusBadge" class="badge-status badge-active">Aktif</span>
                        <span id="viewTypeChip" class="type-chip">Fisik</span>
                    </div>
                    <h4 class="m-0" id="viewProductName" style="color:#fdf0dc;font-weight:800;font-size:1.25rem;font-family:var(--font-display);">
                        Nama Produk
                    </h4>
                    <div style="color:rgba(255,255,255,0.45);font-size:0.78rem;margin-top:2px;">
                        Kategori: <span id="viewCategoryName" style="color:#fdd996;font-weight:600;">—</span>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-4" style="max-height:calc(85vh - 140px);overflow-y:auto;background:var(--clr-bg);">
                
                <!-- Stock Status Indicator Card -->
                <div id="viewStockAlertBox" class="p-3 mb-3 rounded-3" style="background:var(--clr-surface);border:1px solid var(--clr-border);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <span id="viewStockIcon" style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">
                                <i class="bi bi-box-seam"></i>
                            </span>
                            <div>
                                <div id="viewStockTitle" style="font-weight:700;font-size:0.9rem;color:var(--clr-text);">Status Stok</div>
                                <div id="viewStockSub" style="font-size:0.75rem;color:var(--clr-muted);">0 unit tersedia</div>
                            </div>
                        </div>
                        <div class="text-end">
                            <div id="viewStockNumber" style="font-size:1.4rem;font-weight:800;line-height:1;">0</div>
                            <div style="font-size:0.68rem;color:var(--clr-muted);font-weight:600;">unit fisik</div>
                        </div>
                    </div>
                </div>

                <!-- Spec Grid -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="card-glass p-3 h-100">
                            <div class="form-section-title mb-2"><i class="bi bi-currency-dollar"></i> Parameter Harga</div>
                            <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:0.83rem;">
                                <span class="text-muted">Harga Jual Konsumen:</span>
                                <strong id="viewPrice" style="color:var(--clr-primary);font-size:0.95rem;">Rp —</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:0.83rem;">
                                <span class="text-muted">Harga Modal (COGS):</span>
                                <span id="viewCostPrice" style="font-weight:600;">Rp —</span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="font-size:0.83rem;">
                                <span class="text-muted">Estimasi Margin:</span>
                                <span id="viewMargin" style="font-weight:700;color:var(--clr-success);">—</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-glass p-3 h-100">
                            <div class="form-section-title mb-2"><i class="bi bi-clock-history"></i> Rekaman Meta &amp; Alert</div>
                            <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:0.83rem;">
                                <span class="text-muted">Ambang Minimum Alert:</span>
                                <strong id="viewMinStockAlert">≤ 0 unit</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:0.83rem;">
                                <span class="text-muted">Tanggal Ditambahkan:</span>
                                <span id="viewCreatedAt">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2" style="font-size:0.83rem;">
                                <span class="text-muted">Terakhir Diperbarui:</span>
                                <span id="viewUpdatedAt">—</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="card-glass p-3 mb-3">
                    <div class="form-section-title mb-2"><i class="bi bi-card-text"></i> Deskripsi &amp; Catatan Produk</div>
                    <p id="viewDescription" style="font-size:0.82rem;color:var(--clr-muted);line-height:1.6;margin:0;">
                        —
                    </p>
                </div>

                <!-- Audit Trail Logs for this product -->
                <div class="card-glass p-3">
                    <div class="form-section-title mb-2 d-flex justify-content-between align-items-center">
                        <div><i class="bi bi-journal-text"></i> Riwayat Audit Log Produk Ini</div>
                        <span id="viewLogsCountBadge" class="badge" style="background:var(--clr-bg);color:var(--clr-muted);font-size:0.68rem;border:1px solid var(--clr-border);">Memuat...</span>
                    </div>
                    <div id="viewLogsContainer" style="max-height:220px;overflow-y:auto;">
                        <div style="text-align:center;padding:24px;color:var(--clr-muted);">
                            <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                            <div style="font-size:0.75rem;margin-top:6px;">Mengambil data audit trail...</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer" style="background:#fff;border-top:1px solid var(--clr-border);padding:14px 24px;display:flex;justify-content:space-between;">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">
                    Tutup
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-primary-custom" id="viewBtnEditTrigger" onclick="switchToEditFromView()">
                        <i class="bi bi-pencil-square"></i> Edit Produk Ini
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================
     MODAL 4: DELETE CONFIRMATION
======================================================== -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border-radius:16px;border:1px solid var(--clr-border);overflow:hidden;box-shadow:0 12px 36px rgba(0,0,0,0.12);">
            <div style="background:var(--clr-danger);padding:18px 22px;display:flex;align-items:center;gap:10px;">
                <i class="bi bi-trash-fill text-white fs-5"></i>
                <span style="color:white;font-weight:700;font-size:0.95rem;">Konfirmasi Hapus Produk</span>
            </div>
            <div class="modal-body p-4">
                <p style="margin:0;font-size:0.87rem;color:var(--clr-muted);">
                    Apakah Anda yakin ingin menghapus produk <strong id="deleteProductName" class="text-dark"></strong>?
                </p>
                <div style="background:var(--clr-primary-light);border-radius:10px;padding:10px 14px;margin-top:12px;font-size:0.78rem;color:var(--clr-primary-dark);">
                    <i class="bi bi-info-circle me-1"></i> Data akan di-soft delete — riwayat audit trail tetap tersimpan untuk forensic logging.
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid var(--clr-border);padding:14px 22px;gap:8px;">
                <button type="button" class="btn-ghost" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:var(--clr-danger);color:#fff;border:none;border-radius:10px;padding:9px 20px;font-weight:600;font-size:0.85rem;cursor:pointer;">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentViewedProduct = null;

    // Open Create Modal
    function openCreateModal() {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('createProductModal'));
        modal.show();
    }
    window.openCreateModal = openCreateModal;

    // Helper: Generate Random SKU (Enterprise IT Context)
    function genSku(targetInputId) {
        const prefixes = ['HW', 'NET', 'SW', 'SRV', 'OFF', 'SEC', 'CLW'];
        const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
        const num = String(Math.floor(100 + Math.random() * 900)).padStart(3, '0');
        document.getElementById(targetInputId).value = prefix + '-' + num;
    }

    // Radio style toggles for Create
    function setCreateType(radio) {
        ['physical', 'digital', 'service'].forEach(val => {
            const lbl = document.getElementById('create-type-label-' + val);
            if (!lbl) return;
            const isMatch = radio.value === val;
            lbl.style.borderColor = isMatch ? 'var(--clr-primary)' : 'var(--clr-border)';
            lbl.querySelector('div').style.color = isMatch ? 'var(--clr-primary)' : 'var(--clr-muted)';
        });
    }

    // Radio style toggles for Edit
    function setEditType(radioOrVal) {
        const selectedVal = typeof radioOrVal === 'string' ? radioOrVal : radioOrVal.value;
        ['physical', 'digital', 'service'].forEach(val => {
            const lbl = document.getElementById('edit-type-label-' + val);
            const radio = document.getElementById('edit-type-radio-' + val);
            if (!lbl || !radio) return;
            const isMatch = selectedVal === val;
            radio.checked = isMatch;
            lbl.style.borderColor = isMatch ? 'var(--clr-primary)' : 'var(--clr-border)';
            lbl.querySelector('div').style.color = isMatch ? 'var(--clr-primary)' : 'var(--clr-muted)';
        });
    }

    // Open Edit Modal with product data
    function openEditModal(buttonOrProduct) {
        let product;
        if (buttonOrProduct instanceof HTMLElement) {
            product = JSON.parse(buttonOrProduct.getAttribute('data-product'));
        } else {
            product = buttonOrProduct;
        }

        document.getElementById('editBadgeId').innerText = 'ID #' + product.id;
        document.getElementById('editHeaderSubtitle').innerText = product.name;
        document.getElementById('editProductForm').action = '/products/' + product.id;
        document.getElementById('editHiddenId').value = product.id;

        document.getElementById('editName').value = product.name;
        document.getElementById('editSku').value = product.sku;
        document.getElementById('editCategory').value = product.category_id;
        document.getElementById('editPrice').value = product.price;
        document.getElementById('editCostPrice').value = product.cost_price || '';
        document.getElementById('editStock').value = product.stock;
        document.getElementById('editMinStock').value = product.min_stock_alert;
        document.getElementById('editStatus').value = product.status;
        document.getElementById('editDescription').value = product.description || '';

        setEditType(product.product_type || 'physical');

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editProductModal'));
        modal.show();
    }

    // Open View Modal with rich data
    function openViewModal(buttonOrProduct) {
        let product;
        if (buttonOrProduct instanceof HTMLElement) {
            product = JSON.parse(buttonOrProduct.getAttribute('data-product'));
        } else {
            product = buttonOrProduct;
        }

        currentViewedProduct = product;

        // Header info
        document.getElementById('viewSkuChip').innerText = product.sku;
        document.getElementById('viewProductName').innerText = product.name;
        document.getElementById('viewCategoryName').innerText = product.category_name || (product.category ? product.category.name : '—');
        document.getElementById('viewTypeChip').innerText = (product.product_type || 'physical').toUpperCase();

        // Status badge
        const statusBadge = document.getElementById('viewStatusBadge');
        if (product.status === 'active') {
            statusBadge.className = 'badge-status badge-active';
            statusBadge.innerText = 'Aktif';
        } else if (product.status === 'inactive') {
            statusBadge.className = 'badge-status badge-inactive';
            statusBadge.innerText = 'Non-aktif';
        } else {
            statusBadge.className = 'badge-status badge-draft';
            statusBadge.innerText = 'Draft';
        }

        // Stock Box
        const stockAlertBox = document.getElementById('viewStockAlertBox');
        const stockIcon = document.getElementById('viewStockIcon');
        const stockTitle = document.getElementById('viewStockTitle');
        const stockSub = document.getElementById('viewStockSub');
        const stockNumber = document.getElementById('viewStockNumber');

        stockNumber.innerText = product.stock;

        if (product.stock <= 0) {
            stockAlertBox.style.background = 'var(--clr-danger-light)';
            stockAlertBox.style.borderColor = '#fca5a5';
            stockIcon.style.background = 'var(--clr-danger)';
            stockIcon.style.color = '#fff';
            stockIcon.innerHTML = '<i class="bi bi-x-circle-fill"></i>';
            stockTitle.innerText = 'Stok Habis (Out of Stock)';
            stockTitle.style.color = '#991b1b';
            stockSub.innerText = '0 unit siap kirim · Harap segera restock';
            stockNumber.style.color = 'var(--clr-danger)';
        } else if (product.stock <= product.min_stock_alert) {
            stockAlertBox.style.background = 'var(--clr-warning-light)';
            stockAlertBox.style.borderColor = '#fde68a';
            stockIcon.style.background = 'var(--clr-accent)';
            stockIcon.style.color = '#fff';
            stockIcon.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
            stockTitle.innerText = 'Stok Menipis (Di Bawah Ambang Alert)';
            stockTitle.style.color = '#92400e';
            stockSub.innerText = `${product.stock} unit sisa · Batas minimum alert: ${product.min_stock_alert} unit`;
            stockNumber.style.color = '#b45309';
        } else {
            stockAlertBox.style.background = 'var(--clr-success-light)';
            stockAlertBox.style.borderColor = '#a7f3d0';
            stockIcon.style.background = 'var(--clr-success)';
            stockIcon.style.color = '#fff';
            stockIcon.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
            stockTitle.innerText = 'Stok Aman & Tersedia';
            stockTitle.style.color = '#065f46';
            stockSub.innerText = `${product.stock} unit siap jual · Di atas batas minimum (${product.min_stock_alert})`;
            stockNumber.style.color = 'var(--clr-success)';
        }

        // Pricing & Margin
        const formattedPrice = 'Rp ' + Number(product.price).toLocaleString('id-ID');
        document.getElementById('viewPrice').innerText = formattedPrice;

        if (product.cost_price && Number(product.cost_price) > 0) {
            const formattedCost = 'Rp ' + Number(product.cost_price).toLocaleString('id-ID');
            document.getElementById('viewCostPrice').innerText = formattedCost;
            const marginRp = Number(product.price) - Number(product.cost_price);
            const marginPct = ((marginRp / Number(product.price)) * 100).toFixed(1);
            document.getElementById('viewMargin').innerHTML = `+Rp ${marginRp.toLocaleString('id-ID')} (${marginPct}%)`;
        } else {
            document.getElementById('viewCostPrice').innerText = '—';
            document.getElementById('viewMargin').innerText = '—';
        }

        document.getElementById('viewMinStockAlert').innerText = `≤ ${product.min_stock_alert} unit`;
        document.getElementById('viewCreatedAt').innerText = product.created_at_fmt || '—';
        document.getElementById('viewUpdatedAt').innerText = product.updated_at_fmt || '—';
        document.getElementById('viewDescription').innerText = product.description || 'Tidak ada deskripsi rinci.';

        // Load Activity Logs asynchronously for this product
        loadProductLogs(product.id);

        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('viewProductModal'));
        modal.show();
    }

    // Switch from View Modal to Edit Modal
    function switchToEditFromView() {
        if (!currentViewedProduct) return;
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewProductModal'));
        if (viewModal) viewModal.hide();
        setTimeout(() => {
            openEditModal(currentViewedProduct);
        }, 250);
    }

    // Load audit logs via REST API
    function loadProductLogs(productId) {
        const container = document.getElementById('viewLogsContainer');
        const badge = document.getElementById('viewLogsCountBadge');

        container.innerHTML = `
            <div style="text-align:center;padding:24px;color:var(--clr-muted);">
                <div class="spinner-border spinner-border-sm text-warning" role="status"></div>
                <div style="font-size:0.75rem;margin-top:6px;">Mengambil data audit trail...</div>
            </div>`;
        badge.innerText = 'Memuat...';

        fetch(`/api/v1/products/${productId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    const logs = data.data.activity_logs || [];
                    badge.innerText = `${logs.length} Log Terakhir`;

                    if (logs.length === 0) {
                        container.innerHTML = `
                            <div style="text-align:center;padding:20px;color:var(--clr-muted);font-size:0.8rem;">
                                <i class="bi bi-journal-x fs-4 opacity-50 d-block mb-1"></i>
                                Belum ada riwayat aktivitas untuk produk ini.
                            </div>`;
                        return;
                    }

                    let html = '<div class="d-flex flex-column gap-2">';
                    logs.forEach(log => {
                        const actionStyles = {
                            'CREATE': { bg: 'var(--clr-success-light)', color: 'var(--clr-success)' },
                            'UPDATE': { bg: 'var(--clr-primary-light)', color: 'var(--clr-primary)' },
                            'DELETE': { bg: 'var(--clr-danger-light)', color: 'var(--clr-danger)' },
                            'LOW_STOCK_ALERT': { bg: 'var(--clr-warning-light)', color: '#92400e' },
                        };
                        const style = actionStyles[log.action] || { bg: '#f1f5f9', color: '#475569' };
                        const timeFmt = new Date(log.created_at).toLocaleString('id-ID', {
                            day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit'
                        });

                        html += `
                            <div style="background:#fff;border:1px solid var(--clr-border);border-radius:8px;padding:9px 12px;font-size:0.78rem;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span style="background:${style.bg};color:${style.color};font-size:0.65rem;font-weight:700;padding:2px 7px;border-radius:4px;text-transform:uppercase;">
                                        ${log.action}
                                    </span>
                                    <span style="font-size:0.7rem;color:var(--clr-muted);font-family:'Courier New',monospace;">
                                        ${timeFmt} · ${log.execution_time_ms}ms
                                    </span>
                                </div>
                                <div style="color:var(--clr-text);font-weight:500;">${log.description}</div>
                                <div style="font-size:0.68rem;color:var(--clr-muted);margin-top:2px;">
                                    Oleh: ${log.user_identifier || 'Sistem'} · IP: ${log.ip_address || '127.0.0.1'}
                                </div>
                            </div>`;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `<div style="text-align:center;padding:16px;color:var(--clr-danger);font-size:0.75rem;">Gagal memuat log audit.</div>`;
                }
            })
            .catch(() => {
                container.innerHTML = `<div style="text-align:center;padding:16px;color:var(--clr-muted);font-size:0.75rem;">Data audit log tidak dapat diambil.</div>`;
                badge.innerText = '0 Log';
            });
    }

    // Helper for table row click
    function openViewModalFromRow(productId) {
        fetch(`/api/v1/products/${productId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    const p = data.data;
                    openViewModal({
                        id: p.id,
                        name: p.name,
                        sku: p.sku,
                        category_id: p.category_id,
                        category_name: p.category ? p.category.name : '—',
                        product_type: p.product_type,
                        price: p.price,
                        cost_price: p.cost_price,
                        stock: p.stock,
                        min_stock_alert: p.min_stock_alert,
                        status: p.status,
                        description: p.description,
                        created_at_fmt: p.created_at ? new Date(p.created_at).toLocaleString('id-ID') : '—',
                        updated_at_fmt: p.updated_at ? new Date(p.updated_at).toLocaleString('id-ID') : '—',
                    });
                }
            });
    }

    // Delete Confirmation
    function confirmDelete(id, name) {
        const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal'));
        document.getElementById('deleteProductName').innerText = name;
        document.getElementById('deleteForm').action = '/products/' + id;
        modal.show();
    }

    // Handle URL parameters on page load (?action=create, ?edit=123, ?view=123)
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        
        // Validation Errors Re-open
        @if($errors->any())
            @if(old('_edit_id'))
                const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('editProductModal'));
                editModal.show();
            @else
                const createModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('createProductModal'));
                createModal.show();
            @endif
        @else
            if (params.get('action') === 'create' || window.location.hash === '#create') {
                openCreateModal();
            } else if (params.get('edit')) {
                openViewModalFromRow(params.get('edit'));
            } else if (params.get('view')) {
                openViewModalFromRow(params.get('view'));
            }
        @endif
    });
</script>
@endpush
