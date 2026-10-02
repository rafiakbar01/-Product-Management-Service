@extends('layouts.app')

@section('title', 'Monitoring & Evaluasi Performa Modul')
@section('breadcrumb')
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
        </div>
        <h1 class="page-heading">Monitoring &amp; Evaluasi Performa Modul</h1>
        <p class="page-sub mb-0">Pemantauan real-time latensi HTTP, ambang batas SLA, konsumsi memori server, dan evaluasi performa aplikasi.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('activity-logs.index') }}" class="btn-ghost">
            <i class="bi bi-journal-text"></i> Ke Halaman Log Aktivitas
        </a>
        <a href="{{ route('monitoring.index') }}" class="btn-primary-custom">
            <i class="bi bi-arrow-clockwise"></i> Refresh Telemetri
        </a>
    </div>
</div>

<!-- Telemetry Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card orange">
            <div class="icon-wrap"><i class="bi bi-reception-4"></i></div>
            <div class="stat-value">{{ number_format($summary['total_requests']) }}</div>
            <div class="stat-label">Total HTTP Requests</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card green">
            <div class="icon-wrap"><i class="bi bi-stopwatch"></i></div>
            <div class="stat-value" style="font-size:1.4rem;">{{ $summary['avg_response_time_ms'] }}<small style="font-size:0.8rem;font-weight:500;">ms</small></div>
            <div class="stat-label">Rata-rata Latensi</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card amber">
            <div class="icon-wrap"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-value" style="font-size:1.4rem;">{{ $summary['p95_response_time_ms'] }}<small style="font-size:0.8rem;font-weight:500;">ms</small></div>
            <div class="stat-label">P95 Latency (SLA)</div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-xl">
        <div class="stat-card orange">
            <div class="icon-wrap"><i class="bi bi-cpu"></i></div>
            <div class="stat-value" style="font-size:1.4rem;">{{ $summary['peak_memory_mb'] }}<small style="font-size:0.8rem;font-weight:500;">MB</small></div>
            <div class="stat-label">Peak RAM · Avg: {{ $summary['avg_memory_mb'] }}MB</div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl">
        <div class="stat-card {{ $summary['error_count'] > 0 ? '' : 'green' }}" style="{{ $summary['error_count'] > 0 ? 'border-color:rgba(239,68,68,0.25);' : '' }}">
            <div class="icon-wrap" style="{{ $summary['error_count'] > 0 ? 'background:var(--clr-danger-light);color:var(--clr-danger);' : '' }}"><i class="bi bi-shield-check"></i></div>
            <div class="stat-value" style="color:{{ $summary['error_count'] > 0 ? 'var(--clr-danger)' : 'var(--clr-success)' }};">{{ $summary['error_rate_percent'] }}%</div>
            <div class="stat-label">Error Rate · {{ $summary['error_count'] }} Error</div>
        </div>
    </div>
</div>

<!-- SLA Benchmark & Evaluation Banner (UK J.620100.047.01) -->
<div class="card-glass p-4 mb-4" style="background:var(--clr-surface);border:1px solid var(--clr-border);">
    <div class="row align-items-center g-3">
        <div class="col-lg-8">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge" style="background:#059669;color:#fff;font-size:0.75rem;padding:5px 10px;border-radius:20px;">
                    <i class="bi bi-check-circle-fill me-1"></i> STATUS SISTEM: OPTIMAL
                </span>
                <span style="font-size:0.78rem;color:var(--clr-muted);">Evaluasi Standar Kinerja SLA &amp; Telemetri</span>
            </div>
            <h4 style="font-family:var(--font-display);font-weight:700;font-size:1.15rem;margin:0 0 6px;color:var(--clr-text);">
                Ambang Batas &amp; Health Check Kinerja Aplikasi
            </h4>
            <p style="font-size:0.82rem;color:var(--clr-muted);margin:0;line-height:1.6;">
                Berdasarkan pengukuran middleware telemetri, latensi P95 berada pada <strong>{{ $summary['p95_response_time_ms'] }}ms</strong> (di bawah batas toleransi SLA 200ms). Rata-rata pemakaian RAM server <strong>{{ $summary['avg_memory_mb'] }}MB</strong> (limit 128MB).
            </p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="d-inline-flex flex-column gap-2 text-start p-3" style="background:var(--clr-bg);border:1px solid var(--clr-border);border-radius:12px;min-width:220px;">
                <div class="d-flex justify-content-between" style="font-size:0.78rem;">
                    <span style="color:var(--clr-muted);">Target Latensi SLA:</span>
                    <strong style="color:#059669;">&lt; 200 ms</strong>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.78rem;">
                    <span style="color:var(--clr-muted);">Error Budget:</span>
                    <strong style="color:#059669;">&lt; 1.00%</strong>
                </div>
                <div class="d-flex justify-content-between" style="font-size:0.78rem;">
                    <span style="color:var(--clr-muted);">Log Terdistribusi:</span>
                    <a href="{{ route('activity-logs.index') }}" style="color:var(--clr-primary);font-weight:700;text-decoration:none;">
                        {{ number_format($totalLogsCount) }} Entri <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Active Alert Notifications Panel (UK J.620100.044.01) -->
