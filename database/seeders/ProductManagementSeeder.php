<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Categories
        $categoriesData = [
            ['name' => 'Elektronik & Gadget', 'slug' => 'elektronik-gadget', 'description' => 'Perangkat keras, smartphone, laptop, dan aksesoris teknologi.'],
            ['name' => 'Fashion & Pakaian', 'slug' => 'fashion-pakaian', 'description' => 'Busana pria, wanita, sepatu, dan aksesoris gaya hidup.'],
            ['name' => 'Kebutuhan Kantor', 'slug' => 'kebutuhan-kantor', 'description' => 'Alat tulis kantor, perlengkapan meeting, dan ergonomi meja kerja.'],
            ['name' => 'Layanan Digital & Lisensi', 'slug' => 'layanan-digital-lisensi', 'description' => 'Lisensi perangkat lunak SaaS, e-book, dan voucher digital.'],
            ['name' => 'Makanan & Minuman', 'slug' => 'makanan-minuman', 'description' => 'Konsumsi segar, kopi artisan, dan camilan kantor.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 2. Seed Initial Products
        $productsData = [
            [
                'category_id' => $categories['elektronik-gadget']->id,
                'sku' => 'ELC-LTP-001',
                'name' => 'Laptop Ultra Pro 15 inch M3',
                'slug' => 'laptop-ultra-pro-15-inch-m3',
                'description' => 'Laptop performa tinggi dengan RAM 32GB dan SSD 1TB untuk pengembang perangkat lunak.',
                'product_type' => 'physical',
                'price' => 24500000,
                'cost_price' => 20000000,
                'stock' => 12,
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['elektronik-gadget']->id,
                'sku' => 'ELC-MNT-002',
                'name' => 'Monitor 4K Curved 34 inch Ultrawide',
                'slug' => 'monitor-4k-curved-34-inch-ultrawide',
                'description' => 'Monitor bezel-less 144Hz dengan color accuracy 99% sRGB.',
                'product_type' => 'physical',
                'price' => 8750000,
                'cost_price' => 7000000,
                'stock' => 4, // LOW STOCK TRIGGER (< 5)
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['elektronik-gadget']->id,
                'sku' => 'ELC-MOU-003',
                'name' => 'Ergonomic Wireless Mouse Silent Click',
                'slug' => 'ergonomic-wireless-mouse-silent-click',
                'description' => 'Mouse ergonomis vertikal untuk mencegah cidera pergelangan tangan (RSI).',
                'product_type' => 'physical',
                'price' => 450000,
                'cost_price' => 300000,
                'stock' => 35,
                'min_stock_alert' => 10,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['layanan-digital-lisensi']->id,
                'sku' => 'SFT-IDE-001',
                'name' => 'Lisensi IDE Ultimate 1 Tahun',
                'slug' => 'lisensi-ide-ultimate-1-tahun',
                'description' => 'Aktivasi lisensi tool coding all-pack suite untuk 1 pengguna profesional.',
                'product_type' => 'digital',
                'price' => 3200000,
                'cost_price' => 2500000,
                'stock' => 50,
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['layanan-digital-lisensi']->id,
                'sku' => 'SRV-CSL-002',
                'name' => 'Paket Konsultasi Arsitektur Cloud (10 Jam)',
                'slug' => 'paket-konsultasi-arsitektur-cloud',
                'description' => 'Sesi mentoring dan review implementasi Microservices dan DevOps.',
                'product_type' => 'service',
                'price' => 15000000,
                'cost_price' => 10000000,
                'stock' => 3, // LOW STOCK TRIGGER (< 5)
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['kebutuhan-kantor']->id,
                'sku' => 'OFF-CHR-001',
                'name' => 'Kursi Ergonomis Mesh Lumbar Support',
                'slug' => 'kursi-ergonomis-mesh-lumbar-support',
                'description' => 'Kursi kerja breathable mesh dengan penyesuaian 3D armrest dan headrest.',
                'product_type' => 'physical',
                'price' => 2850000,
                'cost_price' => 2100000,
                'stock' => 8,
                'min_stock_alert' => 3,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['makanan-minuman']->id,
                'sku' => 'FNB-COF-001',
                'name' => 'Specialty Arabica Coffee Beans 1kg Single Origin',
                'slug' => 'specialty-arabica-coffee-beans-1kg',
                'description' => 'Biji kopi roasting medium freshly roasted untuk mesin espresso kantor.',
                'product_type' => 'physical',
                'price' => 280000,
                'cost_price' => 190000,
                'stock' => 1, // LOW STOCK TRIGGER (Critical 1 unit)
                'min_stock_alert' => 10,
                'status' => 'active',
            ],
            [
                'category_id' => $categories['fashion-pakaian']->id,
                'sku' => 'FSH-JKT-001',
                'name' => 'Tech Jacket Waterproof Windbreaker',
                'slug' => 'tech-jacket-waterproof-windbreaker',
                'description' => 'Jaket techwear multifungsi anti air dengan saku laptop internal.',
                'product_type' => 'physical',
                'price' => 750000,
                'cost_price' => 500000,
                'stock' => 0, // OUT OF STOCK
                'min_stock_alert' => 5,
                'status' => 'active',
            ],
        ];

        foreach ($productsData as $item) {
            $product = Product::firstOrCreate(['sku' => $item['sku']], $item);

            // Record initial seeding activity log
            ProductActivityLog::create([
                'product_id' => $product->id,
                'action' => 'CREATE',
                'description' => "Initial seeding produk: {$product->name}",
                'user_identifier' => 'System Seeder',
                'ip_address' => '127.0.0.1',
                'payload' => ['sku' => $product->sku, 'stock' => $product->stock],
                'response_status' => 'SUCCESS',
                'execution_time_ms' => 4.5,
                'created_at' => now()->subMinutes(rand(10, 120)),
            ]);

            if ($product->isLowStock()) {
                ProductActivityLog::create([
                    'product_id' => $product->id,
                    'action' => 'LOW_STOCK_ALERT',
                    'description' => "Peringatan stok menipis: {$product->name} (Sisa: {$product->stock})",
                    'user_identifier' => 'SYSTEM_MONITOR',
                    'payload' => ['stock' => $product->stock, 'min_alert' => $product->min_stock_alert],
                    'response_status' => 'WARNING',
                    'created_at' => now()->subMinutes(rand(5, 60)),
                ]);
            }
        }
    }
}
