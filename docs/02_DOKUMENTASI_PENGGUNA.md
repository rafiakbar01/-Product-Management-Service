# PANDUAN PENGGUNA (USER MANUAL)
## MODUL: IT INVENTORY — PRODUCT MANAGEMENT SERVICE

| Keterangan | Detail |
|:---|:---|
| **Nama Asesi** | *(Isi Nama Lengkap)* |
| **Skema Sertifikasi** | Senior Programmer (BNSP) |
| **Unit Kompetensi** | UK J.620100.051.01 (Menyusun Dokumentasi Pengguna) |
| **Tanggal Dokumen** | 30 September 2026 |

---

## 1. Pengantar Sistem

Modul **IT Inventory — Product Management Service** adalah sistem pengelolaan inventaris berbasis web yang dirancang khusus untuk kebutuhan **kantor perusahaan digital**. Sistem ini memungkinkan staf IT, manajer pengadaan, dan administrator untuk:

- Mendaftarkan dan mengelola **aset hardware** (laptop, server, jaringan)
- Mengelola **lisensi software & SaaS** (Microsoft 365, antivirus, cloud tools)
- Mencatat **kontrak layanan IT** (IT Support, cloud migration)
- Memantau **stok kritis** dan nilai total aset kantor secara real-time
- Menelusuri **riwayat audit** setiap perubahan data secara transparan

---

## 2. Cara Menjalankan Aplikasi

### Prasyarat:
- Laragon (Apache/Nginx + MySQL) sudah terinstall
- PHP 8.2+ dan Composer tersedia

### Langkah Setup:

```bash
# 1. Masuk ke direktori proyek
cd d:\Laragon\www\bnsp-project

# 2. Install dependensi (jika belum)
composer install

# 3. Salin konfigurasi environment
copy .env.example .env
php artisan key:generate

# 4. Migrasi & isi data awal (sample data)
php artisan migrate:fresh --seed

# 5. Jalankan server lokal
php artisan serve
```

**Akses Aplikasi:** Buka browser dan kunjungi `http://localhost:8000`

---

## 3. Navigasi Antarmuka

Bilah navigasi kiri (sidebar) berisi:

| Menu | URL | Fungsi |
|:---|:---|:---|
| 🗂️ **Katalog Produk** | `/products` | Halaman utama katalog & CRUD |
| 📊 **Monitoring & Telemetri** | `/monitoring` | Dashboard performa sistem |
| 📋 **Log Aktivitas** | `/activity-logs` | Audit trail semua operasi |

---

## 4. Panduan Fitur Lengkap

### 4.1 Dashboard Katalog Utama (`/products`)

Saat membuka halaman katalog, Anda akan melihat **4 Kartu Statistik** di bagian atas:

| Kartu | Keterangan |
|:---|:---|
| 🗃️ **Total Item Terdaftar** | Jumlah seluruh produk/aset yang ada di database |
| ✅ **Aset / Lisensi Aktif** | Item berstatus `Active` yang sedang digunakan |
| ⚠️ **Stok Kritis / Perlu Reorder** | Item dengan stok ≤ batas minimum (alert threshold) |
| 💰 **Total Nilai Aset Kantor** | Estimasi total nilai rupiah inventaris kantor |

> **Alert Strip Stok Kritis:** Jika ada produk dengan stok di bawah batas minimum, sebuah banner kuning otomatis muncul di atas halaman menampilkan daftar item kritis.

---

### 4.2 Registrasi Aset / Produk Baru (Create)

1. Klik tombol **"Registrasi Aset / Produk Baru"** (pojok kanan atas).
2. Modal formulir akan muncul. Isi field berikut:

| Field | Keterangan | Contoh |
|:---|:---|:---|
| **Nama Produk / Aset** | Nama lengkap item | `Laptop Workstation Dell Precision 5680` |
| **SKU** | Kode unik item. Klik **"Generate"** untuk otomatis | `HW-142` |
| **Kategori** | Pilih dari dropdown kategori IT | `Perangkat Komputasi` |
| **Tipe Aset** | Hardware Asset / Lisensi SaaS / Layanan IT | `Hardware Asset` |
| **Harga Beli (HPP)** | Harga perolehan aset (Rupiah) | `28000000` |
| **Harga Jual / Charge** | Harga jual atau biaya charge internal | `32000000` |
| **Stok / Jumlah Unit** | Jumlah unit yang tersedia | `5` |
| **Batas Alert (Min Stok)** | Jumlah unit minimum sebelum peringatan muncul | `2` |
| **Status** | Active / Inactive / Draft | `Active` |
| **Deskripsi** | Spesifikasi teknis atau keterangan tambahan | `Intel i9-13900H, 32GB RAM...` |

3. Klik **"Simpan Produk"**. Notifikasi hijau sukses akan muncul.

> **Catatan:** SKU harus unik. Jika SKU sudah ada, sistem akan menampilkan pesan error dan menolak penyimpanan.

---

### 4.3 Pencarian & Filtering Multi-Kriteria

Gunakan panel filter di bawah tombol "Registrasi" untuk mencari produk:

