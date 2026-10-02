# DIREKTORI DOKUMENTASI LENGKAP UJI KOMPETENSI BNSP
## SKEMA: SENIOR PROGRAMMER (KKNI LEVEL 6)
### Modul: IT Inventory — Product Management Service

Berikut adalah daftar seluruh dokumen yang telah disusun sesuai dengan standar Kriteria Unjuk Kerja (KUK) BNSP dan Skema Sertifikasi Senior Programmer:

---

### 📂 Daftar Dokumen

| No | Dokumen | Kode Unit Kompetensi | Deskripsi Singkat |
|:---:|:---|:---|:---|
| **01** | [**01_DOKUMENTASI_TEKNIS.md**](./01_DOKUMENTASI_TEKNIS.md) | J.620100.049.01 | Dokumen spesifikasi teknis modul, analisis tools, diagram arsitektur Service-Repository, ERD, kontrak interface OOP, algoritma pencarian, dan REST API specs. |
| **02** | [**02_DOKUMENTASI_PENGGUNA.md**](./02_DOKUMENTASI_PENGGUNA.md) | J.620100.051.01 | Panduan manual operasional pengguna/staf kantor untuk registrasi aset via modal, filter multi-kriteria, edit stok, inspeksi audit log, dan troubleshooting error. |
| **03** | [**03_LAPORAN_PENGUJIAN.md**](./03_LAPORAN_PENGUJIAN.md) | J.620100.050.01 | Laporan hasil pengujian komprehensif: Integration Test (5 cases), System Test (8 cases), dan Stress/Load Test (200 requests, 238 RPS, 0% error). |
| **04** | [**04_ANALISIS_DAMPAK_DAN_CODE_REVIEW.md**](./04_ANALISIS_DAMPAK_DAN_CODE_REVIEW.md) | J.620100.023.01 & J.620100.048.01 | Checklist hasil code review (PSR-12, SOLID, Security) serta analisis dampak perubahan pada optimasi database composite indexing & implementasi UX modal. |
| **05** | [**05_PANDUAN_JAWABAN_ASESOR_FR_IA_04B.md**](./05_PANDUAN_JAWABAN_ASESOR_FR_IA_04B.md) | FR.IA.04B (Sesi Wawancara) | Kunci jawaban siap saji dan taktis untuk **16 pertanyaan wajib wawancara asesor** selama 30 menit sesi penilaian. |

---

### 🚀 Cara Menjalankan Uji Otomatis & Stress Test

```bash
# 1. Menjalankan seluruh Unit/Integration & System Test (14 tests passed)
php artisan test

# 2. Menjalankan Stress & Load Test (200 requests)
php artisan product:stress-test --requests=200

# 3. Menjalankan server aplikasi
php artisan serve
```

---

### 🌐 Akses Aplikasi & Slide Presentasi
- **Katalog & CRUD Inventaris:** `http://localhost:8000/products`
- **Dashboard Telemetri & Performa:** `http://localhost:8000/monitoring`
- **Log Aktivitas & Audit Trail:** `http://localhost:8000/activity-logs`
- **Slide Presentasi Web (HTML):** Buka berkas `presentation/index.html` di browser
