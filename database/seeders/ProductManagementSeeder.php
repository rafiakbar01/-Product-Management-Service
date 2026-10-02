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
 * Menghasilkan 100 variasi produk dengan tipe physical, digital, dan service.
 */
class ProductManagementSeeder extends Seeder
{
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

        // 2. Daftar Template untuk Generate 100 Produk Variasi
        $categoryKeys = array_keys($categories);
        
        $adjectives = ['Pro', 'Enterprise', 'Ultra', 'Advanced', 'Max', 'Prime', 'Cloud', 'Secure', 'Core', 'Extreme', 'Smart', 'Global', 'Flex', 'Optima', 'NextGen'];
        $nouns = [
            'komputer-workstation' => ['Laptop Developer', 'Mini PC Server', 'Display Monitor', 'Workstation GPU', 'Tablet Grafis', 'External SSD Enclosure', 'Docking Station USB-C', 'Mechanical Keyboard', 'Wireless Mouse Set', 'Webcam 4K AI'],
            'infrastruktur-server' => ['Rackmount Server 2U', 'Managed Switch 24P', 'Router Firewall 10G', 'NAS Storage 8-Bay', 'Access Point Wi-Fi 7', 'UPS Enterprise 3000VA', 'Patch Panel Cat6A', 'SFP+ Transceiver Module', 'KVM Console Drawer', 'PDU Power Distribution'],
            'lisensi-software-saas' => ['Lisensi IDE Suite', 'Cloud Workspace Seat', 'Design Collaboration Seat', 'CI/CD Pipeline Runner', 'Security Scanner Annual', 'Database Monitoring Tool', 'Container Registry Tier', 'API Gateway License', 'SSL Wildcard Certificate', 'VPN Enterprise Seat'],
            'layanan-dukungan-it' => ['SLA Server Maintenance', 'Penetration Testing Audit', 'Cloud Migration Consulting', 'SOC 2 Compliance Review', 'Disaster Recovery Drill', 'Network Architecture Audit', 'DevOps Training Session', 'Incident Response Retainer', 'Code Quality Review', 'Load Testing Service'],
            'fasilitas-ergonomi-kantor' => ['Kursi Ergonomis Mesh', 'Standing Desk Elektrik', 'Video Bar Conference', 'Dual Monitor Arm', 'Cable Management Tray', 'LED Desk Lamp Smart', 'Footrest Ergonomic', 'Acoustic Desk Divider', 'Whiteboard Mobile Magnetic', 'Meeting Pod Acoustic']
        ];

        $productTypes = [
            'komputer-workstation' => 'physical',
            'infrastruktur-server' => 'physical',
            'lisensi-software-saas' => 'digital',
            'layanan-dukungan-it' => 'service',
            'fasilitas-ergonomi-kantor' => 'physical',
        ];

        $prefixes = [
            'komputer-workstation' => 'HW-WK',
            'infrastruktur-server' => 'NET-SR',
            'lisensi-software-saas' => 'SW-LIC',
            'layanan-dukungan-it' => 'SRV-IT',
            'fasilitas-ergonomi-kantor' => 'OFF-FAC',
        ];

        $generatedProducts = [];
        $counter = 1;

        foreach ($categoriesData as $catData) {
            $catSlug = $catData['slug'];
            $catId = $categories[$catSlug]->id;
            $catNouns = $nouns[$catSlug];
            $type = $productTypes[$catSlug];
            $prefix = $prefixes[$catSlug];

            // Buat 20 produk per kategori (total 5 kategori * 20 = 100 produk)
            for ($i = 1; $i <= 20; $i++) {
                $adj = $adjectives[array_rand($adjectives)];
                $noun = $catNouns[array_rand($catNouns)];
                $name = "{$noun} {$adj} v" . rand(1, 3) . "." . rand(0, 9);
                $sku = sprintf("%s-%03d", $prefix, $counter);
                $slug = Str::slug($name) . '-' . $counter;
                
                $price = rand(5, 500) * 100000; // 500rb s/d 50 juta
                $stock = rand(0, 50);
                $minAlert = rand(3, 8);
                $status = (rand(1, 20) === 1) ? 'inactive' : 'active';

                $generatedProducts[] = [
                    'category_id' => $catId,
                    'sku' => $sku,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => "Spesifikasi enterprise untuk {$name}. Dirancang untuk keandalan operasional tinggi dan performa optimal.",
                    'product_type' => $type,
                    'price' => $price,
                    'stock' => $stock,
                    'min_stock_alert' => $minAlert,
                    'status' => $status,
                ];

                $counter++;
            }
        }

        foreach ($generatedProducts as $item) {
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
                'created_at' => now()->subMinutes(rand(15, 1800)),
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
                    'created_at' => now()->subMinutes(rand(5, 300)),
                ]);
            }
        }
    }
}