| Filter | Fungsi |
|:---|:---|
| **🔍 Cari Produk** | Pencarian teks di nama, SKU, dan deskripsi secara serentak |
| **Kategori** | Filter berdasarkan kategori (misal: Jaringan & Infrastruktur) |
| **Tipe Aset** | Filter berdasarkan Hardware Asset / Lisensi SaaS / Layanan IT |
| **Kondisi Stok** | Filter: *Tersedia Aman* / *Stok Menipis* / *Stok Habis (0)* |

**Cara penggunaan:**
1. Isi satu atau lebih field filter.
2. Klik tombol **"Filter"**.
3. Untuk menghapus semua filter, klik tombol **"Reset"** (ikon ↺).

> **Tips:** Anda bisa menggabungkan keyword pencarian dengan filter kategori. Contoh: cari *"Dell"* + filter *Hardware Asset* untuk menemukan semua laptop Dell.

---

### 4.4 Melihat Detail & Audit Log Produk (View)

1. Pada tabel produk, klik ikon **👁️ (mata)** di kolom aksi.
2. Modal **Detail Produk** terbuka menampilkan:
   - Informasi lengkap: nama, SKU, kategori, tipe, harga, stok, status
   - **Riwayat Audit Log** — seluruh operasi yang pernah dilakukan pada produk ini (CREATE, UPDATE, DELETE, ALERT)
3. Klik **"Lihat Payload"** pada baris log untuk melihat snapshot JSON perubahan data sebelum dan sesudah operasi.

---

### 4.5 Memperbarui Data Produk (Edit)

1. Klik ikon **✏️ (pensil)** pada baris produk.
2. Modal Edit terbuka dengan data produk yang sudah ter-*prefill*.
3. Ubah field yang diinginkan (misal: tambah stok atau update harga lisensi).
4. Klik **"Perbarui Data Produk"**.

> **Otomatis Dicatat:** Setiap perubahan (termasuk perubahan stok lama → stok baru) akan otomatis dicatat ke audit log dengan payload diff.

---

### 4.6 Menghapus Produk (Soft Delete)

1. Klik ikon **🗑️ (tempat sampah)** pada baris produk.
2. Modal konfirmasi keamanan muncul: *"Yakin ingin menghapus produk ini?"*
3. Klik **"Ya, Hapus Produk"**.

> **Soft Delete:** Produk tidak dihapus secara permanen dari database. Data tetap tersimpan (field `deleted_at` diisi) sehingga riwayat transaksi dan audit log tetap terjaga.

---

## 5. Panduan Halaman Monitoring & Telemetri (`/monitoring`)

> Khusus untuk: Staf IT, DevOps Engineer, atau Lead Developer.

Halaman ini menampilkan performa sistem secara real-time:

| Indikator | Keterangan |
|:---|:---|
| ⚡ **Avg Response Time** | Rata-rata waktu respon server (ms) |
| 📈 **P95 Latency** | Batas latensi pada 95% transaksi |
| 🧠 **Peak Memory** | Puncak penggunaan RAM PHP |
| 🔁 **Total Requests** | Jumlah HTTP request yang telah diproses |
| ⚠️ **Alert Stok Kritis** | Daftar produk yang butuh reorder segera |
| 📊 **Tabel Telemetri** | Riwayat request lengkap (URL, status, waktu, query count) |

---

## 6. Panduan Halaman Log Aktivitas (`/activity-logs`)

Halaman khusus audit trail yang menampilkan seluruh operasi sistem:

| Kolom | Keterangan |
|:---|:---|
| **Waktu** | Timestamp operasi |
| **Aksi** | CREATE / UPDATE / DELETE / SEARCH / ALERT / ERROR |
| **Produk** | Nama produk yang terdampak |
| **Deskripsi** | Narasi singkat operasi |
| **Status** | SUCCESS / WARNING / ERROR |
| **Durasi** | Waktu eksekusi operasi (ms) |

---

## 7. Panduan REST API (untuk Developer)

**Base URL:** `http://localhost:8000/api/v1`

### Contoh: Mencari Produk
```http
GET /api/v1/products?q=Laptop&product_type=physical&per_page=5
```

### Contoh Response JSON:
```json
{
  "success": true,
  "message": "Daftar produk berhasil dimuat.",
  "data": [
    {
      "id": 1,
      "sku": "HW-142",
      "name": "Laptop Workstation Dell Precision 5680",
      "product_type": "physical",
      "price": "32000000.00",
      "stock": 5,
      "status": "active",
      "category": { "id": 1, "name": "Perangkat Komputasi" }
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 5,
    "total": 1,
    "last_page": 1
  }
}
```

### Response Headers Telemetri:
```
X-Response-Time-Ms : 3.82
X-Memory-Usage-MB  : 22.50
X-Query-Count      : 2
```

---

## 8. Pesan Error Umum & Solusi

| Pesan Error | Penyebab | Solusi |
|:---|:---|:---|
| *"SKU sudah digunakan produk lain"* | SKU duplikat | Gunakan SKU unik atau klik "Generate" |
| *"Field wajib tidak boleh kosong"* | Form tidak lengkap | Isi semua field bertanda `*` |
| *"Produk tidak ditemukan"* | ID tidak valid / sudah dihapus | Refresh halaman katalog |
| *"Stok tidak mencukupi"* | Pengurangan melebihi stok | Periksa jumlah stok saat ini |
