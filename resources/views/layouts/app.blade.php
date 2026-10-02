<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Product Management') — PMS Dashboard</title>
    <meta name="description" content="Product Management Service — IT Inventory &amp; Performance Telemetry Dashboard">
    <!-- Favicon: Sky Blue Gear Icon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%230284c7'/%3E%3Cpath d='M16 10a6 6 0 1 0 0 12 6 6 0 0 0 0-12zm0 9.5a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7z' fill='%23ffffff'/%3E%3Cpath d='M14.5 7h3v3h-3zm0 15h3v3h-3zM7 14.5h3v3H7zm15 0h3v3h-3zM9.4 9.4l2.1 2.1-1.4 1.4L8 10.8zm11 11 2.1 2.1-1.4 1.4-2.1-2.1zm-11 2.1L8 21.2l1.4-1.4 2.1 2.1zm11-11 2.1-2.1 1.4 1.4-2.1 2.1z' fill='%23bae6fd'/%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --font-body: 'Inter', sans-serif;
            --font-display: 'Inter', sans-serif;

            /* Palette — Sky Blue & Slate */
            --clr-bg: #f8fafc;
            --clr-sidebar: #ffffff;
            --clr-sidebar-border: #e2e8f0;
            --clr-primary: #0284c7;
            --clr-primary-dark: #0369a1;
            --clr-primary-light: #e0f2fe;
            --clr-gold: #38bdf8;
            --clr-gold-light: #f0f9ff;
            --clr-accent: #0ea5e9;
            --clr-accent-light: #f0f9ff;
            --clr-success: #16a34a;
            --clr-success-light: #dcfce7;
            --clr-danger: #dc2626;
            --clr-danger-light: #fee2e2;
            --clr-warning: #d97706;
            --clr-warning-light: #fef3c7;
            --clr-surface: #ffffff;
            --clr-border: #e2e8f0;
            --clr-text: #0f172a;
            --clr-muted: #64748b;
            --radius-card: 14px;
            --radius-btn: 9px;
            --shadow-card: 0 1px 3px rgba(0,0,0,0.05), 0 4px 12px rgba(2,132,199,0.05);
            --shadow-hover: 0 6px 20px rgba(2,132,199,0.14);
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
           SIDEBAR (SKY BLUE & SLATE)
        ═══════════════════════════════════════ */
        .sidebar {
            width: 248px;
            min-height: 100vh;
            background: var(--clr-sidebar);
            border-right: 1px solid var(--clr-sidebar-border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.02);
            transition: width 0.22s cubic-bezier(0.4, 0, 0.2, 1), transform 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-brand {
            padding: 16px 18px;
            border-bottom: 1px solid var(--clr-sidebar-border);
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 60px;
            transition: padding 0.22s ease, justify-content 0.22s ease;
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem; flex-shrink: 0;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.25);
            transition: all 0.22s ease;
        }
        .sidebar-brand .brand-text {
            display: flex;
            flex-direction: column;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.18s ease;
        }
        .sidebar-brand .brand-name {
            font-family: var(--font-display);
            font-size: 0.95rem;
            color: #0f172a;
            font-weight: 700;
            line-height: 1.2;
        }
        .sidebar-brand .brand-sub {
            font-size: 0.65rem;
            color: var(--clr-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
        }

        .sidebar-nav {
            padding: 16px 12px;
            flex: 1;
            transition: padding 0.22s ease;
        }
        .sidebar-nav .nav-section {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            font-weight: 700;
            padding: 12px 10px 6px;
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.18s ease;
        }
        .sidebar-nav a.nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: #475569;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 4px;
            transition: all 0.18s ease;
            position: relative;
            white-space: nowrap;
        }
        .sidebar-nav a.nav-item i {
            font-size: 1.15rem;
            width: 22px;
            text-align: center;
            flex-shrink: 0;
            color: #64748b;
            transition: color 0.18s ease;
        }
        .sidebar-nav a.nav-item .nav-label {
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.18s ease;
        }
        .sidebar-nav a.nav-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .sidebar-nav a.nav-item:hover i {
            color: var(--clr-primary);
        }
        .sidebar-nav a.nav-item.active {
            background: #e0f2fe;
            color: #0369a1;
            font-weight: 600;
            border: 1px solid rgba(2, 132, 199, 0.2);
        }
        .sidebar-nav a.nav-item.active i { 
            color: #0284c7; 
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid var(--clr-sidebar-border);
            transition: padding 0.22s ease;
        }
        .sidebar-footer .version-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.72rem;
            color: #64748b;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar-footer .version-badge strong { color: #334155; }

        /* ═══════════════════════════════════════
           COLLAPSED / MINI SIDEBAR (HIDE LABELS, SHOW ICONS)
        ═══════════════════════════════════════ */
        body.sidebar-collapsed .sidebar {
            width: 70px;
        }
        body.sidebar-collapsed .main-wrapper {
            margin-left: 70px;
        }
        body.sidebar-collapsed .sidebar-brand {
            padding: 12px 10px;
            justify-content: center;
        }
        body.sidebar-collapsed .sidebar-brand .brand-text {
            display: none !important;
        }
        body.sidebar-collapsed .sidebar-nav {
            padding: 16px 8px;
        }
        body.sidebar-collapsed .sidebar-nav .nav-section {
            height: 1px;
            background: var(--clr-sidebar-border);
            margin: 12px 6px;
            padding: 0;
            color: transparent;
            font-size: 0;
            line-height: 0;
        }
        body.sidebar-collapsed .sidebar-nav a.nav-item {
            justify-content: center;
            padding: 11px 0;
            gap: 0;
        }
        body.sidebar-collapsed .sidebar-nav a.nav-item .nav-label {
            display: none !important;
        }
        body.sidebar-collapsed .sidebar-nav a.nav-item i {
            margin: 0;
            font-size: 1.25rem;
        }
        body.sidebar-collapsed .sidebar-footer {
            display: none !important;
        }

        /* ═══════════════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════════════ */
        .main-wrapper {
            margin-left: 248px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Top Bar */
        .topbar {
            background: var(--clr-surface);
            border-bottom: 1px solid var(--clr-border);
            padding: 0 24px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .btn-toggle-sidebar {
            background: var(--clr-bg);
            border: 1px solid var(--clr-border);
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--clr-muted);
            cursor: pointer;
            transition: all 0.18s ease;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .btn-toggle-sidebar:hover {
            background: var(--clr-primary-light);
            color: var(--clr-primary);
            border-color: rgba(2, 132, 199, 0.3);
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(2px);
            z-index: 95;
            opacity: 0;
            transition: opacity 0.2s ease;
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
        .stat-card.emerald::after { background: var(--clr-primary); }
        .stat-card.teal::after    { background: var(--clr-gold); }
        .stat-card.green::after   { background: var(--clr-success); }
        .stat-card.mint::after    { background: var(--clr-accent); }

        .stat-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
        .stat-card .icon-wrap {
            width: 42px; height: 42px;
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            margin-bottom: 14px;
        }
        .stat-card.emerald .icon-wrap { background: var(--clr-primary-light); color: var(--clr-primary); }
        .stat-card.teal    .icon-wrap { background: var(--clr-gold-light);    color: var(--clr-gold); }
        .stat-card.green   .icon-wrap { background: var(--clr-success-light); color: var(--clr-success); }
        .stat-card.mint    .icon-wrap { background: var(--clr-accent-light);  color: var(--clr-accent); }
        /* backwards compatibility for existing classes */
        .stat-card.orange .icon-wrap  { background: var(--clr-primary-light); color: var(--clr-primary); }
        .stat-card.gold .icon-wrap    { background: var(--clr-gold-light);    color: var(--clr-gold); }
        .stat-card.amber .icon-wrap   { background: var(--clr-accent-light);  color: var(--clr-accent); }

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
            box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
        }
        .btn-primary-custom:hover {
            color: #fff;
            background: var(--clr-primary-dark);
            box-shadow: 0 4px 16px rgba(2, 132, 199, 0.35);
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
            color: #0369a1;
            border: 1px solid rgba(2, 132, 199, 0.2);
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
            background: #f8fafc;
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
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-size: 0.85rem;
        }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr {
            transition: background 0.12s;
        }
        .data-table tbody tr:hover { background: #f0f9ff; }

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
            color: #0369a1;
            border: 1px solid rgba(2, 132, 199, 0.2);
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
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
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
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }
        .input-prefix .prefix-label {
            background: #f8fafc;
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

        /* ═══════════════════════════════════════
           RESPONSIVE DESIGN BREAKPOINTS
        ═══════════════════════════════════════ */
        @media (max-width: 992px) {
            .sidebar { 
                transform: translateX(-100%); 
                width: 260px !important; 
                z-index: 1050;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            }
            .main-wrapper { 
                margin-left: 0 !important; 
            }
            body.sidebar-mobile-open .sidebar {
                transform: translateX(0) !important;
            }
            body.sidebar-mobile-open .sidebar-backdrop {
                display: block;
                opacity: 1;
                z-index: 1040;
            }
            body.sidebar-collapsed .main-wrapper {
                margin-left: 0 !important;
            }
            body.sidebar-collapsed .sidebar {
                width: 260px !important;
                transform: translateX(-100%);
            }
            body.sidebar-collapsed.sidebar-mobile-open .sidebar {
                transform: translateX(0) !important;
            }
            body.sidebar-collapsed .sidebar-brand .brand-text,
            body.sidebar-collapsed .sidebar-nav a.nav-item .nav-label,
            body.sidebar-collapsed .sidebar-footer {
                display: flex !important;
            }
            body.sidebar-collapsed .sidebar-nav a.nav-item {
                justify-content: flex-start !important;
                padding: 10px 14px !important;
                gap: 12px !important;
            }
        }

        @media (max-width: 768px) {
            .page-content {
                padding: 16px 12px;
            }
            .topbar {
                padding: 0 14px;
                height: 56px;
            }
            .topbar .page-title {
                font-size: 0.8rem;
                max-width: 170px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .page-heading {
                font-size: 1.25rem;
            }
            .stat-card {
                padding: 14px 16px;
            }
            .stat-card .stat-value {
                font-size: 1.45rem;
            }
            .data-table thead th {
                padding: 9px 12px;
                font-size: 0.7rem;
            }
            .data-table tbody td {
                padding: 11px 12px;
                font-size: 0.82rem;
            }
            .flash-bar {
                padding: 10px 14px;
                font-size: 0.8rem;
            }
            .btn-primary-custom, .btn-ghost {
                padding: 8px 14px;
                font-size: 0.82rem;
            }
        }

        @media (max-width: 576px) {
            .topbar-right .topbar-chip {
                display: none !important;
            }
            .stat-card .stat-value {
                font-size: 1.25rem;
            }
            .modal-dialog {
                margin: 10px auto;
                max-width: calc(100% - 20px);
            }
            .modal-content {
                border-radius: 14px !important;
            }
            .modal-body {
                padding: 16px !important;
            }
            .modal-footer {
                padding: 12px 16px !important;
            }
            footer {
                flex-direction: column;
                gap: 6px;
                text-align: center;
                padding: 12px 16px !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile Backdrop -->
    <div class="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <!-- ═══════════════ SIDEBAR ═══════════════ -->
    <aside class="sidebar" id="appSidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="bi bi-building-gear"></i></div>
            <div class="brand-text">
                <div class="brand-name">IT Inventory PMS</div>
                <div class="brand-sub">Enterprise Service</div>
            </div>
            <!-- Close Button for Mobile -->
            <button type="button" class="btn-close d-lg-none ms-auto" onclick="toggleSidebar()" aria-label="Tutup Menu Sidebar" style="font-size:0.75rem;"></button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.index') ? 'active' : '' }}" title="Katalog Produk" onclick="closeSidebarOnMobile()">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="nav-label">Katalog Produk</span>
            </a>

            <div class="nav-section" style="margin-top: 10px;">Sistem &amp; Observabilitas</div>
            <a href="{{ route('monitoring.index') }}" class="nav-item {{ request()->routeIs('monitoring.index') ? 'active' : '' }}" title="Monitoring Performa" onclick="closeSidebarOnMobile()">
                <i class="bi bi-speedometer2"></i>
                <span class="nav-label">Monitoring Performa</span>
            </a>
            <a href="{{ route('activity-logs.index') }}" class="nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}" title="Log Aktivitas" onclick="closeSidebarOnMobile()">
                <i class="bi bi-journal-text"></i>
                <span class="nav-label">Log Aktivitas</span>
            </a>
            <a href="{{ url('/api/v1/products') }}" class="nav-item" target="_blank" title="REST API Endpoint" onclick="closeSidebarOnMobile()">
                <i class="bi bi-braces-asterisk"></i>
                <span class="nav-label">REST API</span>
            </a>
        </nav>

        
    </aside>

    <!-- ═══════════════ MAIN ═══════════════ -->
    <div class="main-wrapper">
        <!-- Top Bar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn-toggle-sidebar" id="sidebarToggleBtn" onclick="toggleSidebar()" title="Toggle Sembunyikan/Tampilkan Menu Sidebar">
                    <i class="bi bi-layout-sidebar-inset"></i>
                </button>
              
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
            <span class="d-none d-sm-inline">IT Inventory &amp; Performance Telemetry</span>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Mini / Collapsed Toggle
        function toggleSidebar() {
            if (window.innerWidth <= 992) {
                document.body.classList.toggle('sidebar-mobile-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
                const isCollapsed = document.body.classList.contains('sidebar-collapsed');
                try {
                    localStorage.setItem('pms_sidebar_collapsed', isCollapsed ? '1' : '0');
                } catch(e) {}
            }
        }

        // Close mobile drawer when clicking a menu item
        function closeSidebarOnMobile() {
            if (window.innerWidth <= 992) {
                document.body.classList.remove('sidebar-mobile-open');
            }
        }

        // Restore saved preference on load
        (function() {
            try {
                if (window.innerWidth > 992 && localStorage.getItem('pms_sidebar_collapsed') === '1') {
                    document.body.classList.add('sidebar-collapsed');
                }
            } catch(e) {}
        })();
    </script>
    @stack('scripts')
</body>
</html>
