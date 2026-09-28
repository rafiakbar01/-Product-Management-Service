<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Product Management Service') | BNSP Senior Programmer</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --font-family-sans: 'Plus Jakarta Sans', sans-serif;
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --bg-body: #f8fafc;
            --card-border: #e2e8f0;
            --dark-header: #0f172a;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-brand-badge {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            color: white;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 9999px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .navbar-main {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        }
        .nav-link {
            font-weight: 500;
            color: #475569;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.15s ease-in-out;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
            background-color: #eef2ff;
        }
        .card-stat {
            border: 1px solid var(--card-border);
            border-radius: 12px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
            background: #ffffff;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.06);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .table-custom {
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .table-custom thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
        }
        .table-custom tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }
        .badge-type {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
        }
        .footer-banner {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            padding: 16px 0;
            font-size: 0.85rem;
            color: #64748b;
        }
        .metric-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            background: #f1f5f9;
            padding: 3px 10px;
            border-radius: 20px;
            color: #334155;
            font-weight: 500;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-main sticky-top">
        <div class="container-xl">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-dark" href="{{ route('products.index') }}">
                <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-2" style="width: 36px; height: 36px;">
                    <i class="bi bi-box-seam-fill"></i>
                </span>
                <span>Product Management Service</span>
                <span class="navbar-brand-badge">Senior Programmer</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto ms-lg-4 mb-2 mb-lg-0 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-grid-fill me-1"></i> Data Produk (CRUD)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('monitoring.*') ? 'active' : '' }}" href="{{ route('monitoring.index') }}">
                            <i class="bi bi-activity me-1"></i> Monitoring & Telemetri
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/api/v1/products') }}" target="_blank">
                            <i class="bi bi-code-slash me-1"></i> REST API v1
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    <span class="metric-pill">
                        <i class="bi bi-cpu text-primary"></i> PHP {{ PHP_VERSION }}
                    </span>
                    <span class="metric-pill">
                        <i class="bi bi-database text-success"></i> MySQL 8
                    </span>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                        <i class="bi bi-check-circle-fill me-1"></i> Sistem Aktif
                    </span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="py-4">
        <div class="container-xl">
            <!-- Global Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 rounded-3 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer-banner">
        <div class="container-xl d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <strong>Modul Product Management Service</strong> &copy; {{ date('Y') }} — Sertifikasi Kompetensi BNSP Senior Programmer (Jobhun).
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted"><i class="bi bi-shield-check text-primary"></i> Standar SKKNI: J.620100</span>
                <span class="text-muted"><i class="bi bi-diagram-3 text-secondary"></i> Arsitektur: Service-Repository</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
