<?php

namespace App\Http\Controllers\Api;

use App\Contracts\ProductServiceInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProductApiController
 * RESTful API endpoints for the Product Management Service micro-module.
 * Provides JSON payloads with telemetry response headers.
 */
class ProductApiController extends Controller
{
    protected ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Search and list products via API.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only([
            'q',
            'category_id',
            'product_type',
            'status',
            'stock_status',
            'min_price',
            'max_price',
            'sort_by',
            'sort_order',
        ]);

        $perPage = (int) $request->input('per_page', 10);
        $products = $this->productService->listProducts($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar produk berhasil dimuat.',
            'data' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    /**
     * Store new product via API.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->createProduct(
            data: $request->validated(),
            userIdentifier: 'API Client',
            ip: $request->ip(),
            userAgent: $request->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dibuat.',
            'data' => $product,
        ], 201);
    }

    /**
     * Get single product detail via API.
     */
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail produk berhasil ditemukan.',
            'data' => $product,
        ]);
    }

    /**
     * Update product via API.
     */
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->updateProduct(
            id: $id,
            data: $request->validated(),
            userIdentifier: 'API Client',
            ip: $request->ip(),
            userAgent: $request->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Data produk berhasil diperbarui.',
            'data' => $product,
        ]);
    }

    /**
     * Delete product via API.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->productService->deleteProduct(
            id: $id,
            userIdentifier: 'API Client',
            ip: $request->ip(),
            userAgent: $request->userAgent()
        );

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus (soft delete).',
        ]);
    }

    /**
     * Get inventory dashboard statistics via API.
     */
    public function statistics(): JsonResponse
    {
        $stats = $this->productService->getDashboardMetrics();

        return response()->json([
            'success' => true,
            'message' => 'Statistik inventaris produk berhasil dimuat.',
            'data' => $stats,
        ]);
    }

    /**
     * Get real-time low stock alert notifications via API.
     */
    public function alerts(): JsonResponse
    {
        $alerts = $this->productService->getLowStockAlerts(10);

        return response()->json([
            'success' => true,
            'message' => 'Peringatan stok menipis.',
            'total_alerts' => $alerts->count(),
            'data' => $alerts,
        ]);
    }
}
