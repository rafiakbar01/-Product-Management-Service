<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * Entity model representing a Product within the Product Management Service.
 * Demonstrates OOP encapsulation, relationships, and business logic methods.
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'description',
        'product_type',
        'price',
        'cost_price',
        'stock',
        'min_stock_alert',
        'status',
        'attributes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock_alert' => 'integer',
        'attributes' => 'array',
    ];

    /**
     * Relationship: Product belongs to a Category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: Product has many Activity Logs
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ProductActivityLog::class);
    }

    /**
     * Business Logic: Check if product stock is below or equal to alert threshold
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock_alert;
    }

    /**
     * Business Logic: Check if product is available for sale
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active' && $this->stock > 0;
    }

    /**
     * Scope: Filter active products
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: Filter low stock products for monitoring/alerting
     */
    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('stock', '<=', 'min_stock_alert');
    }

    /**
     * Scope: Multi-criteria Search & Filter Algorithm
     */
    public function scopeFilter(Builder $query, array $filters): Builder
    {
        // 1. Search keyword by SKU or Name or Description
        if (!empty($filters['q'])) {
            $q = trim($filters['q']);
            $query->where(function (Builder $subQuery) use ($q) {
                $subQuery->where('sku', 'like', "%{$q}%")
                         ->orWhere('name', 'like', "%{$q}%")
                         ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // 2. Filter by Category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // 3. Filter by Product Type (physical, digital, service)
        if (!empty($filters['product_type'])) {
            $query->where('product_type', $filters['product_type']);
        }

        // 4. Filter by Status (active, inactive, draft)
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 5. Filter by Price Range
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('price', '>=', (float) $filters['min_price']);
        }
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('price', '<=', (float) $filters['max_price']);
        }

        // 6. Filter by Stock Condition
        if (!empty($filters['stock_status'])) {
            if ($filters['stock_status'] === 'out_of_stock') {
                $query->where('stock', '<=', 0);
            } elseif ($filters['stock_status'] === 'low_stock') {
                $query->whereColumn('stock', '<=', 'min_stock_alert')->where('stock', '>', 0);
            } elseif ($filters['stock_status'] === 'in_stock') {
                $query->whereColumn('stock', '>', 'min_stock_alert');
            }
        }

        // 7. Dynamic Sorting
        $allowedSorts = ['name', 'price', 'stock', 'created_at', 'sku'];
        $sortBy = in_array($filters['sort_by'] ?? '', $allowedSorts) ? $filters['sort_by'] : 'created_at';
        $sortOrder = strtolower($filters['sort_order'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($sortBy, $sortOrder);
    }
}
