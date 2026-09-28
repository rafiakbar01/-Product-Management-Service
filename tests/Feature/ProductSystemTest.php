<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class ProductSystemTest
 * End-to-End System Testing of the Product Management Service.
 * Fulfills UK J.620100.017.01 (Menerapkan Pengujian Sistem).
 */
class ProductSystemTest extends TestCase
{
    use RefreshDatabase;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->category = Category::create([
            'name' => 'Gadget & Tech',
            'slug' => 'gadget-tech',
            'description' => 'Kategori Gadget',
            'is_active' => true,
        ]);
    }

    /**
     * Test web catalog displays successfully.
     */
    public function test_catalog_page_is_accessible_and_renders(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Pengelolaan Data Produk (CRUD)');
    }

    /**
     * Test product search algorithm via HTTP query.
     */
    public function test_product_search_algorithm(): void
    {
        Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SRCH-001',
            'name' => 'Smartphone Android Flagship',
            'slug' => 'smartphone-android-flagship',
            'price' => 12000000,
            'stock' => 10,
            'min_stock_alert' => 3,
            'status' => 'active',
            'product_type' => 'physical',
        ]);

        Product::create([
            'category_id' => $this->category->id,
            'sku' => 'SRCH-002',
            'name' => 'Kopi Robusta Lampung',
            'slug' => 'kopi-robusta-lampung',
            'price' => 50000,
            'stock' => 20,
            'min_stock_alert' => 5,
            'status' => 'active',
            'product_type' => 'physical',
        ]);

        // Search for 'Smartphone'
        $response = $this->get('/products?q=Smartphone');
        $response->assertStatus(200);
        $response->assertSee('Smartphone Android Flagship');
        $response->assertDontSee('Kopi Robusta Lampung');
    }

    /**
     * Test form validation when storing invalid product.
     */
    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->post('/products', []);
        $response->assertSessionHasErrors(['name', 'sku', 'category_id', 'price', 'stock']);
    }

    /**
     * Test full web creation flow.
     */
    public function test_can_create_product_via_web_form(): void
    {
        $response = $this->post('/products', [
            'category_id' => $this->category->id,
            'sku' => 'WEB-PRD-001',
            'name' => 'Web Camera Full HD 1080p',
            'product_type' => 'physical',
            'price' => 450000,
            'cost_price' => 300000,
            'stock' => 15,
            'min_stock_alert' => 5,
            'status' => 'active',
            'description' => 'Webcam untuk video conference',
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', ['sku' => 'WEB-PRD-001']);
    }

    /**
     * Test RESTful API endpoint.
     */
    public function test_api_returns_json_and_telemetry_headers(): void
    {
        $response = $this->getJson('/api/v1/products');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'data',
            'pagination' => ['current_page', 'last_page', 'total'],
        ]);

        // Telemetry Headers (X-Response-Time-Ms)
        $this->assertTrue($response->headers->has('X-Response-Time-Ms'));
        $this->assertTrue($response->headers->has('X-Memory-Usage-MB'));
    }

    /**
     * Test monitoring dashboard accessibility.
     */
    public function test_monitoring_dashboard_is_accessible(): void
    {
        $response = $this->get('/monitoring');
        $response->assertStatus(200);
        $response->assertSee('Monitoring & Evaluasi Performa Modul');
    }
}
