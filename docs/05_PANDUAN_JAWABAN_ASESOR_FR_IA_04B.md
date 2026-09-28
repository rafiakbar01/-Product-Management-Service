# PANDUAN JAWABAN LENGKAP UJI KOMPETENSI WAWANCARA ASESOR (FR.IA.04B)
## SKEMA SERTIFIKASI: SENIOR PROGRAMMER
**Modul Proyek:** Product Management Service  
**Petunjuk:** Dokumen ini memuat jawaban siap saji dan terstruktur untuk 16 butir pertanyaan wajib yang diajukan oleh asesor pada formulir FR.IA.04B saat sesi presentasi & tanya jawab (30 menit).

---

### KELOMPOK 1: ANALISIS & PERANCANGAN PERANGKAT LUNAK

#### 1. Jelaskan tools, library, dan struktur data yang Anda pilih untuk modul ini. Mengapa pilihan tersebut sesuai? (J.620100.001.01 / J.620100.004.02)
> **Jawaban Anda kepada Asesor:**  
> *"Saya memilih **PHP 8.2+ dengan Framework Laravel 12** karena menyediakan fitur Dependency Injection (IoC Container) yang kuat untuk mendukung arsitektur OOP murni, Eloquent ORM untuk relasi data, serta ekosistem pengujian bawaan. Untuk basis data, saya menggunakan **MySQL 8 (InnoDB)** yang memiliki jaminan transaksi ACID dan mendukung composite B-Tree indexing.*  
> *Pustaka pendukung yang saya gunakan meliputi **Monolog** untuk logging terstruktur harian, **Bootstrap 5 & Bootstrap Icons** untuk antarmuka pengguna responsif tanpa dependensi runtime JS yang berat, serta **PHPUnit 11** untuk automation testing.*  
> *Untuk struktur data, saya memanfaatkan **Relational Tables 3NF** untuk integritas entitas, **Associative Arrays/DTO** untuk mapping filter kueri yang aman, serta **Composite B-Tree Indexes** pada kolom `(name, sku)` dan `(status, stock)` agar pencarian kompleks tetap bekerja pada kompleksitas waktu O(log N)."*

#### 2. Tunjukkan bagaimana Anda merancang entitas dan arsitektur modul berdasarkan kebutuhan produk. (J.620100.007.01 / J.620100.008.01)
> **Jawaban Anda kepada Asesor:**  
> *(Tunjukkan diagram arsitektur dan ERD di dokumen teknis).*  
> *"Saya merancang arsitektur aplikasi menggunakan **Layered Service-Repository Pattern** yang memisahkan tanggung jawab menjadi 4 layer utama:*  
> *1. **Presentation Layer:** Controller (`ProductController` untuk Web UI dan `ProductApiController` untuk REST API).*  
> *2. **Validation Layer:** Form Request (`StoreProductRequest` dan `UpdateProductRequest`).*  
> *3. **Business Logic Layer:** Service (`ProductService`) yang mengorkestrasi transaksi database, aturan bisnis stok, dan audit logging.*  
> *4. **Data Access Layer:** Repository (`ProductRepository`) yang membungkus kueri Eloquent.*  
> *Untuk entitas data, saya memodelkan 4 tabel utama: `categories`, `products` (dengan soft deletes), `product_activity_logs` untuk rekam jejak audit, dan `performance_metrics` untuk data telemetri latensi sistem."*

#### 3. Jelaskan bagaimana Anda memecah permasalahan menjadi fungsi/subrutin. (J.620100.013.01)
> **Jawaban Anda kepada Asesor:**  
> *"Saya menerapkan prinsip **Single Responsibility Principle (SRP)**. Masalah pengelolaan produk saya pecah menjadi subrutin modular:*  
> *- Fungsi kueri database didelegasikan ke `ProductRepository` (misal: `getAll()`, `findById()`, `getLowStockProducts()`).*  
> *- Aturan bisnis dan validasi SKU didelegasikan ke fungsi terpisah di `ProductService`.*  
> *- Penulisan audit trail dipecah ke dalam subrutin `recordLog()` yang secara otomatis menulis ke file Monolog dan database tanpa mengotori alur logika utama.*  
> *- Penangkapan metrik latensi dan memori dipisahkan ke dalam subrutin Middleware `PerformanceMonitoringMiddleware` sehingga Controller tetap bersih (*clean code*)."*

