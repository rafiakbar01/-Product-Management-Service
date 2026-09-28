<?php

namespace App\Http\Controllers;

use App\Contracts\ProductServiceInterface;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ProductController
 * Presentation Layer controller for the Product Management Service.
 * Leverages Dependency Injection with ProductServiceInterface (DIP from SOLID).
 */
class ProductController extends Controller
{
    protected ProductServiceInterface $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of products with search & filter algorithm.
     */
    public function index(Request $request): View
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

        $products = $this->productService->listProducts($filters, 10);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $stats = $this->productService->getDashboardMetrics();
        $lowStockAlerts = $this->productService->getLowStockAlerts(5);

        return view('products.index', compact('products', 'categories', 'filters', 'stats', 'lowStockAlerts'));
    }

    /**
     * Show form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $product = $this->productService->createProduct(
            data: $validated,
            userIdentifier: 'Assessor / User',
            ip: $request->ip(),
            userAgent: $request->userAgent()
        );

        return redirect()->route('products.index')
            ->with('success', "Produk '{$product->name}' berhasil ditambahkan ke inventaris!");
    }

    /**
     * Display the specified product with audit logs.
     */
    public function show(int $id): View
    {
        $product = $this->productService->getProductById($id);
        $logs = $product->activityLogs()->orderBy('id', 'desc')->paginate(10);

        return view('products.show', compact('product', 'logs'));
    }

    /**
     * Show form for editing the specified product.
     */
    public function edit(int $id): View
    {
        $product = $this->productService->getProductById($id);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        $validated = $request->validated();

        $product = $this->productService->updateProduct(
            id: $id,
            data: $validated,
            userIdentifier: 'Assessor / User',
            ip: $request->ip(),
            userAgent: $request->userAgent()
        );

        return redirect()->route('products.index')
            ->with('success', "Data produk '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Request $request, int $id): RedirectResponse
    {
        $this->productService->deleteProduct(
            id: $id,
            userIdentifier: 'Assessor / User',
            ip: $request->ip(),
            userAgent: $request->userAgent()
        );

        return redirect()->route('products.index')
            ->with('success', "Produk berhasil dihapus (soft delete) dari sistem!");
    }
}