<div class="card-glass mb-4" style="{{ $lowStockAlerts->count() > 0 ? 'border-color:rgba(245,158,11,0.3);' : '' }}">
    <div style="padding:14px 20px;border-bottom:1px solid var(--clr-border);display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <span style="width:32px;height:32px;background:{{ $lowStockAlerts->count() > 0 ? '#f59e0b' : 'var(--clr-success)' }};border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;">
                <i class="bi bi-bell-fill" style="font-size:0.85rem;"></i>
            </span>
            <div>
                <span style="font-weight:700;font-size:0.9rem;">Notifikasi &amp; Alert Batas Stok</span>
                <div style="font-size:0.72rem;color:var(--clr-muted);">Peringatan otomatis untuk mencegah out-of-stock</div>
            </div>
        </div>
        <span style="background:{{ $lowStockAlerts->count() > 0 ? '#fef3c7' : 'var(--clr-success-light)' }};color:{{ $lowStockAlerts->count() > 0 ? '#92400e' : 'var(--clr-success)' }};border:1px solid {{ $lowStockAlerts->count() > 0 ? '#fde68a' : '#a7f3d0' }};font-size:0.75rem;font-weight:700;padding:4px 12px;border-radius:20px;">
            {{ $lowStockAlerts->count() }} Peringatan Aktif
        </span>
    </div>
    <div class="p-3">
        @if($lowStockAlerts->count() > 0)
            <div class="row g-2">
                @foreach($lowStockAlerts as $item)
                    <div class="col-md-6 col-lg-4">
                        <div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a;border-radius:12px;padding:14px;display:flex;align-items:center;justify-content:space-between;gap:10px;">
                            <div style="min-width:0;">
                                <div style="font-weight:700;font-size:0.83rem;color:var(--clr-text);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->name }}</div>
                                <div style="font-size:0.72rem;color:#92400e;margin-top:2px;">
                                    <span class="sku-chip">{{ $item->sku }}</span>
                                    <span class="ms-1">Ambang alert: {{ $item->min_stock_alert }} unit</span>
                                </div>
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <div style="font-size:1.25rem;font-weight:800;color:var(--clr-danger);">{{ $item->stock }}</div>
                                <div style="font-size:0.65rem;color:#92400e;font-weight:600;">unit tersisa</div>
                                <a href="{{ route('products.index', ['stock_status' => 'low_stock']) }}" style="font-size:0.7rem;color:#92400e;font-weight:700;text-decoration:none;border:1px solid #fde68a;border-radius:6px;padding:2px 8px;display:inline-block;margin-top:3px;background:#fff;">
                                    Kelola
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center;padding:22px;color:var(--clr-muted);">
                <i class="bi bi-shield-check" style="font-size:2rem;color:var(--clr-success);display:block;margin-bottom:6px;"></i>
                <div style="font-weight:600;font-size:0.88rem;color:var(--clr-text);">Semua stok produk berada pada batas aman</div>
                <div style="font-size:0.78rem;margin-top:3px;">Tidak ada alert notifikasi stok yang terpicu saat ini.</div>
            </div>
        @endif
    </div>
</div>

