<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ProductServiceInterface
 * Contract for business logic layer (Service Layer Pattern).
 * Encapsulates domain logic, validation processing, and logging orchestration.
 */
interface ProductServiceInterface
{
    public function listProducts(array $filters = [], int $perPage = 10): LengthAwarePaginator;

    public function getProductById(int $id): Product;

    public function createProduct(array $data, ?string $userIdentifier = null, ?string $ip = null, ?string $userAgent = null): Product;

    public function updateProduct(int $id, array $data, ?string $userIdentifier = null, ?string $ip = null, ?string $userAgent = null): Product;

    public function deleteProduct(int $id, ?string $userIdentifier = null, ?string $ip = null, ?string $userAgent = null): bool;

    public function getLowStockAlerts(int $limit = 5): Collection;

    public function getDashboardMetrics(): array;
}
