<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    /**
     * Relationships to eager load.
     */
    private const RELATIONS = [
        'media',
        'category',
        'subcategory',
        'connection',
    ];

    /**
     * Relationships for product details.
     */
    private const DETAIL_RELATIONS = [
        'media',
        'category',
        'subcategory',
        'connection',
        'productPrices.user',
    ];

    public function __construct(
        protected Product $model
    ) {
    }

    /**
     * Get all products.
     */
    public function getAll(): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->latest()
            ->get();
    }

    /**
     * Paginate products.
     */
    public function paginate(
        int $perPage = 10,
        array $filters = []
    ): LengthAwarePaginator {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('product_code', 'like', "%{$search}%")
                            ->orWhere('product_title', 'like', "%{$search}%")
                            ->orWhere('product_description', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                !empty($filters['category']),
                fn ($query) => $query->whereIn('category_id', (array) $filters['category'])
            )
            ->when(
                !empty($filters['subcategory']),
                fn ($query) => $query->whereIn('subcategory_id', (array) $filters['subcategory'])
            )
            ->when(
                !empty($filters['connection']),
                fn ($query) => $query->whereIn('connection_id', (array) $filters['connection'])
            )
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                function ($query) use ($filters) {
                    $status = $filters['status'];

                    if ($status === 'published' || $status == 1) {
                        $query->where('status', true);
                    }

                    if (
                        in_array($status, ['draft', 'hidden'], true) ||
                        $status == 0
                    ) {
                        $query->where('status', false);
                    }
                }
            )
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find product by ID.
     */
    public function findById(string|int $id): Product
    {
        return $this->model
            ->query()
            ->with(self::DETAIL_RELATIONS)
            ->findOrFail($id);
    }

    /**
     * Create product.
     */
    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    /**
     * Update product.
     */
    public function update(string|int $id, array $data): Product
    {
        $product = $this->findById($id);

        $product->update($data);

        return $product
            ->refresh()
            ->loadMissing(self::DETAIL_RELATIONS);
    }

    /**
     * Delete product.
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Find products by category.
     */
    public function findByCategory(string|int $categoryId): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->where('category_id', $categoryId)
            ->latest()
            ->get();
    }

    /**
     * Find products by subcategory.
     */
    public function findBySubcategory(string|int $subcategoryId): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->where('subcategory_id', $subcategoryId)
            ->latest()
            ->get();
    }

    /**
     * Find products by connection.
     */
    public function findByConnection(string|int $connectionId): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->where('connection_id', $connectionId)
            ->latest()
            ->get();
    }

    /**
     * Search products.
     */
    public function search(string $term): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->where(function ($query) use ($term) {
                $query->where('product_code', 'like', "%{$term}%")
                    ->orWhere('product_title', 'like', "%{$term}%")
                    ->orWhere('product_description', 'like', "%{$term}%");
            })
            ->latest()
            ->get();
    }
}