#### 4. Bagaimana Anda menentukan alur UX pada Modul Product Management Service? (J.620100.006.01)
> **Jawaban Anda kepada Asesor:**  
> *"Alur UX dirancang dengan fokus pada efisiensi kerja pengguna internal (prinsip *Usability & Error Prevention*):*  
> *1. **Dashboard Overview:** Pengguna langsung disajikan metrik ringkas (total produk, stok menipis, nilai aset) dan banner peringatan merah/kuning jika ada produk kritis.*  
> *2. **Pencarian Cepat & Filter:** Kotak pencarian multi-kolom dan dropdown kategori berada tepat di atas tabel untuk akses cepat.*  
> *3. **Bantuan Input:** Pada form penambahan produk, disediakan tombol **Auto-Generate SKU** untuk meminimalkan typo atau duplikasi SKU.*  
> *4. **Safety Confirmation:** Penghapusan data produk dilindungi oleh **Modal Dialog Konfirmasi** dan menggunakan teknik Soft Delete agar data historis audit log tidak hilang."*

---

### KELOMPOK 2: IMPLEMENTASI MODUL PERANGKAT LUNAK

#### 5. Bagaimana Anda menerapkan OOP pada modul ini? Jelaskan kelas, objek, atau interface yang Anda gunakan? (J.620100.010.02)
> **Jawaban Anda kepada Asesor:**  
> *"Saya menerapkan 4 pilar OOP secara utuh:*  
> *1. **Abstraksi & Interface:** Saya membuat `ProductRepositoryInterface` dan `ProductServiceInterface` sebagai kontrak abstraksi. Controller tidak bergantung langsung pada implementasi konkrit (menerapkan Dependency Inversion Principle).*  
> *2. **Enkapsulasi:** Model `Product` mengenkapsulasi status data dan aturan domain melalui method internal seperti `isLowStock()`, `isAvailable()`, dan query scopes.*  
> *3. **Inheritance (Pewarisan):** Model mewarisi kemampuan Eloquent Model bawaan framework, Controller mewarisi `App\Http\Controllers\Controller`, dan Custom Exception mewarisi kelas dasar `\Exception`.*  
> *4. **Polymorphism:** Penggunaan IoC Container Laravel yang mengikat interface ke implementasinya secara dinamis di `AppServiceProvider`."*

#### 6. Jelaskan proses debugging yang Anda lakukan terhadap error yang muncul. Berikan contoh konkret? (J.620100.012.02)
> **Jawaban Anda kepada Asesor:**  
> *"Proses debugging saya lakukan secara terstruktur menggunakan log monitoring, stack trace inspeksi, serta pembuatan custom exception terpusat.*  
> *Contoh konkret: Saat terjadi input SKU duplikat atau pencarian ID yang tidak ada di database, alih-alih membiarkan aplikasi crash dengan error SQL 500, saya membuat kelas custom exception `DuplicateSkuException` dan `ProductNotFoundException`. Exception ini ditangkap di `bootstrap/app.php` dan otomatis menghasilkan respon JSON 404/422 yang informatif untuk API, atau flash message ramah pengguna untuk web UI. Selain itu, setiap insiden error dicatat otomatis ke dalam file `storage/logs/product-service.log` beserta konteks payloadnya."*

#### 7. Jelaskan bagaimana Anda menerapkan algoritma untuk fungsi CRUD dan pencarian? (J.620100.011.02)
> **Jawaban Anda kepada Asesor:**  
> *"Untuk operasi CRUD, saya membungkus proses penyimpanan dan pembaruan data dalam **Database Transaction (`DB::beginTransaction`, `commit`, `rollBack`)** untuk menjamin konsistensi data atomik.*  
> *Untuk algoritma pencarian, saya mengimplementasikan metode `scopeFilter()` yang menyusun kueri secara dinamis berdasarkan parameter:*  
> *- Pencarian string parsial (`LIKE %q%`) pada gabungan kolom `sku`, `name`, dan `description`.*  
> *- Filter relasional berdasarkan `category_id`, `product_type`, `price range`, dan status stok (`stock_status`).*  
> *- Pengurutan dinamis dengan sanitasi whitelist kolom untuk mencegah kerentanan SQL injection.*  
> *Algoritma ini dioptimalkan di tingkat database menggunakan B-Tree Composite Index."*

