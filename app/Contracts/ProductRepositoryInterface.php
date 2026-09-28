<?php

namespace App\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface ProductRepositoryInterface
 * Contract for data access layer abstraction (Repository Pattern).
 * Adheres to Dependency Inversion Principle (SOLID) and OOP Interfaces.
 */
interface ProductRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Product;

    public function findBySku(string $sku): ?Product;

    public function create(array $data): Product;

    public function update(int $id, array $data): Product;

    public function delete(int $id): bool;

    public function getLowStockProducts(int $limit = 10): Collection;

    public function getStatistics(): array;
}
