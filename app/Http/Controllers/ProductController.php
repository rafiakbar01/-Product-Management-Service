<?php

namespace App\Http\Controllers;

use App\Contracts\ProductServiceInterface;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:64|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:physical,digital,service',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,draft',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU tersebut sudah terdaftar dalam sistem.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'price.required' => 'Harga jual produk wajib diisi.',
            'price.numeric' => 'Harga jual harus berupa angka valid.',
            'price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'stock.required' => 'Jumlah stok awal produk wajib diisi.',
            'stock.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'min_stock_alert.required' => 'Batas minimum notifikasi stok wajib ditentukan.',
        ]);

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
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:64',
                \Illuminate\Validation\Rule::unique('products', 'sku')->ignore($id),
            ],
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:physical,digital,service',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,draft',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU tersebut sudah terdaftar pada produk lain.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'price.required' => 'Harga jual wajib diisi.',
            'stock.required' => 'Jumlah stok wajib diisi.',
        ]);

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
