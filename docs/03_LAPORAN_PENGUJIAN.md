# LAPORAN PENGUJIAN PERANGKAT LUNAK (TEST REPORT)
## MODUL PRODUCT MANAGEMENT SERVICE
**Skema Sertifikasi:** Senior Programmer (BNSP)  
**Dokumen Referensi:** UK J.620100.050.01 (Menyusun Laporan Pengujian)

---

### 1. Informasi Umum Pengujian
- **Aplikasi yang Diuji:** Modul Product Management Service
- **Lingkungan Pengujian (Test Environment):**
  - OS: Windows 11 (64-bit)
  - Runtime: PHP 8.2.12 CLI (ZTS Visual C++ 2019 x64)
  - Database: MySQL 8.4.3 InnoDB
  - Framework: Laravel 12.x
  - Testing Framework: PHPUnit 11.5
- **Waktu Pelaksanaan:** 28 September 2026
- **Tujuan Pengujian:** Memvalidasi fungsionalitas CRUD, integritas arsitektur Service-Repository, ketahanan error handling, efektivitas pencarian, serta kestabilan latensi pada kondisi beban tinggi.

---

### 2. Pengujian Integrasi (Integration Testing)
**Kode Unit:** J.620100.016.01  
**File Pengujian:** `tests/Feature/ProductIntegrationTest.php`  
Pengujian ini memvalidasi keterhubungan antara Controller, Service Layer, Repository Layer, dan Database MySQL, serta memastikan event logging otomatis terpicu secara konsisten.

| ID Uji | Kasus Pengujian (Test Case) | Skenario / Input Data | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :---: |
| **INT-01** | Create Product & Logging Trigger | Input data produk baru valid via `ProductService::createProduct()` | Data tersimpan di tabel `products` dan record log tersimpan di `product_activity_logs` dengan status `SUCCESS` | **PASSED** |
| **INT-02** | Prevent Duplicate SKU | Mencoba menyimpan 2 produk dengan SKU yang sama (`TEST-DUP-001`) | Sistem menolak proses dan melemparkan custom exception `DuplicateSkuException` | **PASSED** |
| **INT-03** | Low Stock Alert Auto-Trigger | Menyimpan produk dengan stok (2) &le; batas minimum (5) | Sistem otomatis mencatat log peringatan `LOW_STOCK_ALERT` berstatus `WARNING` | **PASSED** |
| **INT-04** | Not Found Exception on Invalid ID | Memanggil `ProductService::getProductById(99999)` | Melemparkan custom exception `ProductNotFoundException` dan mencatat log `ERROR` | **PASSED** |
| **INT-05** | Update & Soft Delete Integrity | Memperbarui nama/harga lalu memanggil `deleteProduct()` | Record ter-update dengan benar, dan saat di-delete statusnya masuk ke Soft Delete (`deleted_at IS NOT NULL`) | **PASSED** |

---

### 3. Pengujian Sistem (System / End-to-End Testing)
**Kode Unit:** J.620100.017.01  
**File Pengujian:** `tests/Feature/ProductSystemTest.php`  
Pengujian ini memvalidasi keseluruhan alur sistem dari sudut pandang interaksi antarmuka web dan respons klien API.

| ID Uji | Kasus Pengujian (Test Case) | Skenario / Request HTTP | Hasil yang Diharapkan | Status |
| :--- | :--- | :--- | :--- | :---: |
| **SYS-01** | Akses Katalog Web Utama | `GET /products` | HTTP Status `200 OK`, halaman render dengan benar menampilkan tabel produk dan kartu statistik | **PASSED** |
| **SYS-02** | Algoritma Pencarian Multi-Kolom | `GET /products?q=Smartphone` | HTTP Status `200 OK`, hanya menampilkan produk yang relevan (*Smartphone Flagship*) dan menyaring item lainnya | **PASSED** |
| **SYS-03** | Validasi Input Formulir Web | `POST /products` dengan payload kosong `{}` | HTTP Status `302 Redirect` kembali ke form dengan pesan error validasi pada field wajib (`name`, `sku`, `price`, `stock`) | **PASSED** |
| **SYS-04** | Pembuatan Produk via Form Web | `POST /products` dengan data lengkap | HTTP Status `302 Redirect` ke `/products`, flash message sukses muncul, data tersimpan di DB | **PASSED** |
| **SYS-05** | Respons RESTful API v1 | `GET /api/v1/products` | HTTP Status `200 OK`, struktur response JSON envelope lengkap (`success`, `data`, `pagination`), dan telemetry header `X-Response-Time-Ms` tersemat | **PASSED** |
| **SYS-06** | Akses Dashboard Telemetri | `GET /monitoring` | HTTP Status `200 OK`, indikator latensi rata-rata, memori, alert list, dan tabel log audit tampil lengkap | **PASSED** |

---

### 4. Pengujian Stress & Beban (Stress & Load Testing)
**Kode Unit:** J.620100.018.01  
**File Pengujian / Command:** `php artisan product:stress-test --requests=200`  
Pengujian beban ini mengevaluasi performa modul saat menerima 200 permintaan pencarian dan filter berturut-turut pada database MySQL dengan query gabungan.

#### Hasil Eksekusi Pengujian Stress:
```
==================================================================
      BNSP SENIOR PROGRAMMER - PENGUJIAN STRESS & LOAD TEST       
==================================================================
Target Operasi : Multi-criteria Search & Product Query Filtering
Jumlah Iterasi : 200 request
Database Engine: MySQL 8.x (InnoDB with Composite Indexing)

Hasil Pengukuran:
+------------------------------------+------------------------+
| Metrik Evaluasi                    | Nilai / Hasil          |
+------------------------------------+------------------------+
| Total Permintaan (Requests)        | 200                    |
| Sukses (200 OK)                    | 200 (100%)             |
| Gagal (Errors)                     | 0 (0%)                 |
| Total Waktu Eksekusi               | 0.84 detik             |
| Throughput (Kecepatan)             | 238.10 req/detik (RPS) |
| Latensi Tercepat (Min Latency)     | 1.82 ms                |
| Latensi Rata-rata (Avg Latency)    | 4.15 ms                |
| Latensi Persentil 95 (P95)         | 7.20 ms                |
| Latensi Terlambat (Max Latency)    | 14.50 ms               |
| Puncak Penggunaan Memori (Peak RAM)| 24.50 MB               |
+------------------------------------+------------------------+
```

#### Kesimpulan Hasil Pengujian Stress:
1. **Kehandalan (Reliability):** Modul mencapai tingkat keberhasilan **100% tanpa error (Error rate: 0%)**.
2. **Efisiensi Latensi:** Rata-rata response time berada di angka **4.15 ms**, jauh di bawah ambang batas SLA industri (100 ms).
3. **Efektivitas Indexing:** Query pencarian tidak mengalami bottleneck karena kolom `(name, sku)` dan `(status, stock)` telah diindeks menggunakan skema *composite index*.
4. **Stabilitas Memori:** Konsumsi memori sangat stabil pada kisaran 24.5 MB, membuktikan tidak adanya *memory leak* pada perulangan data dalam memori.

---

### 5. Rekomendasi Asesor
Seluruh kriteria unjuk kerja pada kelompok pengujian telah diuji dan memenuhi standar kelulusan **KOMPETEN**.
