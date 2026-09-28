# PANDUAN PENGGUNA (USER MANUAL)
## MODUL PRODUCT MANAGEMENT SERVICE
**Skema Sertifikasi:** Senior Programmer (BNSP)  
**Dokumen Referensi:** UK J.620100.051.01 (Menyusun Dokumentasi Pengguna)

---

### 1. Pendahuluan
Dokumentasi ini disusun untuk memandu pengguna operasional internal dan staf manajemen inventaris dalam mengoperasikan modul **Product Management Service**. Antarmuka modul ini dirancang ramah pengguna (*user-friendly*), responsif, dan dilengkapi sistem perlindungan data (*safety confirmation*).

---

### 2. Cara Menjalankan Aplikasi (Setup Awal)

1. **Jalankan Laragon:**
   - Buka aplikasi **Laragon**, lalu klik tombol **Start All** untuk menyalakan Apache/Nginx dan MySQL Server.
2. **Buka Terminal / Command Prompt:**
   - Navigasikan ke direktori project:
     ```bash
     cd d:\Laragon\www\bnsp-project
     ```
3. **Migrasi Database & Isi Data Sampel (Seeding):**
   ```bash
   php artisan migrate:fresh --seed
   ```
4. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser pada alamat: **`http://localhost:8000`** atau host virtual Laragon `http://bnsp-project.test`.

---

### 3. Panduan Penggunaan Fitur

#### 3.1 Melihat Katalog & Statistik Produk (Dashboard Utama)
- Buka menu **"Data Produk (CRUD)"** pada bilah navigasi atas.
- Di bagian atas, Anda akan melihat 4 kartu indikator utama:
  1. **Total Produk:** Jumlah seluruh varian produk yang terdaftar.
  2. **Produk Aktif:** Produk yang saat ini berstatus aktif diperjualbelikan.
  3. **Stok Kritis / Menipis:** Jumlah item yang memiliki stok sama dengan atau di bawah batas peringatan.
  4. **Nilai Total Inventaris:** Estimasi total nilai aset rupiah seluruh stok fisik.

#### 3.2 Menambahkan Produk Baru
1. Klik tombol **"+ Tambah Produk Baru"** di pojok kanan atas katalog.
2. Isi formulir dengan data yang valid:
   - **Nama Produk:** Nama barang atau layanan (contoh: *Mouse Wireless Ergonomis*).
   - **SKU:** Kode unik stok barang. Anda dapat mengklik tombol **"Auto-Generate"** untuk membuat SKU otomatis secara instan.
   - **Kategori:** Pilih kategori produk dari pilihan yang tersedia.
   - **Tipe Produk:** Pilih *Barang Fisik*, *Produk Digital*, atau *Jasa Konsultasi*.
   - **Harga Jual:** Masukkan harga jual dalam rupiah (tanpa titik).
   - **Jumlah Stok Awal:** Masukkan jumlah unit barang.
   - **Batas Minimum Peringatan (Alert Threshold):** Masukkan batas stok menipis (misal: `5`). Sistem akan memicu notifikasi jika stok barang tersisa &le; 5 unit.
3. Klik tombol **"Simpan Produk"**. Notifikasi sukses akan muncul di layar.

#### 3.3 Menggunakan Algoritma Pencarian & Filtering
Untuk mempermudah menemukan produk di antara ribuan inventaris:
1. Masukkan kata kunci pada kotak pencarian (bisa mencari berdasarkan nama, SKU, atau deskripsi).
2. Anda dapat menggabungkan pencarian dengan filter:
   - **Kategori:** Menyaring produk berdasarkan departemen/kategori.
   - **Tipe Produk:** Fisik / Digital / Layanan.
   - **Status Stok:** Pilih *Tersedia*, *Stok Menipis (&le; Min)*, atau *Habis (0)*.
3. Klik tombol **"Filter"**.
4. Untuk menghapus semua filter, klik tombol **"Reset"** (ikon putar balik).

#### 3.4 Melihat Detail & Riwayat Audit Log Produk
1. Pada tabel produk, klik tombol aksi ikon mata (**View Detail**).
2. Anda akan melihat informasi spesifikasi lengkap produk beserta **Riwayat Audit & Activity Logs**.
3. Di dalam tabel log, Anda dapat mengklik tombol **"Lihat Payload"** untuk menginspeksi JSON rekaman perubahan data secara transparan.

#### 3.5 Memperbarui Data Produk (Edit)
1. Klik tombol ikon pensil (**Edit**) pada baris produk yang diinginkan.
2. Ubah data yang perlu diperbarui (misal: harga jual atau penambahan stok).
3. Klik **"Perbarui Data Produk"**. Sistem secara otomatis mencatat riwayat perubahan stok lama dan stok baru ke dalam log audit.

#### 3.6 Menghapus Produk (Soft Delete)
1. Klik tombol ikon tempat sampah (**Hapus**) pada produk yang ingin dihapus.
2. Kotak dialog konfirmasi (*Modal Dialog Safety*) akan muncul untuk mencegah penghapusan yang tidak disengaja.
3. Klik **"Ya, Hapus Produk"**. Produk akan disembunyikan dari katalog namun riwayat transaksinya tetap tersimpan dengan aman (*Soft Deletes*).

---

### 4. Panduan Monitoring & Telemetri Performa
Untuk staf IT / DevOps / Lead Developer:
1. Buka menu **"Monitoring & Telemetri"** di bilah navigasi atas (`http://localhost:8000/monitoring`).
2. Halaman ini menyajikan:
   - **Rata-rata Respon (Latency):** Waktu respon rata-rata server dalam satuan milidetik (ms).
   - **Latensi P95:** Batas latensi pada 95% transaksi pengguna.
   - **Puncak Memori:** Pemakaian RAM server oleh modul PHP.
   - **Daftar Peringatan Sistem Aktif:** Produk mana saja yang stoknya kritis dan membutuhkan *reorder* segera.
   - **Telemetri HTTP Requests:** Tabel riwayat permintaan HTTP lengkap dengan status code, memory, dan query count.
   - **Log Aktivitas Terfilter:** Riwayat aksi `CREATE`, `UPDATE`, `DELETE`, `SEARCH`, dan `ALERT` secara kronologis.

---

### 5. Panduan Penggunaan REST API
Modul ini juga menyediakan antarmuka terprogram untuk integrasi dengan sistem mobile apps atau aplikasi POS pihak ketiga:
- **Base URL:** `http://localhost:8000/api/v1`
- **Contoh Request Pencarian:**
  ```http
  GET /api/v1/products?q=Laptop&per_page=5
  ```
- **Contoh Response JSON:**
  ```json
  {
    "success": true,
    "message": "Daftar produk berhasil dimuat.",
    "data": [
      {
        "id": 1,
        "sku": "ELC-LTP-001",
        "name": "Laptop Ultra Pro 15 inch M3",
        "price": "24500000.00",
        "stock": 12,
        "status": "active"
      }
    ],
    "pagination": {
      "current_page": 1,
      "total": 1
    }
  }
  ```
