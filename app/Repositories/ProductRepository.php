<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProductRepository
 * Implementation of ProductRepositoryInterface using Eloquent ORM.
 * Implements clean database encapsulation for Unit UK J.620100.010.02 & J.620100.013.01.
 */
class ProductRepository implements ProductRepositoryInterface
{
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    /**
     * Get paginated products with filter algorithm applied.
     */
    public function getAll(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->with('category')
            ->filter($filters)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Find single product by ID with category.
     */
    public function findById(int $id): ?Product
    {
        return $this->model->with('category')->find($id);
    }

    /**
     * Find product by unique SKU.
     */
    public function findBySku(string $sku): ?Product
    {
        return $this->model->where('sku', $sku)->first();
    }

    /**
     * Store new product in database.
     */
    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    /**
     * Update existing product.
     */
    public function update(int $id, array $data): Product
    {
        $product = $this->findById($id);
        if ($product) {
            $product->update($data);
            return $product->fresh('category');
        }
        return null;
    }

    /**
     * Soft delete product.
     */
    public function delete(int $id): bool
    {
        $product = $this->findById($id);
        if ($product) {
            return (bool) $product->delete();
        }
        return false;
    }

    /**
     * Get products whose stock is below or equal to alert threshold.
     */
    public function getLowStockProducts(int $limit = 10): Collection
    {
        return $this->model
            ->with('category')
            ->lowStock()
            ->where('status', 'active')
            ->orderBy('stock', 'asc')
            ->limit($limit)
            ->get();
    }

    /**
     * Aggregate dashboard summary statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total_products' => $this->model->count(),
            'active_products' => $this->model->where('status', 'active')->count(),
            'low_stock_count' => $this->model->lowStock()->count(),
            'out_of_stock_count' => $this->model->where('stock', '<=', 0)->count(),
            'total_stock_units' => (int) $this->model->sum('stock'),
            'total_inventory_value' => (float) $this->model->selectRaw('SUM(price * stock) as total_val')->value('total_val'),
        ];
    }
}
