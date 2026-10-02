@extends('layouts.app')

@section('title', 'Log Aktivitas & Audit Trail')
@section('breadcrumb')
    <i class="bi bi-journal-text" style="color:var(--clr-primary)"></i>
    <span>Observabilitas</span>
    <i class="bi bi-chevron-right mx-1" style="font-size:0.7rem;"></i>
    <span style="color:var(--clr-text);font-weight:600;">Log Aktivitas & Audit Trail</span>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge" style="background:var(--clr-primary-light);color:var(--clr-primary);font-size:0.72rem;font-weight:700;padding:4px 8px;border-radius:6px;">
                UK J.620100.043.01 &amp; .046.01
            </span>
            <span style="font-size:0.78rem;color:var(--clr-muted);">Audit Trail &amp; Forensic Logging</span>
        </div>
        <h1 class="page-heading">Log Aktivitas &amp; Riwayat Audit</h1>
        <p class="page-sub mb-0">Rekaman kronologis lengkap setiap aksi modul: mutasi inventaris, pencarian, dan peringatan sistem.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('monitoring.index') }}" class="btn-ghost">
            <i class="bi bi-speedometer2"></i> Monitoring Performa
        </a>
        <a href="{{ route('activity-logs.index') }}" class="btn-ghost">
            <i class="bi bi-arrow-clockwise"></i> Refresh Log
        </a>
    </div>
</div>

<!-- Log Stats Breakdown Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card orange">
            <div class="icon-wrap"><i class="bi bi-journal-check"></i></div>
            <div class="stat-value">{{ number_format($counts['total']) }}</div>
            <div class="stat-label">Total Entri Log</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card green">
            <div class="icon-wrap"><i class="bi bi-plus-circle"></i></div>
            <div class="stat-value">{{ number_format($counts['create']) }}</div>
            <div class="stat-label">Produk Dibuat (CREATE)</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card gold">
            <div class="icon-wrap"><i class="bi bi-pencil-square"></i></div>
            <div class="stat-value">{{ number_format($counts['update']) }}</div>
            <div class="stat-label">Perubahan (UPDATE)</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card" style="border-color:rgba(220,38,38,0.2);">
            <div class="icon-wrap" style="background:var(--clr-danger-light);color:var(--clr-danger);"><i class="bi bi-trash"></i></div>
            <div class="stat-value" style="color:var(--clr-danger);">{{ number_format($counts['delete']) }}</div>
            <div class="stat-label">Dihapus (DELETE)</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl">
        <div class="stat-card amber">
            <div class="icon-wrap"><i class="bi bi-bell"></i></div>
            <div class="stat-value" style="color:var(--clr-accent);">{{ number_format($counts['alerts']) }}</div>
            <div class="stat-label">Alerts Stok Dipicu</div>
        </div>
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="card-glass mb-4 p-4">
    <form method="GET" action="{{ route('activity-logs.index') }}">
        <div class="row g-3 align-items-center">
            <div class="col-lg-5">
                <label class="form-label-custom">Cari Riwayat Log</label>
                <div class="search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" class="form-control-custom search-input" placeholder="Cari deskripsi, SKU, nama user, atau alamat IP..." value="{{ request('q') }}">
                </div>
            </div>
            <div class="col-lg-7">
                <label class="form-label-custom d-block">Filter Tipe Aksi</label>
                <div class="d-flex flex-wrap gap-1 align-items-center">
                    @php
                        $filterOptions = [
                            '' => ['Semua', $counts['total']],
                            'CREATE' => ['Create', $counts['create']],
                            'UPDATE' => ['Update', $counts['update']],
                            'DELETE' => ['Delete', $counts['delete']],
                            'SEARCH' => ['Search', $counts['search']],
                            'LOW_STOCK_ALERT' => ['Alerts', $counts['alerts']],
                            'ERROR' => ['Error', $counts['error']],
                        ];
                    @endphp
                    @foreach($filterOptions as $act => $info)
                        @php
                            $isSelected = request('action') === ($act ?: null);
                            $urlParams = array_filter(array_merge(request()->except(['page', 'action']), $act ? ['action' => $act] : []));
                        @endphp
                        <a href="{{ route('activity-logs.index', $urlParams) }}"
                            class="text-decoration-none"
                            style="font-size:0.75rem;font-weight:600;padding:6px 12px;border-radius:20px;transition:all 0.15s;
                            {{ $isSelected 
                                ? 'background:var(--clr-primary);color:#fff;border:1px solid var(--clr-primary);box-shadow:0 2px 8px rgba(2,132,199,0.3);' 
                                : 'background:var(--clr-surface);color:var(--clr-muted);border:1px solid var(--clr-border);' }}">
                            {{ $info[0] }} <span style="opacity:0.75;font-size:0.7rem;margin-left:2px;">({{ $info[1] }})</span>
                        </a>
                    @endforeach
                    <button type="submit" class="btn-primary-custom ms-auto" style="padding:6px 14px;font-size:0.78rem;">
                        <i class="bi bi-funnel"></i> Terapkan
                    </button>
                    @if(request()->hasAny(['q', 'action']))
                        <a href="{{ route('activity-logs.index') }}" class="btn-ghost" title="Reset Filter" style="padding:6px 10px;font-size:0.78rem;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Main Activity Log Table -->
