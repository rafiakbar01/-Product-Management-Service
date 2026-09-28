<?php

namespace Tests\Feature;

use App\Contracts\ProductServiceInterface;
use App\Exceptions\DuplicateSkuException;
use App\Exceptions\ProductNotFoundException;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ProductIntegrationTest
 * Tests integration across Presentation, Service Layer, Repository, and Database.
 * Fulfills UK J.620100.016.01 (Menerapkan Pengujian Integrasi).
 */
class ProductIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected ProductServiceInterface $productService;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->productService = app(ProductServiceInterface::class);

        $this->category = Category::create([
            'name' => 'Elektronik Test',
            'slug' => 'elektronik-test',
            'description' => 'Kategori untuk testing integrasi',
            'is_active' => true,
        ]);
    }

    /**
     * Test successful creation of product and verify audit trail log creation.
     */
    public function test_can_create_product_and_generates_activity_log(): void
    {
        $payload = [
            'category_id' => $this->category->id,
            'sku' => 'TEST-SKU-001',
            'name' => 'Keyboard Mechanical Gaming',
            'slug' => 'keyboard-mechanical-gaming',
            'description' => 'Keyboard switch biru tactile feedback',
            'product_type' => 'physical',
            'price' => 750000,
            'cost_price' => 500000,
            'stock' => 20,
            'min_stock_alert' => 5,
            'status' => 'active',
        ];

        $product = $this->productService->createProduct($payload, 'Tester', '127.0.0.1');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sku' => 'TEST-SKU-001',
            'name' => 'Keyboard Mechanical Gaming',
        ]);

        $this->assertDatabaseHas('product_activity_logs', [
            'product_id' => $product->id,
            'action' => 'CREATE',
            'response_status' => 'SUCCESS',
        ]);
    }

    /**
     * Test duplicate SKU prevention (business rule & custom exception).
     */
    public function test_cannot_create_product_with_duplicate_sku(): void
    {
        $payload = [
            'category_id' => $this->category->id,
            'sku' => 'TEST-DUP-001',
            'name' => 'Produk Asli',
            'price' => 100000,
            'stock' => 10,
            'min_stock_alert' => 3,
            'status' => 'active',
            'product_type' => 'physical',
        ];

        $this->productService->createProduct($payload);

        $this->expectException(DuplicateSkuException::class);
        $this->productService->createProduct($payload);
    }

    /**
     * Test low stock alert triggering upon creation.
     */
    public function test_triggers_low_stock_alert_when_stock_is_below_min(): void
    {
        $payload = [
            'category_id' => $this->category->id,
            'sku' => 'TEST-LOW-001',
            'name' => 'Produk Stok Kritis',
            'price' => 50000,
            'stock' => 2, // Less than min_stock_alert (5)
            'min_stock_alert' => 5,
            'status' => 'active',
            'product_type' => 'physical',
        ];

        $product = $this->productService->createProduct($payload);

        $this->assertDatabaseHas('product_activity_logs', [
            'product_id' => $product->id,
            'action' => 'LOW_STOCK_ALERT',
            'response_status' => 'WARNING',
        ]);
    }

    /**
     * Test retrieval throws ProductNotFoundException when ID does not exist.
     */
    public function test_throws_exception_when_product_not_found(): void
    {
        $this->expectException(ProductNotFoundException::class);
        $this->productService->getProductById(99999);
    }

    /**
     * Test update and soft delete operations.
     */
    public function test_can_update_and_soft_delete_product(): void
    {
        $product = $this->productService->createProduct([
            'category_id' => $this->category->id,
            'sku' => 'TEST-DEL-001',
            'name' => 'Barang Untuk Dihapus',
            'price' => 25000,
            'stock' => 15,
            'min_stock_alert' => 2,
            'status' => 'active',
            'product_type' => 'physical',
        ]);

        // Update
        $updated = $this->productService->updateProduct($product->id, [
            'name' => 'Barang Diubah Nama',
            'price' => 30000,
            'stock' => 15,
            'min_stock_alert' => 2,
            'category_id' => $this->category->id,
            'product_type' => 'physical',
            'status' => 'active',
            'sku' => 'TEST-DEL-001',
        ]);

        $this->assertEquals('Barang Diubah Nama', $updated->name);

        // Soft Delete
        $deleted = $this->productService->deleteProduct($product->id);
        $this->assertTrue($deleted);

        // Verify soft delete in DB
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
}
