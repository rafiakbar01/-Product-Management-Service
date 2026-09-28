@extends('layouts.app')

@section('title', 'Detail Produk: ' . $product->name)

@section('content')
<!-- Header Page Navigation -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <a href="{{ route('products.index') }}" class="text-decoration-none text-muted small fw-medium mb-1 d-inline-block">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Produk
        </a>
        <h3 class="fw-bold mb-0">{{ $product->name }}</h3>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-pencil-square"></i> Edit Data
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Product Specifications -->
    <div class="col-lg-5">
        <div class="card card-stat mb-4">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Spesifikasi & Detail Entitas</h5>
                <code class="bg-primary-subtle text-primary px-2 py-1 rounded fw-bold">{{ $product->sku }}</code>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <span class="text-muted small d-block">Status Ketersediaan:</span>
                    @if($product->stock <= 0)
                        <span class="badge bg-danger fs-6 px-3 py-1">Stok Habis (0)</span>
                    @elseif($product->isLowStock())
                        <span class="badge bg-warning text-dark fs-6 px-3 py-1">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Stok Kritis ({{ $product->stock }} unit)
                        </span>
                    @else
                        <span class="badge bg-success fs-6 px-3 py-1">Tersedia ({{ $product->stock }} unit)</span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td class="text-muted" style="width: 140px;">Kategori:</td>
                                <td class="fw-semibold">{{ $product->category->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tipe Produk:</td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ ucfirst($product->product_type) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Jual:</td>
                                <td class="fw-bold fs-5 text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Harga Modal (COGS):</td>
                                <td class="text-dark">
                                    {{ $product->cost_price ? 'Rp ' . number_format($product->cost_price, 0, ',', '.') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Batas Alert Stok:</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border">
                                        &le; {{ $product->min_stock_alert }} unit
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status Operasional:</td>
                                <td>
                                    <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dibuat Pada:</td>
                                <td class="small">{{ $product->created_at->translatedFormat('d F Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Terakhir Diperbarui:</td>
                                <td class="small">{{ $product->updated_at->translatedFormat('d F Y, H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-bold mb-2">Deskripsi Produk:</h6>
                    <p class="text-secondary small mb-0 lh-base">
                        {{ $product->description ?: 'Tidak ada deskripsi yang dicantumkan untuk produk ini.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Audit Activity Logs (Observability & Logging) -->
    <div class="col-lg-7">
        <div class="card card-stat">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history text-primary fs-5"></i>
                    <h5 class="fw-bold mb-0">Riwayat Audit & Activity Logs</h5>
                </div>
                <span class="badge bg-light text-dark border">Observabilitas Modul</span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Aksi</th>
                            <th>Deskripsi Aktivitas</th>
                            <th style="width: 110px;">Durasi</th>
                            <th style="width: 140px;">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    @if($log->action === 'CREATE')
                                        <span class="badge bg-success">CREATE</span>
                                    @elseif($log->action === 'UPDATE')
                                        <span class="badge bg-primary">UPDATE</span>
                                    @elseif($log->action === 'DELETE')
                                        <span class="badge bg-danger">DELETE</span>
                                    @elseif($log->action === 'LOW_STOCK_ALERT')
                                        <span class="badge bg-warning text-dark">ALERT</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $log->action }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $log->description }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        User: {{ $log->user_identifier }} | IP: {{ $log->ip_address ?: '127.0.0.1' }}
                                    </div>
                                    @if($log->payload)
                                        <div class="mt-1">
                                            <a class="text-decoration-none small text-primary" data-bs-toggle="collapse" href="#payload-{{ $log->id }}" role="button">
                                                <i class="bi bi-code"></i> Lihat Payload
                                            </a>
                                            <div class="collapse mt-1" id="payload-{{ $log->id }}">
                                                <pre class="bg-light p-2 rounded small mb-0 border" style="font-size: 0.7rem;">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $log->execution_time_ms }} ms
                                    </span>
                                </td>
                                <td class="small text-muted">
                                    {{ $log->created_at->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">
                                    Belum ada catatan aktivitas untuk produk ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-white py-2 border-top d-flex justify-content-end">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
