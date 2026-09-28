<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\Contracts\ProductServiceInterface;
use App\Exceptions\DuplicateSkuException;
use App\Exceptions\ProductNotFoundException;
use App\Models\Product;
use App\Models\ProductActivityLog;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Class ProductService
 * Implements ProductServiceInterface.
 * Orchestrates business rules, transactions, custom exceptions, and structured activity logging.
 * Fulfills:
 * - UK J.620100.010.02 (OOP & Interface Implementation)
 * - UK J.620100.011.02 (CRUD and Search Algorithms)
 * - UK J.620100.012.02 (Error Handling and Debugging)
 * - UK J.620100.013.01 (Subroutine / Modular Deconstruction)
 * - UK J.620100.046.01 (Fitur Logging Aplikasi)
 */
class ProductService implements ProductServiceInterface
{
    protected ProductRepositoryInterface $productRepo;

    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * List and search products with execution timing and search logging.
     */
    public function listProducts(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $startTime = microtime(true);

        $results = $this->productRepo->getAll($filters, $perPage);

        $durationMs = round((microtime(true) - $startTime) * 1000, 2);

        // If a search keyword is provided, log the search activity for behavioral analytics
        if (!empty($filters['q'])) {
            $this->recordLog(
                productId: null,
                action: 'SEARCH',
                description: "Pencarian produk dengan kata kunci: '{$filters['q']}'",
                payload: ['filters' => $filters, 'total_matched' => $results->total()],
                durationMs: $durationMs
            );
        }

        return $results;
    }

    /**
     * Retrieve single product with error handling.
     * Throws ProductNotFoundException if record does not exist.
     */
    public function getProductById(int $id): Product
    {
        $startTime = microtime(true);
        $product = $this->productRepo->findById($id);

        if (!$product) {
            $this->recordLog(
                productId: $id,
                action: 'ERROR',
                description: "Gagal menemukan produk dengan ID: {$id}",
                payload: ['requested_id' => $id],
                status: 'NOT_FOUND',
                durationMs: round((microtime(true) - $startTime) * 1000, 2)
            );

            throw new ProductNotFoundException("Produk dengan ID {$id} tidak ditemukan dalam sistem database.");
        }

        return $product;
    }

    /**
     * Create product wrapped in database transaction with validation and logging.
     */
    public function createProduct(array $data, ?string $userIdentifier = null, ?string $ip = null, ?string $userAgent = null): Product
    {
        $startTime = microtime(true);

        // Business rule 1: Slug auto generation if empty
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . strtolower(Str::random(5));
        }

        // Business rule 2: Check SKU uniqueness before insert
        $existing = $this->productRepo->findBySku($data['sku']);
        if ($existing) {
            throw new DuplicateSkuException("SKU '{$data['sku']}' telah digunakan oleh produk '{$existing->name}'.");
        }

