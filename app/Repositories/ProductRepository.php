<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    private const RELATIONS = [
        'media',
        'category',
        'subcategory',
        'connection',
    ];

    private const DETAIL_RELATIONS = [
        'media',
        'category',
        'subcategory',
        'connection',
        'productPrices.user',
    ];

    public function __construct(
        protected Product $model
    ) {}

    /**
     * Get all products
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
     * ACTIVE PRODUCTS QUERY (IMPORTANT FOR JOBS)
     */
    public function activeQuery(array $select = ['*']): Builder
    {
        return $this->model
            ->query()
            ->where('status', 1)
            ->select($select);
    }

    /**
     * Paginate products
     */
    public function paginate(
        int $perPage = 10,
        array $filters = []
    ): LengthAwarePaginator {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('product_code', 'like', "%{$search}%")
                        ->orWhere('product_title', 'like', "%{$search}%")
                        ->orWhere('product_description', 'like', "%{$search}%");
                });
            })
            ->when(!empty($filters['category']), fn ($q) =>
                $q->whereIn('category_id', (array) $filters['category'])
            )
            ->when(!empty($filters['subcategory']), fn ($q) =>
                $q->whereIn('subcategory_id', (array) $filters['subcategory'])
            )
            ->when(!empty($filters['connection']), fn ($q) =>
                $q->whereIn('connection_id', (array) $filters['connection'])
            )
            ->when(!empty($filters['machine_class']), fn ($q) =>
                $q->where('machine_class', $filters['machine_class'])
            )
            ->when(isset($filters['status']) && $filters['status'] !== '', function ($q) use ($filters) {
                if ($filters['status'] === 'published' || $filters['status'] == 1) {
                    $q->where('status', 1);
                }

                if (in_array($filters['status'], ['draft', 'hidden'], true) || $filters['status'] == 0) {
                    $q->where('status', 0);
                }
            })
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find product by ID
     */
    public function findById(string|int $id): Product
    {
        return $this->model
            ->query()
            ->with(self::DETAIL_RELATIONS)
            ->findOrFail($id);
    }

    /**
     * Create product
     */
    public function create(array $data): Product
    {
        return $this->model->create($data);
    }

    /**
     * Update product
     */
    public function update(string|int $id, array $data): Product
    {
        $product = $this->findById($id);

        $product->update($data);

        return $product->refresh()->loadMissing(self::DETAIL_RELATIONS);
    }

    /**
     * Delete product
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Find by category
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
     * Find by subcategory
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
     * Find by connection
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
     * Search products
     */
    public function getAllWithClientProducts(int $perPage, string $userId): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->with(['category', 'connection', 'productPrices' => fn ($q) => $q->where('user_id', $userId)->select(['product_id', 'user_id', 'final_price', 'margin'])])
            ->where('status', 1)
            ->select(['id', 'product_code', 'category_id', 'connection_id', 'ddp_price'])
            ->paginate($perPage);
    }

    public function getActiveProductsWithUserPrices(string $userId): Collection
    {
        return $this->model
            ->query()
            ->with(['category', 'connection', 'productPrices' => fn ($q) => $q->where('user_id', $userId)->select(['product_id', 'user_id', 'final_price', 'margin'])])
            ->where('status', 1)
            ->select(['id', 'product_code', 'category_id', 'connection_id', 'ddp_price'])
            ->get();
    }

    public function search(string $term): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->where(function ($q) use ($term) {
                $q->where('product_code', 'like', "%{$term}%")
                    ->orWhere('product_title', 'like', "%{$term}%")
                    ->orWhere('product_description', 'like', "%{$term}%");
            })
            ->latest()
            ->get();
    }
}
