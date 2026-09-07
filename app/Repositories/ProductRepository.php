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
        'productPrices',
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

        return $this->model->query()
            ->where('status', 1)
            ->with(self::RELATIONS)
            ->select($select);


    }


    public function filterProducts( array $filters = []){

        $userId = $filters['user_id'] ?? null;
        $perPage = isset($filters['perPage']) && in_array((int) $filters['perPage'], [25, 50, 75, 100], true)
            ? (int) $filters['perPage']
            : 28;
        $query = $this->model->query()->with(self::RELATIONS)->where('status', 1);
        if (!empty($filters['search'])) {
        $query->where(function ($q) use ($filters) {
                $q->where('product_code', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('product_title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('product_description', 'like', '%' . $filters['search'] . '%');
            });
        }
        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }
        if (!empty($filters['subcategory'])) {
            $query->where('subcategory_id', $filters['subcategory']);
        }
        if (!empty($filters['connection'])) {
             $query->where('connection_id', $filters['connection']);
        }
        if (!empty($filters['machine_class'])) {
            $machineClass = trim((string) $filters['machine_class']);
            if (str_ends_with($machineClass, '+')) {
                $query->where('machine_class', '>=', (int) $machineClass);
            } elseif (str_contains($machineClass, '-')) {
                [$min, $max] = array_map('intval', explode('-', $machineClass, 2));
                $query->whereBetween('machine_class', [min($min, $max), max($min, $max)]);
            } else {
                $query->where('machine_class', $machineClass);
            }
        }
        // Weight Min and Max (0-10000 = slider defaults = no filter)
        if ( isset($filters['min_weight']) && isset($filters['max_weight']) &&  $filters['min_weight'] !== '' &&  $filters['max_weight'] !== '' ) {
            $minWeight = (int) $filters['min_weight'];
            $maxWeight = (int) $filters['max_weight'];
            if (! ($minWeight == 0 && $maxWeight == 10000)) {
                if ($minWeight > $maxWeight) {
                    [$minWeight, $maxWeight] = [$maxWeight, $minWeight];
                }
                $query->whereRaw(
            'CAST(weight AS UNSIGNED) BETWEEN ? AND ?',
            [$minWeight, $maxWeight]
        );
            }
        }
        if (!empty($filters['sort_by'])) {
            $this->applySorting( $query, $filters['sort_by'] );
        } else {
            $query->orderBy('products.created_at', 'desc');
        }

        return $query->paginate($perPage);
}

    private  function applySorting(Builder $query, string $sortBy): void
    {
        $allowed = ['newest', 'oldest', 'manufacture_year_high_low', 'manufacture_year_low_high', 'price_high_low', 'price_low_high'];
        if (! in_array($sortBy, $allowed, true)) {
            $query->orderBy('products.created_at', 'desc');
            return;
        }
        $userId = auth()->id();
        switch ($sortBy) {
            case 'newest':
                $query->latest('products.created_at');
                break;
            case 'oldest':
                $query->orderBy('products.created_at', 'asc');
                break;
            case 'manufacture_year_high_low':
                $query->orderByRaw('products.manufacture_year IS NULL, products.manufacture_year DESC');
                break;
            case 'manufacture_year_low_high':
                $query->orderByRaw('products.manufacture_year IS NULL, products.manufacture_year ASC');
                break;
            case 'price_high_low':
                   if ($userId) {
                        $query->leftJoin('product_prices as pp', function ($join) use ($userId) {
                            $join->on('pp.product_id', '=', 'products.id')
                                ->where('pp.user_id', '=', $userId);
                        });
                        $query->select('products.*')
                            ->orderByRaw(
                                'COALESCE(pp.final_price, products.ddp_price) DESC'
                            );
                    } else {
                        $query->orderByDesc('products.ddp_price');
                    }
                break;
            case 'price_low_high':
                $query->orderByRaw(
                    'COALESCE(
                        (
                            SELECT final_price
                            FROM product_prices
                            WHERE product_prices.product_id = products.id
                            AND product_prices.user_id = ?
                            LIMIT 1
                        ),
                        products.ddp_price
                    ) ASC',
                    [$userId]
                );
                break;
        }
    }

 public function paginate(
    int $perPage = 10,
    array $filters = []
): LengthAwarePaginator {
    $userId = $filters['user_id'] ?? null;

    return $this->model
        ->query()
        ->with(array_merge(self::RELATIONS, [
            'productPrices' => function ($query) use ($userId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                }

                $query->select([
                    'product_id',
                    'user_id',
                    'base_price',
                    'final_price',
                    'margin',
                ]);
            },
        ]))
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
        ->when($userId, function ($q) use ($userId) {
            $q->whereHas('productPrices', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            });
        })
        ->when(isset($filters['status']) && $filters['status'] !== '', function ($q) use ($filters) {
            if ($filters['status'] === 'published' || $filters['status'] == 1) {
                $q->where('status', 1);
            }

            if (in_array($filters['status'], ['draft', 'hidden'], true) || $filters['status'] == 0) {
                $q->where('status', 0);
            }
        })
        ->oldest()
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
            ->with(['category', 'connection', 'productPrices' => fn ($q) => $q->where('user_id', $userId)->select(['product_id', 'user_id', 'base_price', 'final_price', 'margin'])])
            ->where('status', 1)
            ->select(['id', 'product_code','product_title', 'category_id', 'connection_id', 'ddp_price'])
            ->paginate($perPage);
    }

    public function getActiveProductsWithUserPrices(string $userId): Collection
    {
        return $this->model
            ->query()
            ->with(['category', 'connection', 'productPrices' => fn ($q) => $q->where('user_id', $userId)->select(['product_id', 'user_id', 'base_price', 'final_price', 'margin'])])
            ->where('status', 1)
            ->select(['id', 'product_code', 'product_title', 'product_description', 'category_id', 'connection_id', 'ddp_price'])
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

    public function paginateActiveProductsForUser(array $filters, int $perPage, string $userId): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->with(['category', 'connection', 'productPrices' => fn ($q) => $q->where('user_id', $userId)->select(['product_id', 'user_id', 'base_price', 'final_price', 'margin'])])
            ->where('status', 1)
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
            ->latest()
            ->paginate($perPage);
    }
}