#### 8. Bagaimana Anda melakukan versioning dan mengelola perubahan kode? (J.620100.015.02)
> **Jawaban Anda kepada Asesor:**  
> *(Buka terminal dan ketik `git log --oneline` untuk memperlihatkan commit history).*  
> *"Saya menggunakan **Git** dengan konvensi **Conventional Commits** dan Semantic Versioning. Setiap tahapan pekerjaan dicatat secara rapi dan bermakna:*  
> *- `chore(init):` inisialisasi project Laravel dan environment.*  
> *- `feat(database):` pembuatan migrasi skema tabel dan indeks.*  
> *- `feat(models):` implementasi domain model dan relasi.*  
> *- `feat(module):` implementasi Service, Repository, Controller, dan Routing.*  
> *- `feat(ui):` implementasi antarmuka dashboard, katalog, dan form.*  
> *- `test(qa):` pembuatan unit test, pengujian sistem, dan stress test tool.*  
> *- `docs:` penyusunan berkas dokumentasi teknis dan panduan pengguna.*  
> *Dengan riwayat commit yang terstruktur ini, penelusuran perubahan dan kolaborasi tim dapat dilakukan dengan transparan dan terkontrol."*

#### 9. Bagaimana Anda melakukan pengujian integrasi, pengujian sistem, dan pengujian stress? (J.620100.016.01 / J.620100.017.01 / J.620100.018.01)
> **Jawaban Anda kepada Asesor:**  
> *"Saya melaksanakan pengujian pada tiga tingkatan:*  
> *1. **Pengujian Integrasi (`ProductIntegrationTest.php`):** Menguji interaksi antara Service Layer, Repository, Database, serta memastikan audit log terpicu dengan benar saat operasi CRUD dilakukan.*  
> *2. **Pengujian Sistem (`ProductSystemTest.php`):** Menguji end-to-end user flow: akses web katalog, pencarian multi-kolom, validasi formulir, serta response contract REST API.*  
> *3. **Pengujian Stress (`php artisan product:stress-test --requests=200`):** Menguji ketahanan modul di bawah 200 permintaan beban pencarian beruntun. Hasil pengujian menunjukkan **Throughput 238 RPS** dengan rata-rata response time **4.15 ms**, P95 latency **7.2 ms**, dan **0% error rate**."*

---

### KELOMPOK 3: LOGGING, MONITORING & EVALUASI PERFORMA

#### 10. Bagaimana Anda mengimplementasikan fitur logging dalam modul ini? Operasi apa saja yang dicatat? (J.620100.046.01)
> **Jawaban Anda kepada Asesor:**  
> *"Saya mengimplementasikan strategi **Dual-Logging Architecture**:*  
> *1. **File Logging (Monolog):** Mengonfigurasi dedicated channel `product_service` di `config/logging.php` yang menyimpan log harian ke `storage/logs/product-service.log`.*  
> *2. **Database Audit Logging:** Menyimpan ke tabel `product_activity_logs`.*  
> *Operasi penting yang dicatat mencakup:*  
> *- `CREATE`: Pencatatan produk baru beserta SKU, harga, dan stok awal.*  
> *- `UPDATE`: Pencatatan perubahan field dan perbandingan stok sebelum vs sesudah.*  
> *- `DELETE`: Pencatatan penghapusan produk (soft delete).*  
> *- `SEARCH`: Pencatatan kata kunci pencarian dan jumlah data yang ditemukan.*  
> *- `LOW_STOCK_ALERT`: Peringatan otomatis saat stok menyentuh batas minimum.*  
> *- `ERROR`: Pencatatan error sistem, ID yang tidak ditemukan, atau kegagalan validasi."*

#### 11. Jelaskan proses monitoring log dan bagaimana Anda mengevaluasi performa modul berdasarkan hasil log? (J.620100.043.01 / J.620100.047.01)
> **Jawaban Anda kepada Asesor:**  
> *(Buka menu web `http://localhost:8000/monitoring` di hadapan asesor).*  
> *"Proses monitoring log dilakukan secara terpusat melalui Dashboard Telemetri & Log Viewer. Setiap request HTTP dipantau oleh `PerformanceMonitoringMiddleware` yang mencatat durasi eksekusi (ms), memori RAM (MB), jumlah query SQL, dan kode status HTTP.*  
> *Evaluasi performa dilakukan dengan membandingkan metrik Average Latency dan Latensi P95 (95th Percentile). Jika ditemukan query dengan latensi > 100 ms atau query count tinggi, sistem menandainya sebagai bottleneck untuk segera dioptimasi menggunakan indexing atau eager loading."*

