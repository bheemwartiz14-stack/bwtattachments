<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function __construct(protected Product $model)
    {
    }

    /**
     * Get all products with media
     */
    public function getAll(): Collection
    {
        return $this->model->newQuery()
            ->with('media') // ✅ Spatie media eager load
            ->get();
    }

    /**
     * Paginated products with media and optional filters
     */
    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->with('media');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('product_code', 'like', "%{$search}%")
                  ->orWhere('product_title', 'like', "%{$search}%")
                  ->orWhere('product_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['category'])) {
            $query->whereIn('category_id', (array) $filters['category']);
        }

        if (!empty($filters['subcategory'])) {
            $query->whereIn('subcategory_id', (array) $filters['subcategory']);
        }

        if (!empty($filters['connection'])) {
            $query->whereIn('connection_id', (array) $filters['connection']);
        }

        if (isset($filters['status'])) {
            if ($filters['status'] === 'published' || $filters['status'] == 1) {
                $query->where('status', true);
            } elseif (in_array($filters['status'], ['draft', 'hidden'], true) || $filters['status'] == 0) {
                $query->where('status', false);
            }
        }

        return $query->paginate($perPage);
    }

    /**
     * Find single product
     */
    public function findById(string|int $id): Product
    {
        return $this->model->newQuery()
            ->with(['media', 'productPrices.user'])
            ->findOrFail($id);
    }

    /**
     * Create product
     */
    public function create(array $data): Product
    {
        return $this->model->newQuery()->create($data);
    }

    /**
     * Update product
     */
    public function update(string|int $id, array $data): Product
    {
        $record = $this->findById($id);
        $record->update($data);

        return $record->fresh(['media']); // ✅ reload with media
    }

    /**
     * Delete product
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function findByCategory(string|int $categoryId): Collection
    {
        return $this->model->newQuery()
            ->with('media')
            ->where('category_id', $categoryId)
            ->get();
    }

    public function findBySubcategory(int $subcategoryId): Collection
    {
        return $this->model->newQuery()
            ->with('media')
            ->where('subcategory_id', $subcategoryId)
            ->get();
    }

    public function findByConnection(int $connectionId): Collection
    {
        return $this->model->newQuery()
            ->with('media')
            ->where('connection_id', $connectionId)
            ->get();
    }

    public function search(string $term): Collection
    {
        return $this->model->newQuery()
            ->with('media')
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('sku', 'like', "%{$term}%");
            })
            ->get();
    }
}