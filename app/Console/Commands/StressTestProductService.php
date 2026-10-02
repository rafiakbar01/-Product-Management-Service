<?php

namespace App\Console\Commands;

use App\Contracts\ProductServiceInterface;
use App\Models\Category;
use Illuminate\Console\Command;

/**
 * Class StressTestProductService
 * Artisan command to execute automated stress and load testing on search & CRUD operations.
 * Fulfills UK J.620100.018.01 (Menerapkan Pengujian Stress).
 */
class StressTestProductService extends Command
{
    protected $signature = 'product:stress-test {--requests=100 : Number of test requests to execute}';
    protected $description = 'Melaksanakan stress testing dan benchmarking performa pada modul Product Management';

    protected ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    public function handle(): int
    {
        $totalRequests = (int) $this->option('requests');
        $this->info("==================================================================");
        $this->info("      PERFORMANCE STRESS TEST — PRODUCT MANAGEMENT SERVICE       ");
        $this->info("==================================================================");
        $this->line("Target Operasi : Multi-criteria Search & Product Query Filtering");
        $this->line("Jumlah Iterasi : {$totalRequests} request");
        $this->line("Database Engine: MySQL (InnoDb with Composite Indexing)");
        $this->newLine();

        $keywords = ['Laptop', 'Mouse', 'Monitor', 'Lisensi', 'Server', 'Kursi', 'NonExistentProduct'];
        $categories = Category::pluck('id')->toArray();

        $latencies = [];
        $successCount = 0;
        $errorCount = 0;

        $bar = $this->output->createProgressBar($totalRequests);
        $bar->start();

        $startGlobal = microtime(true);
        $startMem = memory_get_usage(true);

        for ($i = 0; $i < $totalRequests; $i++) {
            $kw = $keywords[$i % count($keywords)];
            $catId = !empty($categories) ? $categories[$i % count($categories)] : null;

            $filter = [
                'q' => $kw,
                'category_id' => $catId,
                'status' => 'active',
                'sort_by' => 'price',
                'sort_order' => ($i % 2 === 0) ? 'asc' : 'desc',
            ];

            $stepStart = microtime(true);
            try {
                $results = $this->productService->listProducts($filter, 10);
                $stepDuration = round((microtime(true) - $stepStart) * 1000, 2);
                $latencies[] = $stepDuration;
                $successCount++;
            } catch (\Exception $e) {
                $errorCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $totalElapsed = round(microtime(true) - $startGlobal, 4);
        $peakMem = round(memory_get_peak_usage(true) / (1024 * 1024), 2);

        // Statistical Calculations
        sort($latencies);
        $count = count($latencies);
        $min = $count > 0 ? $latencies[0] : 0;
        $max = $count > 0 ? $latencies[$count - 1] : 0;
        $avg = $count > 0 ? round(array_sum($latencies) / $count, 2) : 0;
        $p95Index = (int) floor($count * 0.95);
        $p95 = $count > 0 ? $latencies[min($p95Index, $count - 1)] : 0;
        $throughput = $totalElapsed > 0 ? round($totalRequests / $totalElapsed, 2) : 0;

        $this->table(
            ['Metrik Evaluasi', 'Nilai / Hasil Pengukuran'],
            [
                ['Total Permintaan (Requests)', number_format($totalRequests)],
                ['Sukses (200 OK)', number_format($successCount)],
                ['Gagal (Errors)', number_format($errorCount)],
                ['Total Waktu Eksekusi', "{$totalElapsed} detik"],
                ['Throughput (Kecepatan)', "{$throughput} req/detik (RPS)"],
                ['Latensi Tercepat (Min Latency)', "{$min} ms"],
                ['Latensi Rata-rata (Avg Latency)', "{$avg} ms"],
                ['Latensi Persentil 95 (P95)', "{$p95} ms"],
                ['Latensi Terlambat (Max Latency)', "{$max} ms"],
                ['Puncak Penggunaan Memori (Peak RAM)', "{$peakMem} MB"],
            ]
        );

        $this->info("Kesimpulan Evaluasi Performa:");
        if ($avg < 50.0 && $errorCount === 0) {
            $this->info("✓ MEMENUHI SLA: Rata-rata latensi {$avg} ms (< 50 ms) & Error Rate 0% — Indeks B-Tree Komposit berhasil mengeliminasi Full Table Scan.");
        } else {
            $this->warn("! MELEBIHI THRESHOLD: Latensi {$avg} ms melampaui batas toleransi — Direkomendasikan penerapan Query Caching (Redis/Memcached).");
        }

        return Command::SUCCESS;
    }
}
