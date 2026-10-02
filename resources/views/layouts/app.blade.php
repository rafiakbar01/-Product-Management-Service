<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Product Management') — PMS Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --font-body: 'Inter', sans-serif;
            --font-display: 'Inter', sans-serif;

            /* Palette — orange to gold */
            --clr-bg: #faf8f4;
            --clr-sidebar: #1c1008;
            --clr-sidebar-glow: #2e1b07;
            --clr-primary: #e07b00;
            --clr-primary-dark: #c06800;
            --clr-primary-light: #fff3e0;
            --clr-gold: #d4a017;
            --clr-gold-light: #fef9e7;
            --clr-accent: #f59e0b;
            --clr-accent-light: #fffbeb;
            --clr-success: #16a34a;
            --clr-success-light: #dcfce7;
            --clr-danger: #dc2626;
            --clr-danger-light: #fee2e2;
            --clr-warning: #ca8a04;
            --clr-warning-light: #fef9c3;
            --clr-surface: #ffffff;
            --clr-border: #ede8df;
            --clr-text: #1a110a;
            --clr-muted: #7a6a58;
            --radius-card: 14px;
            --radius-btn: 9px;
            --shadow-card: 0 1px 4px rgba(0,0,0,0.06), 0 4px 16px rgba(224,123,0,0.07);
            --shadow-hover: 0 6px 28px rgba(224,123,0,0.16);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background-color: var(--clr-bg);
            color: var(--clr-text);
            min-height: 100vh;
            display: flex;
            font-size: 0.9rem;
        }

        /* ═══════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════ */
        .sidebar {
            width: 248px;
            min-height: 100vh;
            background: var(--clr-sidebar);
            border-right: 1px solid rgba(255,255,255,0.05);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-brand {
            padding: 24px 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand .brand-icon {
            width: 36px; height: 36px;
            background: var(--clr-primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1rem; flex-shrink: 0;
        }
        .sidebar-brand .brand-name {
            font-family: var(--font-display);
            font-size: 1rem;
            color: #fdf0dc;
            font-weight: 700;
            line-height: 1.2;
        }
        .sidebar-brand .brand-sub {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
        }
        .sidebar-nav .nav-section {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.25);
            font-weight: 600;
            padding: 12px 10px 6px;
        }
        .sidebar-nav a.nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 2px;
            transition: all 0.18s ease;
            position: relative;
        }
        .sidebar-nav a.nav-item i {
            font-size: 1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-nav a.nav-item:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.85);
        }
        .sidebar-nav a.nav-item.active {
            background: rgba(224,123,0,0.22);
            color: #fdd996;
            border: 1px solid rgba(224,123,0,0.25);
        }
        .sidebar-nav a.nav-item.active i { color: #f59e0b; }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-footer .version-badge {
            background: rgba(255,255,255,0.06);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.4);
        }
        .sidebar-footer .version-badge strong { color: rgba(255,255,255,0.65); }

        /* ═══════════════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════════════ */
        .main-wrapper {
            margin-left: 248px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Bar */
        .topbar {
            background: var(--clr-surface);
            border-bottom: 1px solid var(--clr-border);
            padding: 0 28px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar .page-title {
            font-size: 0.85rem;
            color: var(--clr-muted);
            display: flex; align-items: center; gap: 6px;
        }
        .topbar .page-title span { color: var(--clr-text); font-weight: 600; }

        .topbar-right {
            display: flex; align-items: center; gap: 12px;
        }
        .status-dot {
            display: flex; align-items: center; gap: 6px;
            font-size: 0.75rem;
            color: var(--clr-success);
            font-weight: 500;
            background: var(--clr-success-light);
            padding: 4px 10px;
            border-radius: 20px;
        }
        .status-dot::before {
            content: '';
            width: 6px; height: 6px;
            background: var(--clr-success);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .topbar-chip {
            background: var(--clr-bg);
            border: 1px solid var(--clr-border);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.73rem;
            color: var(--clr-muted);
            font-weight: 500;
        }

        /* Page Content */
        .page-content {
            padding: 28px;
            flex: 1;
        }

        /* ═══════════════════════════════════════
           CARDS
        ═══════════════════════════════════════ */
        .card-glass {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            transition: box-shadow 0.22s ease, transform 0.22s ease;
        }
        .card-glass:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-1px);
        }

        /* Stat Cards */
        .stat-card {
            background: var(--clr-surface);
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-card);
            padding: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.22s ease;
        }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            opacity: 0.06;
            transform: translate(20px, -20px);
        }
        .stat-card.orange::after { background: var(--clr-primary); }
        .stat-card.gold::after   { background: var(--clr-gold); }
        .stat-card.green::after  { background: var(--clr-success); }
        .stat-card.amber::after  { background: var(--clr-accent); }

        .stat-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
        .stat-card .icon-wrap {
            width: 42px; height: 42px;
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            margin-bottom: 14px;
        }
        .stat-card.orange .icon-wrap { background: var(--clr-primary-light); color: var(--clr-primary); }
        .stat-card.gold   .icon-wrap { background: var(--clr-gold-light);    color: var(--clr-gold); }
        .stat-card.green  .icon-wrap { background: var(--clr-success-light); color: var(--clr-success); }
        .stat-card.amber  .icon-wrap { background: var(--clr-accent-light);  color: var(--clr-accent); }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            font-family: var(--font-display);
            line-height: 1;
            margin-bottom: 4px;
        }
        .stat-card .stat-label {
            font-size: 0.75rem;
            color: var(--clr-muted);
            font-weight: 500;
        }

        /* ═══════════════════════════════════════
           BUTTONS
        ═══════════════════════════════════════ */
        .btn-primary-custom {
            background: var(--clr-primary);
            color: #fff;
            border: none;
            border-radius: var(--radius-btn);
            padding: 9px 20px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.18s ease;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 7px;
        }
        .btn-primary-custom:hover {
            color: #fff;
            background: var(--clr-primary-dark);
            box-shadow: 0 4px 16px rgba(224,123,0,0.3);
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--clr-border);
            border-radius: var(--radius-btn);
            padding: 8px 16px;
            font-weight: 500;
            font-size: 0.82rem;
            color: var(--clr-muted);
            transition: all 0.18s ease;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 7px;
        }
        .btn-ghost:hover {
            background: var(--clr-primary-light);
            color: var(--clr-primary);
            border-color: var(--clr-primary);
        }

        .btn-danger-sm {
            background: var(--clr-danger-light);
            color: var(--clr-danger);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 0.78rem;
            font-weight: 600;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-danger-sm:hover { background: var(--clr-danger); color: white; }

        .btn-edit-sm {
            background: var(--clr-primary-light);
            color: var(--clr-primary);
            border: 1px solid rgba(224,123,0,0.2);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 0.78rem;
            font-weight: 600;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-edit-sm:hover { background: var(--clr-primary); color: white; }

        .btn-view-sm {
            background: #f0fdf4;
            color: var(--clr-success);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 0.78rem;
            font-weight: 600;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn-view-sm:hover { background: var(--clr-success); color: white; }

        /* ═══════════════════════════════════════
           TABLE
        ═══════════════════════════════════════ */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table thead th {
            background: #fdf9f2;
            padding: 11px 16px;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-weight: 700;
            color: var(--clr-muted);
            border-bottom: 1px solid var(--clr-border);
            white-space: nowrap;
        }
        .data-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #faf5ee;
            vertical-align: middle;
            font-size: 0.85rem;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr {
            transition: background 0.12s;
        }
        .data-table tbody tr:hover { background: #fdf7ef; }

        /* ═══════════════════════════════════════
           BADGES
        ═══════════════════════════════════════ */
        .badge-status {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }
        .badge-active   { background: var(--clr-success-light); color: var(--clr-success); }
        .badge-inactive { background: #f1f5f9; color: #64748b; }
        .badge-draft    { background: var(--clr-warning-light); color: #92400e; }
        .badge-low      { background: var(--clr-warning-light); color: #92400e; }
        .badge-out      { background: var(--clr-danger-light); color: var(--clr-danger); }
        .badge-ok       { background: var(--clr-success-light); color: var(--clr-success); }

        .sku-chip {
            font-family: 'Courier New', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            background: var(--clr-primary-light);
            color: var(--clr-primary-dark);
            border: 1px solid rgba(224,123,0,0.18);
            padding: 3px 9px;
            border-radius: 6px;
        }

        .type-chip {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #475569;
            white-space: nowrap;
        }

        /* ═══════════════════════════════════════
           FORMS
        ═══════════════════════════════════════ */
        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--clr-text);
            margin-bottom: 6px;
            display: block;
        }
        .form-control-custom, .form-select-custom {
            border: 1.5px solid var(--clr-border);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.85rem;
            font-family: var(--font-body);
            color: var(--clr-text);
            background: var(--clr-surface);
            width: 100%;
            transition: border-color 0.18s, box-shadow 0.18s;
            outline: none;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(224,123,0,0.12);
        }
        .form-control-custom.is-invalid, .form-select-custom.is-invalid {
            border-color: var(--clr-danger);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.08);
        }
        .invalid-msg {
            font-size: 0.75rem;
            color: var(--clr-danger);
            margin-top: 4px;
        }

        /* Search Bar */
        .search-wrap {
            position: relative;
        }
        .search-wrap i {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--clr-muted);
            font-size: 0.9rem;
        }
        .search-input {
            padding-left: 36px !important;
        }

        /* Prefix input group */
        .input-prefix {
            display: flex;
            border: 1.5px solid var(--clr-border);
            border-radius: 10px;
            overflow: hidden;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .input-prefix:focus-within {
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(224,123,0,0.12);
        }
        .input-prefix .prefix-label {
            background: #fdf9f2;
            padding: 10px 12px;
            font-size: 0.82rem;
            color: var(--clr-muted);
            font-weight: 600;
            border-right: 1.5px solid var(--clr-border);
            white-space: nowrap;
        }
        .input-prefix input {
            border: none;
            outline: none;
            padding: 10px 14px;
            font-size: 0.85rem;
            width: 100%;
            font-family: var(--font-body);
        }

        /* Section Header inside Forms */
        .form-section-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: var(--clr-muted);
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--clr-border);
            display: flex; align-items: center; gap: 8px;
        }
        .form-section-title i { color: var(--clr-accent); font-size: 0.9rem; }

        /* ═══════════════════════════════════════
           ALERT STRIP
        ═══════════════════════════════════════ */
        .alert-strip {
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 14px 18px;
        }
        .alert-strip-danger {
            background: linear-gradient(135deg, #fff5f5, var(--clr-danger-light));
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 14px 18px;
        }
        .alert-strip-success {
            background: linear-gradient(135deg, #f0fdf4, var(--clr-success-light));
            border: 1px solid #6ee7b7;
            border-radius: 12px;
            padding: 14px 18px;
        }

        /* ═══════════════════════════════════════
           FLASH MESSAGE
        ═══════════════════════════════════════ */
        .flash-bar {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
            font-size: 0.85rem;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .flash-bar.success {
            background: var(--clr-success-light);
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .flash-bar.error {
            background: var(--clr-danger-light);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* ═══════════════════════════════════════
           MISC
        ═══════════════════════════════════════ */
        .page-heading {
            font-family: var(--font-display);
            font-size: 1.45rem;
            font-weight: 800;
            color: var(--clr-text);
            margin: 0;
        }
        .page-sub {
            color: var(--clr-muted);
            font-size: 0.82rem;
            margin-top: 3px;
        }

        textarea.form-control-custom { resize: vertical; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-248px); }
            .main-wrapper { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- ═══════════════ SIDEBAR ═══════════════ -->
    <aside class="sidebar">
        <div class="sidebar-brand d-flex align-items-center gap-3">
            <div class="brand-icon"><i class="bi bi-building-gear"></i></div>
            <div>
                <div class="brand-name">IT Inventory PMS</div>
                
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.index') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Katalog Produk
            </a>
            <div class="nav-section" style="margin-top: 10px;">Sistem & Observabilitas</div>
            <a href="{{ route('monitoring.index') }}" class="nav-item {{ request()->routeIs('monitoring.index') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Monitoring Performa
            </a>
            <a href="{{ route('activity-logs.index') }}" class="nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Log Aktivitas
            </a>
            <a href="{{ url('/api/v1/products') }}" class="nav-item" target="_blank">
                <i class="bi bi-braces-asterisk"></i> REST API
            </a>
        </nav>

        <div class="sidebar-footer">
           
        </div>
    </aside>

    <!-- ═══════════════ MAIN ═══════════════ -->
    <div class="main-wrapper">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="page-title">
                <i class="bi bi-house-door"></i>
                @yield('breadcrumb', '<span>Dashboard</span>')
            </div>
        </header>

        <!-- Flash Messages -->
        <div class="px-4 pt-4" style="padding-bottom: 0;">
            @if(session('success'))
                <div class="flash-bar success">
                    <i class="bi bi-check-circle-fill fs-6"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flash-bar error">
                    <i class="bi bi-exclamation-triangle-fill fs-6"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer style="padding: 14px 28px; border-top: 1px solid var(--clr-border); background: var(--clr-surface); font-size: 0.75rem; color: var(--clr-muted); display: flex; justify-content: space-between; align-items: center;">
            <span>Product Management Service &copy; {{ date('Y') }}</span>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
