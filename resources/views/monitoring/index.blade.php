@extends('layouts.app')

@section('title', 'Monitoring & Telemetri Performa Modul')

@section('content')
<!-- Header Page Title -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h3 class="fw-bold mb-1">Monitoring & Evaluasi Performa Modul</h3>
        <p class="text-muted mb-0">Pemantauan real-time latensi respon HTTP, konsumsi memori, logging aktivitas, dan sistem peringatan dini (Alerts).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('monitoring.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-clockwise"></i> Refresh Data
        </a>
    </div>
</div>

<!-- Telemetry Summary Cards (UK J.620100.045.01 - Memantau Performa Aplikasi) -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="card card-stat p-3">
            <div class="text-muted small fw-medium">Total Permintaan</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ number_format($summary['total_requests']) }}</div>
            <div class="small text-muted" style="font-size: 0.72rem;">HTTP Requests</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card card-stat p-3">
            <div class="text-muted small fw-medium">Rata-rata Respon</div>
            <div class="fs-4 fw-bold text-primary mt-1">{{ $summary['avg_response_time_ms'] }} <span class="fs-6 fw-normal">ms</span></div>
            <div class="small text-muted" style="font-size: 0.72rem;">Average Latency</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="text-muted small fw-medium">Latensi P95 (95th Percentile)</div>
            <div class="fs-4 fw-bold text-info mt-1">{{ $summary['p95_response_time_ms'] }} <span class="fs-6 fw-normal">ms</span></div>
            <div class="small text-muted" style="font-size: 0.72rem;">Senior-Level SLA Metric</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-stat p-3">
            <div class="text-muted small fw-medium">Puncak Memori (Peak RAM)</div>
            <div class="fs-4 fw-bold text-dark mt-1">{{ $summary['peak_memory_mb'] }} <span class="fs-6 fw-normal">MB</span></div>
            <div class="small text-muted" style="font-size: 0.72rem;">Avg: {{ $summary['avg_memory_mb'] }} MB</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="card card-stat p-3">
            <div class="text-muted small fw-medium">Error Rate</div>
            <div class="fs-4 fw-bold {{ $summary['error_count'] > 0 ? 'text-danger' : 'text-success' }} mt-1">
                {{ $summary['error_rate_percent'] }}%
            </div>
            <div class="small text-muted" style="font-size: 0.72rem;">{{ $summary['error_count'] }} Error detected</div>
        </div>
    </div>
</div>

