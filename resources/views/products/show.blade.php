@extends('layouts.app')

@section('title', $product->name . ' — Detail')
@section('breadcrumb', '<i class="bi bi-grid-1x2-fill"></i> <a href="' . route('products.index') . '" style="text-decoration:none;color:inherit;">Katalog</a> <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i> <span>Detail Produk</span>')

@section('content')
<!-- Hero Header -->
<div style="background:#1c1008;border:1px solid rgba(224,123,0,0.2);border-radius:16px;padding:28px 32px;margin-bottom:24px;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:20px;">
    <div>
        <span class="sku-chip" style="background:rgba(224,123,0,0.2);color:#f59e0b;border-color:rgba(224,123,0,0.3);margin-bottom:10px;display:inline-block;">{{ $product->sku }}</span>
        <h1 style="font-family:var(--font-display);font-size:1.5rem;color:#fdf0dc;margin:0;font-weight:800;">{{ $product->name }}</h1>
        <p style="color:rgba(255,255,255,0.4);font-size:0.82rem;margin:6px 0 0;">{{ $product->category->name }} · {{ ucfirst($product->product_type) }}</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('products.edit', $product->id) }}" class="btn-primary-custom">
            <i class="bi bi-pencil-fill"></i> Edit Produk
        </a>
        <a href="{{ route('products.index') }}" class="btn-ghost" style="border-color:rgba(255,255,255,0.15);color:rgba(255,255,255,0.5);">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left: Detail Specs -->
    <div class="col-lg-5">
        <!-- Stock Status Card -->
        <div class="card-glass p-4 mb-3">
            <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;color:var(--clr-muted);margin-bottom:12px;">Status Ketersediaan</div>
            @if($product->stock <= 0)
                <div style="background:var(--clr-danger-light);border:1px solid #fca5a5;border-radius:10px;padding:16px;text-align:center;">
                    <i class="bi bi-x-circle-fill" style="font-size:1.8rem;color:var(--clr-danger);display:block;margin-bottom:6px;"></i>
                    <div style="font-weight:700;color:#991b1b;font-size:0.95rem;">Stok Habis</div>
                    <div style="font-size:0.78rem;color:#b91c1c;margin-top:2px;">0 unit tersedia</div>
                </div>
            @elseif($product->isLowStock())
                <div style="background:var(--clr-warning-light);border:1px solid #fde68a;border-radius:10px;padding:16px;text-align:center;">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:1.8rem;color:var(--clr-warning);display:block;margin-bottom:6px;"></i>
                    <div style="font-weight:700;color:#92400e;font-size:0.95rem;">Stok Kritis</div>
                    <div style="font-size:0.78rem;color:#b45309;margin-top:2px;">{{ $product->stock }} unit sisa · Batas min: {{ $product->min_stock_alert }}</div>
                </div>
            @else
                <div style="background:var(--clr-success-light);border:1px solid #a7f3d0;border-radius:10px;padding:16px;text-align:center;">
                    <i class="bi bi-check-circle-fill" style="font-size:1.8rem;color:var(--clr-success);display:block;margin-bottom:6px;"></i>
                    <div style="font-weight:700;color:#065f46;font-size:0.95rem;">Stok Tersedia</div>
                    <div style="font-size:0.78rem;color:#047857;margin-top:2px;">{{ $product->stock }} unit siap</div>
                </div>
            @endif
        </div>

        <!-- Spec Table -->
        <div class="card-glass p-4 mb-3">
            <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;color:var(--clr-muted);margin-bottom:14px;">Spesifikasi & Atribut</div>
            @php
                $specs = [
                    ['label' => 'Harga Jual', 'value' => 'Rp ' . number_format($product->price, 0, ',', '.'), 'bold' => true, 'accent' => true],
                    ['label' => 'Harga Modal', 'value' => $product->cost_price ? 'Rp ' . number_format($product->cost_price, 0, ',', '.') : '—'],
                    ['label' => 'Tipe Produk', 'value' => ucfirst($product->product_type)],
                    ['label' => 'Alert Stok', 'value' => '≤ ' . $product->min_stock_alert . ' unit'],
                    ['label' => 'Status Operasional', 'value' => ucfirst($product->status)],
                    ['label' => 'Dibuat', 'value' => $product->created_at->format('d M Y, H:i')],
                    ['label' => 'Diperbarui', 'value' => $product->updated_at->format('d M Y, H:i')],
                ];
            @endphp
            @foreach($specs as $spec)
                <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom:1px solid var(--clr-border);font-size:0.8rem;">
                    <span style="color:var(--clr-muted);">{{ $spec['label'] }}</span>
                    <span style="font-weight:{{ !empty($spec['bold']) ? '700' : '500' }};color:{{ !empty($spec['accent']) ? 'var(--clr-primary)' : 'var(--clr-text)' }};">{{ $spec['value'] }}</span>
                </div>
            @endforeach
        </div>

        <!-- Description -->
        @if($product->description)
            <div class="card-glass p-4">
                <div style="font-size:0.72rem;text-transform:uppercase;letter-spacing:0.8px;font-weight:700;color:var(--clr-muted);margin-bottom:10px;">Deskripsi</div>
                <p style="font-size:0.82rem;color:var(--clr-muted);line-height:1.7;margin:0;">{{ $product->description }}</p>
            </div>
        @endif
    </div>

    <!-- Right: Audit Logs -->
    <div class="col-lg-7">
        <div class="card-glass" style="overflow:hidden;">
            <div style="padding:16px 20px;border-bottom:1px solid var(--clr-border);display:flex;align-items:center;justify-content:space-between;">
                <div style="font-weight:700;font-size:0.9rem;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-clock-history" style="color:var(--clr-primary);"></i>
                    Riwayat Audit Log
                </div>
                <span style="font-size:0.72rem;color:var(--clr-muted);">{{ $logs->total() }} total entri</span>
            </div>
            <div style="max-height:520px;overflow-y:auto;">
                @forelse($logs as $log)
                    <div style="padding:14px 20px;border-bottom:1px solid var(--clr-border);display:flex;gap:12px;align-items:flex-start;">
                        <!-- Action badge column -->
                        <div style="flex-shrink:0;padding-top:2px;">
                            @php
                                $colors = [
                                    'CREATE' => ['bg'=>'#dcfce7','color'=>'#065f46'],
                                    'UPDATE' => ['bg'=>'var(--clr-primary-light)','color'=>'var(--clr-primary)'],
                                    'DELETE' => ['bg'=>'var(--clr-danger-light)','color'=>'var(--clr-danger)'],
                                    'SEARCH' => ['bg'=>'#e0f2fe','color'=>'#0369a1'],
                                    'LOW_STOCK_ALERT' => ['bg'=>'var(--clr-warning-light)','color'=>'#92400e'],
                                    'ERROR' => ['bg'=>'var(--clr-danger-light)','color'=>'var(--clr-danger)'],
                                ];
                                $clr = $colors[$log->action] ?? ['bg'=>'#f1f5f9','color'=>'#64748b'];
                            @endphp
                            <span style="background:{{ $clr['bg'] }};color:{{ $clr['color'] }};font-size:0.65rem;font-weight:700;padding:3px 8px;border-radius:6px;text-transform:uppercase;letter-spacing:0.5px;white-space:nowrap;">
                                {{ $log->action }}
                            </span>
                        </div>
                        <!-- Content -->
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:0.8rem;font-weight:500;color:var(--clr-text);">{{ $log->description }}</div>
                            <div style="font-size:0.72rem;color:var(--clr-muted);margin-top:3px;display:flex;flex-wrap:wrap;gap:8px;">
                                <span><i class="bi bi-person me-1"></i>{{ $log->user_identifier }}</span>
                                <span><i class="bi bi-stopwatch me-1"></i>{{ $log->execution_time_ms }} ms</span>
                                <span><i class="bi bi-clock me-1"></i>{{ $log->created_at->format('d/m H:i:s') }}</span>
                            </div>
                            @if($log->payload)
                                <a href="#" onclick="this.nextElementSibling.style.display=(this.nextElementSibling.style.display==='none'?'block':'none');return false;"
                                    style="font-size:0.7rem;color:var(--clr-primary);font-weight:600;text-decoration:none;display:inline-block;margin-top:4px;">
                                    <i class="bi bi-code-slash"></i> Payload JSON
                                </a>
                                <pre style="display:none;background:#fdf9f2;border:1px solid var(--clr-border);border-radius:8px;padding:10px;font-size:0.68rem;margin-top:6px;overflow-x:auto;line-height:1.4;">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;padding:40px;color:var(--clr-muted);">
                        <i class="bi bi-journal-x" style="font-size:1.8rem;display:block;margin-bottom:6px;opacity:0.3;"></i>
                        Belum ada catatan aktivitas
                    </div>
                @endforelse
            </div>
            @if($logs->hasPages())
                <div style="padding:12px 20px;border-top:1px solid var(--clr-border);display:flex;justify-content:flex-end;">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