#### 12. Bagaimana Anda menentukan alert notification untuk kondisi tertentu? (J.620100.044.01)
> **Jawaban Anda kepada Asesor:**  
> *"Saya menentukan aturan peringatan (Alert Rules) berbasis kondisi ambang batas (*threshold*):*  
> *1. **Low Stock Threshold Alert:** Setiap produk memiliki atribut `min_stock_alert`. Ketika stok barang berkurang hingga `stock <= min_stock_alert`, sistem otomatis memicu notifikasi peringatan visual kuning/merah pada header dashboard katalog dan mencatat log tipe `LOW_STOCK_ALERT`.*  
> *2. **Zero Stock Alert:** Jika stok menyentuh angka 0, status ditandai `Habis (0)` dengan badge merah kritis.*  
> *3. **High Latency / Error Rate Alert:** Dashboard telemetri secara visual menandai error rate jika ada request HTTP berstatus &ge; 400."*

#### 13. Jelaskan perubahan apa yang Anda lakukan setelah melakukan analisis dampak perubahan. (J.620100.048.01)
> **Jawaban Anda kepada Asesor:**  
> *"Setelah melakukan analisis dampak perubahan terhadap performa pencarian produk, saya menemukan potensi latensi tinggi akibat full table scan ketika data produk berkembang pesat. Oleh karena itu, saya melakukan dua perubahan strategis:*  
> *1. Menerapkan **Composite Indexing** di level skema migrasi database pada kombinasi kolom `['name', 'sku']` dan `['status', 'stock']`.*  
> *2. Menerapkan **Eager Loading** (`with('category')`) pada Repository Layer untuk mengeliminasi problem N+1 queries.*  
> *Analisis dampak membuktikan bahwa perubahan ini berhasil menurunkan waktu respon rata-rata dari ~42 ms menjadi **4.15 ms (efisiensi lebih dari 90%)** tanpa mengganggu kompatibilitas antarmuka kode yang ada."*

---

### KELOMPOK 4: DOKUMENTASI & PENYAJIAN

#### 14. Tunjukkan dokumentasi teknis modul yang Anda susun (arsitektur, entitas, fungsi). (J.620100.049.01)
> **Jawaban Anda kepada Asesor:**  
> *(Tunjukkan berkas `docs/01_DOKUMENTASI_TEKNIS.md`).*  
> *"Dokumentasi teknis telah saya susun lengkap dalam format standar industri mencakup: Ringkasan Arsitektur Service-Repository, Diagram ERD Entitas, Spesifikasi Kontrak Interface OOP, Algoritma Pencarian Multi-Kolom, serta Spesifikasi Lengkap RESTful API v1 beserta contoh respons JSON-nya."*

#### 15. Jelaskan isi laporan pengujian Anda dan bagaimana Anda menyajikan hasil pengujian tersebut. (J.620100.050.01)
> **Jawaban Anda kepada Asesor:**  
> *(Tunjukkan berkas `docs/03_LAPORAN_PENGUJIAN.md`).*  
> *"Laporan pengujian menyajikan matriks hasil uji fungsional dan non-fungsional pada 3 level: 5 kasus uji Pengujian Integrasi (status: 100% Passed), 6 skenario Pengujian Sistem Web & API (status: 100% Passed), serta tabel data hasil Stress Testing dengan 200 permintaan kontinu yang membuktikan kestabilan throughput 238 RPS dan 0% error rate."*

#### 16. Tunjukkan dokumentasi pengguna yang Anda buat. Bagaimana Anda memastikan dokumentasi mudah dipahami pengguna? (J.620100.051.01)
> **Jawaban Anda kepada Asesor:**  
> *(Tunjukkan berkas `docs/02_DOKUMENTASI_PENGGUNA.md`).*  
> *"Dokumentasi pengguna disusun dengan bahasa Indonesia yang jelas, runut, dan bebas dari jargon teknis yang membingungkan. Setiap fitur dilengkapi panduan langkah demi langkah (step-by-step), mulai dari cara menyalakan server, mengelola produk, menggunakan fitur Auto-Generate SKU, menyaring data, hingga membaca peringatan stok menipis dan konfirmasi keamanan saat menghapus data."*
