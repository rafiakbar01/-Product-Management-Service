<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Class ProductManagementSeeder
 * Seeder data master inventaris internal untuk Perusahaan Digital (IT Enterprise & Corporate Office).
 * Mendukung 3 tipe produk (physical, digital, service) sesuai instruksi tugas BNSP Senior Programmer.
 */
class ProductManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Categories Khusus Kebutuhan Kantor Perusahaan Digital
        $categoriesData = [
            [
                'name' => 'Perangkat Komputer & Workstation',
                'slug' => 'komputer-workstation',
                'description' => 'Laptop pengembang, PC workstation, monitor resolusi tinggi, dan periferal komputasi karyawan.',
            ],
            [
                'name' => 'Infrastruktur Jaringan & Server',
                'slug' => 'infrastruktur-server',
                'description' => 'Server rack enterprise, managed switch PoE, router gateway firewall, dan perangkat penyimpanan NAS.',
            ],
            [
                'name' => 'Lisensi Software & Cloud SaaS',
                'slug' => 'lisensi-software-saas',
                'description' => 'Aktivasi lisensi IDE developer, tool kolaborasi desain, productivity suite, dan cloud hosting.',
            ],
            [
                'name' => 'Layanan & Dukungan IT Enterprise',
                'slug' => 'layanan-dukungan-it',
                'description' => 'Kontrak pemeliharaan server bulanan, jasa penetration testing audit keamanan, dan konsultasi cloud DevOps.',
            ],
            [
                'name' => 'Fasilitas & Ergonomi Kantor',
                'slug' => 'fasilitas-ergonomi-kantor',
                'description' => 'Kursi kerja ergonomis standar kesehatan kerja, meja standing desk elektrik, dan perangkat video conference.',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Seed Master Data Produk Kebutuhan Perusahaan Digital
        $productsData = [
            // --- Kategori 1: Komputer & Workstation (Physical) ---
            [
                'category_id' => $categories['komputer-workstation']->id,
                'sku' => 'HW-LTP-001',
                'name' => 'MacBook Pro 16" M3 Max 36GB / 1TB SSD',
                'slug' => 'macbook-pro-16-m3-max-36gb-1tb',
                'description' => 'Laptop workstation performa tinggi standar engineer dan arsitek software dengan layar Liquid Retina XDR.',
                'product_type' => 'physical',
                'price' => 38500000,
                'cost_price' => 34000000,
                'stock' => 12,
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['komputer-workstation']->id,
                'sku' => 'HW-MNT-002',
                'name' => 'Monitor Dell UltraSharp 32" 4K UHD USB-C Hub',
                'slug' => 'monitor-dell-ultrasharp-32-4k-uhd',
                'description' => 'Display IPS Black 4K dengan akurasi 100% sRGB, 98% DCI-P3, dan integrasi 90W Power Delivery hub.',
                'product_type' => 'physical',
                'price' => 11800000,
                'cost_price' => 9500000,
                'stock' => 4, // LOW STOCK (< 5)
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['komputer-workstation']->id,
                'sku' => 'HW-PRF-003',
                'name' => 'Logitech MX Master 3S Wireless Mouse & Mechanical Mini Set',
                'slug' => 'logitech-mx-master-3s-mouse-keyboard-set',
                'description' => 'Bundle periferal produktivitas nirkabel dengan sensor 8K DPI silent click dan switch mekanik tactile.',
                'product_type' => 'physical',
                'price' => 3250000,
                'cost_price' => 2600000,
                'stock' => 25,
                'min_stock_alert' => 8,
                'status' => 'active',
            ],

            // --- Kategori 2: Infrastruktur Jaringan & Server (Physical) ---
            [
                'category_id' => $categories['infrastruktur-server']->id,
                'sku' => 'NET-SRV-001',
                'name' => 'Server Dell PowerEdge R760 2U Dual Intel Xeon 128GB RAM',
                'slug' => 'server-dell-poweredge-r760-2u-xeon',
                'description' => 'Server rack enterprise on-premise untuk virtualisasi Proxmox, staging Kubernetes, dan replikasi database.',
                'product_type' => 'physical',
                'price' => 85000000,
                'cost_price' => 72000000,
                'stock' => 2, // LOW STOCK (< 3)
                'min_stock_alert' => 3,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['infrastruktur-server']->id,
                'sku' => 'NET-SWT-002',
                'name' => 'Ubiquiti UniFi Switch Pro 24 PoE + Dream Machine SE Gateway',
                'slug' => 'ubiquiti-unifi-switch-pro-24-poe-udm-se',
                'description' => 'Core switch managed 24-Port Gigabit PoE+ dengan firewall gateway 10Gbps SFP+ dan kontroler terpusat.',
                'product_type' => 'physical',
                'price' => 19500000,
                'cost_price' => 16000000,
                'stock' => 6,
                'min_stock_alert' => 2,
                'status' => 'active',
            ],

            // --- Kategori 3: Lisensi Software & Cloud SaaS (Digital) ---
            [
                'category_id' => $categories['lisensi-software-saas']->id,
                'sku' => 'SW-IDE-001',
                'name' => 'Lisensi JetBrains All Products Pack Enterprise (1 Tahun)',
                'slug' => 'lisensi-jetbrains-all-products-pack-enterprise',
                'description' => 'Aktivasi lisensi tool coding suite (PhpStorm, IntelliJ, WebStorm, PyCharm, DataGrip) untuk engineer tim.',
                'product_type' => 'digital',
                'price' => 12500000,
                'cost_price' => 9800000,
                'stock' => 45,
                'min_stock_alert' => 10,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['lisensi-software-saas']->id,
                'sku' => 'SW-CLD-002',
                'name' => 'Langganan Google Workspace Enterprise Plus (Paket 10 User)',
                'slug' => 'google-workspace-enterprise-plus-10-user',
                'description' => 'Email domain perusahaan kustom, unlimited cloud vault storage, dan advanced DLP security compliance.',
                'product_type' => 'digital',
                'price' => 6800000,
                'cost_price' => 5500000,
                'stock' => 30,
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['lisensi-software-saas']->id,
                'sku' => 'SW-FGM-003',
                'name' => 'Lisensi Figma Enterprise Organization Seat (Annual)',
                'slug' => 'lisensi-figma-enterprise-organization-seat',
                'description' => 'Akses desain sistem kolaboratif, branching design file, dan SSO Okta integration untuk UI/UX tim.',
                'product_type' => 'digital',
                'price' => 8400000,
                'cost_price' => 7000000,
                'stock' => 15,
                'min_stock_alert' => 5,
                'status' => 'active',
            ],

            // --- Kategori 4: Layanan & Dukungan IT (Service) ---
            [
                'category_id' => $categories['layanan-dukungan-it']->id,
                'sku' => 'SRV-MNT-001',
                'name' => 'Kontrak Pemeliharaan Server & Database Bulanan (SLA 99.9%)',
                'slug' => 'kontrak-pemeliharaan-server-database-bulanan',
                'description' => 'Layanan monitoring 24/7, OS security patch berkala, backup drill mingguan, dan respon insiden < 15 menit.',
                'product_type' => 'service',
                'price' => 15000000,
                'cost_price' => 9000000,
                'stock' => 5,
                'min_stock_alert' => 2,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['layanan-dukungan-it']->id,
                'sku' => 'SRV-SEC-002',
                'name' => 'Paket Penetration Testing & Vulnerability Assessment Aplikasi Internal',
                'slug' => 'paket-penetration-testing-vulnerability-assessment',
                'description' => 'Audit celah keamanan OWASP Top 10, SAST/DAST testing, dan sertifikat laporan kepatuhan ISO 27001.',
                'product_type' => 'service',
                'price' => 22000000,
                'cost_price' => 14000000,
                'stock' => 1, // CRITICAL LOW STOCK
                'min_stock_alert' => 3,
                'status' => 'active',
            ],

            // --- Kategori 5: Fasilitas & Ergonomi Kantor (Physical) ---
            [
                'category_id' => $categories['fasilitas-ergonomi-kantor']->id,
                'sku' => 'OFF-CHR-001',
                'name' => 'Kursi Kerja Ergonomis Herman Miller Aeron Remastered',
                'slug' => 'kursi-kerja-ergonomis-herman-miller-aeron',
                'description' => 'Kursi kantor standar korporat dengan PostureFit SL lumbar support, 3D armrest, dan mesh Pellicle breathable.',
                'product_type' => 'physical',
                'price' => 21500000,
                'cost_price' => 17500000,
                'stock' => 8,
                'min_stock_alert' => 3,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['fasilitas-ergonomi-kantor']->id,
                'sku' => 'OFF-DSK-002',
                'name' => 'Meja Kerja Standing Desk Elektrik Dual Motor 160x80cm',
                'slug' => 'meja-kerja-standing-desk-elektrik-dual-motor',
                'description' => 'Meja kerja adjustable height motor ganda dengan 4 memory preset, anti-collision sensor, dan cable tray rapi.',
                'product_type' => 'physical',
                'price' => 6800000,
                'cost_price' => 5200000,
                'stock' => 0, // OUT OF STOCK
                'min_stock_alert' => 4,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['fasilitas-ergonomi-kantor']->id,
                'sku' => 'OFF-MTR-003',
                'name' => 'Polycom Studio 4K Video Bar & Speakerphone Conference System',
                'slug' => 'polycom-studio-4k-video-bar-conference',
                'description' => 'Perangkat all-in-one meeting room kamera 4K pintar dengan automatic voice tracking dan noise blocker AI.',
                'product_type' => 'physical',
                'price' => 16500000,
                'cost_price' => 13000000,
                'stock' => 3,
                'min_stock_alert' => 2,
                'status' => 'active',
            ],
        ];

        foreach ($productsData as $item) {
            $product = Product::updateOrCreate(['sku' => $item['sku']], $item);

            // Record initial seeding activity log
            ProductActivityLog::create([
                'product_id' => $product->id,
                'action' => 'CREATE',
                'description' => "Initial catalog entry: {$product->name} (SKU: {$product->sku})",
                'user_identifier' => 'System Admin / Seeder',
                'ip_address' => '127.0.0.1',
                'payload' => [
                    'sku' => $product->sku,
                    'type' => $product->product_type,
                    'stock' => $product->stock,
                    'price' => $product->price,
                ],
                'response_status' => 'SUCCESS',
                'execution_time_ms' => round(rand(20, 80) / 10, 2),
                'created_at' => now()->subMinutes(rand(15, 180)),
            ]);

            if ($product->isLowStock()) {
                ProductActivityLog::create([
                    'product_id' => $product->id,
                    'action' => 'LOW_STOCK_ALERT',
                    'description' => "Peringatan stok kritis: {$product->name} (Sisa: {$product->stock} unit, Ambang batas: {$product->min_stock_alert})",
                    'user_identifier' => 'SYSTEM_TELEMETRY',
                    'payload' => ['stock' => $product->stock, 'min_alert' => $product->min_stock_alert],
                    'response_status' => 'WARNING',
                    'execution_time_ms' => 1.8,
                    'created_at' => now()->subMinutes(rand(5, 60)),
                ]);
            }
        }
    }
}
