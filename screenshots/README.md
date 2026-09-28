# PANDUAN TANGKAPAN LAYAR (SCREENSHOT BUKTI KERJA BNSP)
**Dokumen Referensi:** Form FR.IA.04A Halaman 5 Poin 3

Sebagai pemenuhan bukti kerja administratif yang wajib dikumpulkan dalam satu folder untuk diverifikasi oleh asesor, silakan lampirkan screenshot berikut ke dalam folder ini (`screenshots/`):

---

### 1. `01_eksekusi_fungsi_crud.png` (Poin 3.a)
- **Halaman yang Ditangkap:** Halaman Katalog Utama (`http://localhost:8000/products`) atau Form Tambah/Edit Produk.
- **Tampilan yang Harus Terlihat:**
  - Tabel produk yang menampilkan daftar item inventaris (ID, SKU, Nama, Kategori, Harga, Stok, Status).
  - Banner flash message sukses: *"Produk ... berhasil ditambahkan ke inventaris!"*
  - Tombol aksi: Detail, Edit, dan Hapus.

---

### 2. `02_eksekusi_pencarian_produk.png` (Poin 3.b)
- **Halaman yang Ditangkap:** Halaman Katalog Utama dengan Filter Aktif (`http://localhost:8000/products?q=Laptop`).
- **Tampilan yang Harus Terlihat:**
  - Input pencarian terisi kata kunci (misal: `Laptop` atau filter kategori).
  - Tabel hasil pencarian yang menampilkan produk relevan yang sesuai dengan filter.
  - URL query string di address bar browser.

---

### 3. `03_hasil_logging_dan_monitoring.png` (Poin 3.c)
- **Halaman yang Ditangkap:** Halaman Dashboard Monitoring (`http://localhost:8000/monitoring`) atau Halaman Detail Produk (`/products/1`).
- **Tampilan yang Harus Terlihat:**
  - Tabel **Log Aktivitas Modul** dengan badge aksi (`CREATE`, `UPDATE`, `SEARCH`, `LOW_STOCK_ALERT`).
  - Kartu telemetri **Average Response Time (ms)**, **P95 Latency**, dan **RAM Peak Usage**.
  - Peringatan aktif (*Low Stock Alert Notifications*).
  - *(Opsional tambahan):* Isi berkas log `storage/logs/product-service.log` di text editor.

---

### 4. `04_bukti_hasil_pengujian.png` (Poin 3.d)
- **Halaman/Terminal yang Ditangkap:** Jendela terminal saat menjalankan salah satu perintah berikut:
  - Eksekusi stress testing:
    ```bash
    php artisan product:stress-test --requests=200
    ```
    *(Menampilkan tabel metrik throughput 238 RPS, avg latency 4.15 ms, dan error rate 0%)*
  - Eksekusi automated test:
    ```bash
    php artisan test
    ```
    *(Menampilkan semua tes berstatus PASS berwarna hijau)*.