<div class="card-glass">
    <div style="padding:18px 22px;border-bottom:1px solid var(--clr-border);display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:10px;">
        <div class="d-flex align-items-center gap-2">
            <span style="width:32px;height:32px;background:var(--clr-primary-light);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--clr-primary);">
                <i class="bi bi-clock-history fs-6"></i>
            </span>
            <div>
                <div style="font-weight:700;font-size:0.92rem;color:var(--clr-text);">Entri Log Audit Sistem</div>
                <div style="font-size:0.72rem;color:var(--clr-muted);">Menampilkan aktivitas {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} dari {{ $logs->total() }} total rekaman</div>
            </div>
        </div>
        <div style="font-size:0.75rem;color:var(--clr-muted);">
            <i class="bi bi-shield-check text-success me-1"></i> Immutability Guaranteed
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:110px;">Tipe Aksi</th>
                    <th style="width:200px;">Produk Terkait</th>
                    <th>Deskripsi &amp; Aktivitas</th>
                    <th style="width:170px;">Pengguna / IP</th>
                    <th class="text-end" style="width:140px;">Waktu &amp; Durasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    @php
                        $actionConfigs = [
                            'CREATE' => ['bg' => 'var(--clr-success-light)', 'color' => 'var(--clr-success)', 'icon' => 'bi-plus-circle-fill', 'label' => 'CREATE'],
                            'UPDATE' => ['bg' => 'var(--clr-primary-light)', 'color' => 'var(--clr-primary)', 'icon' => 'bi-pencil-fill', 'label' => 'UPDATE'],
                            'DELETE' => ['bg' => 'var(--clr-danger-light)', 'color' => 'var(--clr-danger)', 'icon' => 'bi-trash-fill', 'label' => 'DELETE'],
                            'SEARCH' => ['bg' => '#e0f2fe', 'color' => '#0284c7', 'icon' => 'bi-search', 'label' => 'SEARCH'],
                            'LOW_STOCK_ALERT' => ['bg' => 'var(--clr-warning-light)', 'color' => '#92400e', 'icon' => 'bi-exclamation-triangle-fill', 'label' => 'ALERT'],
                            'ERROR' => ['bg' => 'var(--clr-danger-light)', 'color' => 'var(--clr-danger)', 'icon' => 'bi-x-octagon-fill', 'label' => 'ERROR'],
                        ];
                        $cfg = $actionConfigs[$log->action] ?? ['bg' => '#f1f5f9', 'color' => '#475569', 'icon' => 'bi-dot', 'label' => $log->action];
                    @endphp
                    <tr>
                        <!-- Action Badge -->
                        <td>
                            <span style="background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};font-size:0.7rem;font-weight:700;padding:4px 9px;border-radius:6px;display:inline-flex;align-items:center;gap:4px;letter-spacing:0.5px;">
                                <i class="bi {{ $cfg['icon'] }}" style="font-size:0.65rem;"></i>
                                {{ $cfg['label'] }}
                            </span>
                        </td>

                        <!-- Related Product -->
                        <td>
                            @if($log->product)
                                <div style="font-weight:600;font-size:0.83rem;color:var(--clr-text);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:190px;" title="{{ $log->product->name }}">
                                    {{ $log->product->name }}
                                </div>
                                <div class="mt-1">
                                    <span class="sku-chip" style="font-size:0.68rem;padding:2px 6px;">{{ $log->product->sku }}</span>
                                </div>
                            @elseif($log->product_id)
                                <span style="font-size:0.75rem;color:var(--clr-muted);font-style:italic;">
                                    Produk ID #{{ $log->product_id }} (Terhapus)
                                </span>
                            @else
                                <span style="font-size:0.75rem;color:var(--clr-muted);">— (Sistem Global)</span>
                            @endif
                        </td>

                        <!-- Description & Payload -->
                        <td>
                            <div style="font-size:0.83rem;color:var(--clr-text);line-height:1.45;font-weight:500;">
                                {{ $log->description }}
                            </div>

                            @if($log->payload && count($log->payload) > 0)
                                <div class="mt-2">
                                    <button type="button" onclick="toggleLogPayload('payload-{{ $log->id }}')" 
                                        style="background:none;border:none;padding:0;font-size:0.72rem;color:var(--clr-primary);font-weight:600;cursor:pointer;display:inline-flex;align-items:center;gap:4px;">
                                        <i class="bi bi-code-square"></i> Lihat Payload Perubahan JSON
                                    </button>
                                    <div id="payload-{{ $log->id }}" style="display:none;margin-top:8px;">
                                        <div style="background:#0f172a;border:1px solid #334155;border-radius:10px;padding:12px;position:relative;">
                                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;border-bottom:1px solid rgba(255,255,255,0.08);padding-bottom:4px;">
                                                <span style="font-size:0.68rem;color:#34d399;font-weight:700;letter-spacing:0.5px;text-transform:uppercase;">Snapshot Payload Data</span>
                                                <button type="button" onclick="copyJson('json-pre-{{ $log->id }}', this)" style="background:rgba(255,255,255,0.1);border:none;color:#fff;border-radius:4px;padding:2px 8px;font-size:0.65rem;cursor:pointer;">
                                                    <i class="bi bi-clipboard"></i> Salin
                                                </button>
                                            </div>
                                            <pre id="json-pre-{{ $log->id }}" style="margin:0;font-size:0.72rem;color:#e2e8f0;font-family:'Consolas','Courier New',monospace;line-height:1.4;max-height:220px;overflow:auto;">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>

                        <!-- User & IP -->
                        <td>
                            <div style="font-size:0.8rem;font-weight:600;color:var(--clr-text);">
                                <i class="bi bi-person-circle me-1" style="color:var(--clr-primary)"></i>
                                {{ $log->user_identifier ?: 'System / Guest' }}
                            </div>
                            <div style="font-size:0.72rem;color:var(--clr-muted);font-family:'Courier New',monospace;margin-top:2px;">
                                <i class="bi bi-globe me-1"></i>{{ $log->ip_address ?: '127.0.0.1' }}
                            </div>
                            @if($log->user_agent)
                                <div style="font-size:0.68rem;color:var(--clr-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:160px;margin-top:2px;" title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 28) }}
                                </div>
                            @endif
                        </td>

                        <!-- Time & Duration -->
                        <td class="text-end">
                            <div style="font-size:0.82rem;font-weight:700;color:var(--clr-text);">
                                {{ $log->created_at->format('H:i:s') }}
                            </div>
                            <div style="font-size:0.7rem;color:var(--clr-muted);">
                                {{ $log->created_at->format('d M Y') }}
                            </div>
                            <div class="mt-1">
                                <span style="background:var(--clr-bg);border:1px solid var(--clr-border);padding:1px 6px;border-radius:4px;font-size:0.68rem;font-family:'Courier New',monospace;color:var(--clr-muted);">
                                    {{ $log->execution_time_ms }} ms
                                </span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:56px 20px;color:var(--clr-muted);">
                            <i class="bi bi-journal-x" style="font-size:2.4rem;display:block;opacity:0.3;margin-bottom:10px;"></i>
                            <div style="font-weight:700;font-size:0.95rem;color:var(--clr-text);">Tidak ada rekaman log ditemukan</div>
                            <div style="font-size:0.78rem;margin-top:4px;">Coba gunakan kata kunci lain atau bersihkan filter pencarian.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div style="padding:16px 22px;border-top:1px solid var(--clr-border);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <div style="font-size:0.78rem;color:var(--clr-muted);">
                Halaman {{ $logs->currentPage() }} dari {{ $logs->lastPage() }}
            </div>
            <div>
                {{ $logs->links() }}
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function toggleLogPayload(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
    }

    function copyJson(elementId, btn) {
        const text = document.getElementById(elementId).innerText;
        navigator.clipboard.writeText(text).then(() => {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check2"></i> Tersalin!';
            btn.style.color = '#34d399';
            setTimeout(() => {
                btn.innerHTML = orig;
                btn.style.color = '#fff';
            }, 1800);
        });
    }
</script>
@endpush