<!-- Alert Notifications Section (UK J.620100.044.01 - Menerapkan Alert Notification) -->
<div class="card card-stat mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <span class="d-inline-flex p-1 bg-warning text-dark rounded"><i class="bi bi-bell-fill"></i></span>
            <h5 class="fw-bold mb-0">Peringatan Sistem Aktif (Alert Notifications)</h5>
        </div>
        <span class="badge bg-warning text-dark">{{ $lowStockAlerts->count() }} Peringatan Terdeteksi</span>
    </div>
    <div class="card-body p-3">
        @if($lowStockAlerts->count() > 0)
            <div class="row g-2">
                @foreach($lowStockAlerts as $item)
                    <div class="col-md-6">
                        <div class="border border-warning-subtle bg-warning-subtle p-3 rounded-3 d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-bold text-dark">{{ $item->name }}</div>
                                <div class="small text-muted">
                                    SKU: <code>{{ $item->sku }}</code> | Batas Minimum: {{ $item->min_stock_alert }} unit
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger fs-6">{{ $item->stock }} Unit Sisa</span>
                                <div class="mt-1">
                                    <a href="{{ route('products.edit', $item->id) }}" class="btn btn-sm btn-outline-dark py-0" style="font-size: 0.75rem;">Restock</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-3 text-muted">
                <i class="bi bi-shield-check text-success fs-3 d-block mb-1"></i>
                <div class="fw-medium">Semua inventaris dalam batas aman. Tidak ada alert aktif saat ini.</div>
            </div>
        @endif
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Telemetri Permintaan HTTP (Recent Requests) -->
    <div class="col-lg-6">
        <div class="card card-stat h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-stopwatch text-primary fs-5"></i>
                    <h5 class="fw-bold mb-0">Telemetri HTTP Requests</h5>
                </div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Real-Time</span>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th>Method & URL</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Waktu (ms)</th>
                            <th class="text-end">RAM (MB)</th>
                            <th class="text-center">Queries</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentMetrics as $metric)
                            <tr>
                                <td>
                                    <span class="badge {{ $metric->method === 'POST' ? 'bg-success' : ($metric->method === 'PUT' ? 'bg-warning text-dark' : ($metric->method === 'DELETE' ? 'bg-danger' : 'bg-primary')) }} me-1" style="font-size: 0.68rem;">
                                        {{ $metric->method }}
                                    </span>
                                    <span class="small font-monospace text-truncate d-inline-block" style="max-width: 190px;" title="{{ $metric->url }}">
                                        {{ parse_url($metric->url, PHP_URL_PATH) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $metric->status_code < 400 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} border">
                                        {{ $metric->status_code }}
                                    </span>
                                </td>
                                <td class="text-end font-monospace small fw-bold {{ $metric->response_time_ms > 200 ? 'text-warning' : 'text-dark' }}">
                                    {{ $metric->response_time_ms }} ms
                                </td>
                                <td class="text-end font-monospace small text-muted">
                                    {{ $metric->memory_usage_mb }} MB
                                </td>
                                <td class="text-center small">
                                    <span class="badge bg-light text-dark border">{{ $metric->db_query_count }} Q</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted small">
                                    Belum ada data telemetri tercatat. Buka halaman produk untuk menghasilkan data.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Activity & Audit Logs (UK J.620100.046.01 & J.620100.043.01) -->
    <div class="col-lg-6">
        <div class="card card-stat h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-journal-text text-success fs-5"></i>
                    <h5 class="fw-bold mb-0">Log Aktivitas Modul</h5>
                </div>
                <div class="d-flex gap-1">
                    <a href="{{ route('monitoring.index') }}" class="btn btn-sm {{ !$actionFilter ? 'btn-primary' : 'btn-outline-secondary' }} py-0 px-2" style="font-size: 0.75rem;">Semua</a>
                    <a href="{{ route('monitoring.index', ['action' => 'CREATE']) }}" class="btn btn-sm {{ $actionFilter === 'CREATE' ? 'btn-primary' : 'btn-outline-secondary' }} py-0 px-2" style="font-size: 0.75rem;">Create</a>
                    <a href="{{ route('monitoring.index', ['action' => 'SEARCH']) }}" class="btn btn-sm {{ $actionFilter === 'SEARCH' ? 'btn-primary' : 'btn-outline-secondary' }} py-0 px-2" style="font-size: 0.75rem;">Search</a>
                    <a href="{{ route('monitoring.index', ['action' => 'LOW_STOCK_ALERT']) }}" class="btn btn-sm {{ $actionFilter === 'LOW_STOCK_ALERT' ? 'btn-primary' : 'btn-outline-secondary' }} py-0 px-2" style="font-size: 0.75rem;">Alerts</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-custom mb-0">
                    <thead>
                        <tr>
                            <th style="width: 90px;">Action</th>
                            <th>Keterangan Aktivitas</th>
                            <th class="text-end" style="width: 120px;">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    @if($log->action === 'CREATE')
                                        <span class="badge bg-success" style="font-size: 0.7rem;">CREATE</span>
                                    @elseif($log->action === 'UPDATE')
                                        <span class="badge bg-primary" style="font-size: 0.7rem;">UPDATE</span>
                                    @elseif($log->action === 'DELETE')
                                        <span class="badge bg-danger" style="font-size: 0.7rem;">DELETE</span>
                                    @elseif($log->action === 'SEARCH')
                                        <span class="badge bg-info text-dark" style="font-size: 0.7rem;">SEARCH</span>
                                    @elseif($log->action === 'LOW_STOCK_ALERT')
                                        <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">ALERT</span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">{{ $log->action }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="small fw-semibold text-dark">{{ $log->description }}</div>
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        Oleh: {{ $log->user_identifier }} | IP: {{ $log->ip_address ?: '127.0.0.1' }} | Latensi: {{ $log->execution_time_ms }}ms
                                    </div>
                                    @if($log->payload)
                                        <div class="mt-1">
                                            <a class="text-decoration-none small text-primary" data-bs-toggle="collapse" href="#mon-payload-{{ $log->id }}" role="button" style="font-size: 0.72rem;">
                                                <i class="bi bi-code"></i> Payload JSON
                                            </a>
                                            <div class="collapse mt-1" id="mon-payload-{{ $log->id }}">
                                                <pre class="bg-light p-2 rounded small mb-0 border" style="font-size: 0.68rem;">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-end small text-muted font-monospace" style="font-size: 0.75rem;">
                                    {{ $log->created_at->format('H:i:s') }}
                                    <div class="text-muted" style="font-size: 0.68rem;">{{ $log->created_at->format('d/m/Y') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted small">
                                    Tidak ada catatan log pada kategori ini.
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