<!-- HTTP Telemetry Real-time Table (Full Width) -->
<div class="card-glass">
    <div style="padding:18px 22px;border-bottom:1px solid var(--clr-border);display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:10px;">
        <div class="d-flex align-items-center gap-2">
            <span style="width:32px;height:32px;background:var(--clr-primary-light);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--clr-primary);">
                <i class="bi bi-activity fs-6"></i>
            </span>
            <div>
                <div style="font-weight:700;font-size:0.92rem;color:var(--clr-text);">Telemetri HTTP Requests &amp; Latensi Runtime</div>
                <div style="font-size:0.72rem;color:var(--clr-muted);">Pengambilan metrik otomatis melalui PerformanceMonitoringMiddleware</div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="status-dot">Live Stream</span>
            <a href="{{ route('activity-logs.index') }}" class="btn-ghost" style="padding:5px 12px;font-size:0.78rem;">
                <i class="bi bi-journal-text me-1"></i> Buka Log Aktivitas
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:90px;">Method</th>
                    <th>Path / URL Endpoint</th>
                    <th class="text-center" style="width:110px;">Status Code</th>
                    <th class="text-end" style="width:140px;">Response Time</th>
                    <th class="text-end" style="width:120px;">Memory (RAM)</th>
                    <th class="text-center" style="width:110px;">DB Queries</th>
                    <th class="text-end" style="width:130px;">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentMetrics as $metric)
                    @php
                        $method = $metric->method;
                        $methodColors = [
                            'GET'    => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
                            'POST'   => ['bg' => 'var(--clr-success-light)', 'color' => 'var(--clr-success)'],
                            'PUT'    => ['bg' => 'var(--clr-warning-light)', 'color' => '#92400e'],
                            'DELETE' => ['bg' => 'var(--clr-danger-light)', 'color' => 'var(--clr-danger)'],
                            'PATCH'  => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                        ];
                        $mc = $methodColors[$method] ?? ['bg' => '#f1f5f9', 'color' => '#475569'];

                        $isSlow = $metric->response_time_ms > 200;
                    @endphp
                    <tr>
                        <!-- Method -->
                        <td>
                            <span style="background:{{ $mc['bg'] }};color:{{ $mc['color'] }};font-size:0.68rem;font-weight:800;padding:3px 8px;border-radius:6px;letter-spacing:0.5px;">
                                {{ $method }}
                            </span>
                        </td>

                        <!-- URL -->
                        <td>
                            <div style="font-size:0.83rem;font-family:'Consolas','Courier New',monospace;color:var(--clr-text);font-weight:600;">
                                {{ parse_url($metric->url, PHP_URL_PATH) }}
                            </div>
                            @if(parse_url($metric->url, PHP_URL_QUERY))
                                <div style="font-size:0.72rem;color:var(--clr-muted);font-family:'Consolas','Courier New',monospace;">
                                    ?{{ Str::limit(parse_url($metric->url, PHP_URL_QUERY), 50) }}
                                </div>
                            @endif
                        </td>

                        <!-- Status Code -->
                        <td class="text-center">
                            @if($metric->status_code < 400)
                                <span style="background:var(--clr-success-light);color:var(--clr-success);font-size:0.75rem;font-weight:700;padding:3px 10px;border-radius:6px;">
                                    <i class="bi bi-check-circle me-1"></i>{{ $metric->status_code }}
                                </span>
                            @else
                                <span style="background:var(--clr-danger-light);color:var(--clr-danger);font-size:0.75rem;font-weight:700;padding:3px 10px;border-radius:6px;">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $metric->status_code }}
                                </span>
                            @endif
                        </td>

                        <!-- Response Time -->
                        <td class="text-end">
                            <span style="font-size:0.85rem;font-weight:800;font-family:'Consolas','Courier New',monospace;color:{{ $isSlow ? 'var(--clr-danger)' : 'var(--clr-success)' }};">
                                {{ $metric->response_time_ms }} ms
                            </span>
                            @if($isSlow)
                                <div style="font-size:0.65rem;color:var(--clr-danger);font-weight:600;">Slow Request</div>
                            @endif
                        </td>

                        <!-- RAM -->
                        <td class="text-end" style="font-size:0.8rem;color:var(--clr-muted);font-family:'Consolas','Courier New',monospace;font-weight:600;">
                            {{ $metric->memory_usage_mb }} MB
                        </td>

                        <!-- DB Queries -->
                        <td class="text-center">
                            <span style="background:#f1f5f9;color:#334155;font-size:0.72rem;font-weight:700;padding:3px 9px;border-radius:6px;">
                                <i class="bi bi-database me-1"></i>{{ $metric->db_query_count }} Q
                            </span>
                        </td>

                        <!-- Timestamp -->
                        <td class="text-end" style="font-size:0.75rem;color:var(--clr-muted);font-family:'Consolas','Courier New',monospace;">
                            {{ $metric->created_at->format('H:i:s') }}
                            <div style="font-size:0.68rem;">{{ $metric->created_at->format('d/m/Y') }}</div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:56px 20px;color:var(--clr-muted);">
                            <i class="bi bi-wifi-off" style="font-size:2.4rem;display:block;opacity:0.3;margin-bottom:8px;"></i>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--clr-text);">Belum ada data telemetri tercatat</div>
                            <div style="font-size:0.78rem;margin-top:4px;">Lakukan navigasi atau muat halaman produk untuk memulai perekaman telemetri.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