        DB::beginTransaction();
        try {
            $product = $this->productRepo->create($data);

            $durationMs = round((microtime(true) - $startTime) * 1000, 2);

            // Check if initial stock is already below or at min_stock_alert
            $isLow = $product->isLowStock();

            $this->recordLog(
                productId: $product->id,
                action: 'CREATE',
                description: "Berhasil menambahkan produk baru '{$product->name}' (SKU: {$product->sku})",
                userIdentifier: $userIdentifier,
                ip: $ip,
                userAgent: $userAgent,
                payload: [
                    'sku' => $product->sku,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'is_low_stock' => $isLow
                ],
                status: 'SUCCESS',
                durationMs: $durationMs
            );

            if ($isLow) {
                $this->recordLog(
                    productId: $product->id,
                    action: 'LOW_STOCK_ALERT',
                    description: "Peringatan: Stok awal produk '{$product->name}' berada di batas kritis ({$product->stock} unit tersisa)!",
                    userIdentifier: 'SYSTEM_MONITOR',
                    payload: ['current_stock' => $product->stock, 'min_threshold' => $product->min_stock_alert],
                    status: 'WARNING'
                );
            }

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();

            $this->recordLog(
                productId: null,
                action: 'ERROR',
                description: "Gagal menyimpan produk baru: " . $e->getMessage(),
                userIdentifier: $userIdentifier,
                ip: $ip,
                payload: ['data' => $data, 'error' => $e->getMessage()],
                status: 'FAILED'
            );

            throw $e;
        }
    }

    /**
     * Update product details with transaction, diff check, and audit logging.
     */
    public function updateProduct(int $id, array $data, ?string $userIdentifier = null, ?string $ip = null, ?string $userAgent = null): Product
    {
        $startTime = microtime(true);
        $product = $this->getProductById($id);

        // Check SKU collision if SKU changed
        if (!empty($data['sku']) && $data['sku'] !== $product->sku) {
            $duplicate = $this->productRepo->findBySku($data['sku']);
            if ($duplicate && $duplicate->id !== $product->id) {
                throw new DuplicateSkuException("SKU '{$data['sku']}' telah digunakan oleh produk lain.");
            }
        }

        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . strtolower(Str::random(5));
        }

        $oldStock = $product->stock;

        DB::beginTransaction();
        try {
            $updatedProduct = $this->productRepo->update($id, $data);

            $durationMs = round((microtime(true) - $startTime) * 1000, 2);

            $this->recordLog(
                productId: $updatedProduct->id,
                action: 'UPDATE',
                description: "Memperbarui informasi produk '{$updatedProduct->name}' (ID: {$updatedProduct->id})",
                userIdentifier: $userIdentifier,
                ip: $ip,
                userAgent: $userAgent,
                payload: [
                    'updated_fields' => array_keys($data),
                    'stock_before' => $oldStock,
                    'stock_after' => $updatedProduct->stock,
                ],
                status: 'SUCCESS',
                durationMs: $durationMs
            );

            // Trigger alert if stock transition crossed alert threshold
            if ($updatedProduct->isLowStock()) {
                $this->recordLog(
                    productId: $updatedProduct->id,
                    action: 'LOW_STOCK_ALERT',
                    description: "Peringatan: Stok produk '{$updatedProduct->name}' menipis! Sisa {$updatedProduct->stock} unit (Batas min: {$updatedProduct->min_stock_alert}).",
                    userIdentifier: 'SYSTEM_MONITOR',
                    payload: ['stock' => $updatedProduct->stock, 'min_alert' => $updatedProduct->min_stock_alert],
                    status: 'WARNING'
                );
            }

            DB::commit();
            return $updatedProduct;
        } catch (Exception $e) {
            DB::rollBack();

            $this->recordLog(
                productId: $id,
                action: 'ERROR',
                description: "Gagal memperbarui produk ID {$id}: " . $e->getMessage(),
                userIdentifier: $userIdentifier,
                payload: ['error' => $e->getMessage()],
                status: 'FAILED'
            );

            throw $e;
        }
    }

    /**
     * Soft delete product with audit trail.
     */
    public function deleteProduct(int $id, ?string $userIdentifier = null, ?string $ip = null, ?string $userAgent = null): bool
    {
        $startTime = microtime(true);
        $product = $this->getProductById($id);

        DB::beginTransaction();
        try {
            $deleted = $this->productRepo->delete($id);

            $durationMs = round((microtime(true) - $startTime) * 1000, 2);

            $this->recordLog(
                productId: $id,
                action: 'DELETE',
                description: "Menghapus (soft delete) produk '{$product->name}' (SKU: {$product->sku})",
                userIdentifier: $userIdentifier,
                ip: $ip,
                userAgent: $userAgent,
                payload: ['deleted_product_id' => $id, 'sku' => $product->sku],
                status: 'SUCCESS',
                durationMs: $durationMs
            );

            DB::commit();
            return $deleted;
        } catch (Exception $e) {
            DB::rollBack();

            $this->recordLog(
                productId: $id,
                action: 'ERROR',
                description: "Gagal menghapus produk ID {$id}: " . $e->getMessage(),
                status: 'FAILED'
            );

            throw $e;
        }
    }

    /**
     * Get list of products with low stock for notification alerts.
     */
    public function getLowStockAlerts(int $limit = 5): Collection
    {
        return $this->productRepo->getLowStockProducts($limit);
    }

    /**
     * Aggregate statistics for management dashboard.
     */
    public function getDashboardMetrics(): array
    {
        return $this->productRepo->getStatistics();
    }

    /**
     * Helper Subroutine for Dual Logging:
     * 1. Monolog File System (`storage/logs/product-service.log`)
     * 2. Database Audit Trail (`product_activity_logs`)
     */
    protected function recordLog(
        ?int $productId,
        string $action,
        string $description,
        ?string $userIdentifier = 'Internal Assessor',
        ?string $ip = null,
        ?string $userAgent = null,
        ?array $payload = null,
        string $status = 'SUCCESS',
        float $durationMs = 0.0
    ): void {
        try {
            // 1. File Logger (Monolog)
            $logContext = [
                'action' => $action,
                'product_id' => $productId,
                'user' => $userIdentifier,
                'ip' => $ip,
                'duration_ms' => $durationMs,
                'payload' => $payload,
            ];

            if ($status === 'FAILED' || $status === 'ERROR') {
                Log::channel('product_service')->error($description, $logContext);
            } elseif ($status === 'WARNING') {
                Log::channel('product_service')->warning($description, $logContext);
            } else {
                Log::channel('product_service')->info($description, $logContext);
            }

            // 2. Database Activity Log
            ProductActivityLog::create([
                'product_id' => $productId,
                'action' => $action,
                'description' => $description,
                'user_identifier' => $userIdentifier ?: 'Internal Assessor',
                'ip_address' => $ip ?: request()->ip(),
                'user_agent' => $userAgent ?: request()->userAgent(),
                'payload' => $payload,
                'response_status' => $status,
                'execution_time_ms' => $durationMs,
                'created_at' => now(),
            ]);
        } catch (Exception $logEx) {
            // Fallback so application flow does not break if logging storage fails
            Log::error("Failed to write activity log: " . $logEx->getMessage());
        }
    }
}
