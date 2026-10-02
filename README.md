# PRODUCT MANAGEMENT SERVICE
### Sistem Manajemen Inventaris IT Perusahaan Digital
**Kategori:** Enterprise Product Management Module  
**Standar:** Enterprise Architecture & Cloud-Ready Design

---

## 📌 Ringkasan Proyek
Modul **Product Management Service** ini dikembangkan untuk mengelola siklus hidup data produk secara internal dalam ekosistem perusahaan digital. Arsitektur aplikasi dibangun di atas **Laravel 12 (PHP 8.2+)** dan basis data **MySQL 8 (InnoDB)** dengan menerapkan pola arsitektur **Service-Repository Pattern** yang kokoh, modular, dan mematuhi prinsip SOLID & OOP.

---

## 🚀 Fitur Utama & Pemenuhan Unit Kompetensi (SKKNI)

| Kelompok Pekerjaan | Unit Kompetensi | Fitur yang Diimplementasikan |
| :--- | :--- | :--- |
| **1. Analisis & Perancangan** | J.620100.001 s/d 014 | - Analisis Tools (Laravel, MySQL, PHPUnit, Monolog)<br>- Normalisasi Skema Relasional 3NF & Composite Indexing<br>- Antarmuka Pengguna Responsif (Bootstrap 5) dengan Auto-Generate SKU<br>- Desain Arsitektur Service-Repository Pattern |
| **2. Implementasi Modul** | J.620100.009 s/d 023 | - OOP murni (`ProductRepositoryInterface`, `ProductServiceInterface`)<br>- Algoritma CRUD & Pencarian Multi-Kolom dinamis<br>- Custom Exception Handling (`ProductNotFoundException`, `DuplicateSkuException`)<br>- Semantic Git Versioning terstruktur<br>- Pengujian Integrasi, Sistem, dan Stress Test Tool |
| **3. Logging & Monitoring** | J.620100.042 s/d 048 | - Dual-Logging: Monolog File (`product-service.log`) & DB Audit Trail (`product_activity_logs`)<br>- Telemetri Latensi & RAM real-time via `PerformanceMonitoringMiddleware`<br>- Alert Notification Otomatis untuk stok kritis (&le; batas minimum)<br>- Laporan Analisis Dampak Perubahan & Evaluasi Optimasi |
| **4. Dokumentasi & Penyajian** | J.620100.049 s/d 051 | - `docs/01_DOKUMENTASI_TEKNIS.md`<br>- `docs/02_DOKUMENTASI_PENGGUNA.md`<br>- `docs/03_LAPORAN_PENGUJIAN.md`<br>- `docs/04_ANALISIS_DAMPAK_DAN_CODE_REVIEW.md`<br>- `docs/05_PANDUAN_JAWABAN_ASESOR_FR_IA_04B.md`<br>

---

## 🛠️ Panduan Menjalankan Aplikasi

### 1. Prasyarat:
- Laragon (Apache/Nginx & MySQL Server Aktif)
- PHP >= 8.2 dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`
- Composer >= 2.0

### 2. Konfigurasi Lingkungan:
Pastikan file `.env` telah mengarah ke database MySQL:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_management
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Migrasi & Seeding Data Awal:
```bash
php artisan migrate --seed
```

### 4. Menjalankan Server Lokal:
```bash
php artisan serve
```
Akses di browser: **[http://localhost:8000](http://localhost:8000)** atau virtual host Laragon `http://product-management.test`.

---

## 🧪 Pengujian & Benchmarking Mandiri

### 1. Menjalankan Automated Tests (PHPUnit):
```bash
php artisan test
```

### 2. Menjalankan Stress & Load Testing:
```bash
php artisan product:stress-test --requests=200
```
*Menghasilkan metrik kecepatan throughput (RPS), latensi rata-rata (ms), P95 latency, dan penggunaan memori.*

---

## 📑 Struktur Berkas Penting Proyek
```
bnsp-project/
├── app/
│   ├── Contracts/              # Interface OOP (Repository & Service Interface)
│   ├── Repositories/           # Repository Pattern (ProductRepository)
│   ├── Services/              # Service Pattern (ProductService, PerformanceMonitorService)
│   ├── Http/Controllers/      # ProductController, MonitoringController, Api/ProductApiController
│   ├── Http/Middleware/       # PerformanceMonitoringMiddleware
│   ├── Http/Requests/         # Form Validation (Store & Update Product Request)
│   ├── Exceptions/            # Custom Exceptions (ProductNotFound, DuplicateSku, InsufficientStock)
│   └── Models/                # Product, Category, ProductActivityLog, PerformanceMetric
├── docs/                      # 📑 Berkas Dokumentasi Teknis & Panduan:
│   ├── 01_DOKUMENTASI_TEKNIS.md
│   ├── 02_DOKUMENTASI_PENGGUNA.md
│   ├── 03_LAPORAN_PENGUJIAN.md
│   ├── 04_ANALISIS_DAMPAK_DAN_CODE_REVIEW.md
│   └── 05_PANDUAN_IMPLEMENTASI.md
├── presentation/
│   └── index.html             # 🖥️ Slide Presentasi Interaktif (30 Menit)
├── screenshots/               # 📸 Folder Tangkapan Layar Dokumentasi
└── routes/web.php             # Rute Web, API v1, dan Presentasi
```
