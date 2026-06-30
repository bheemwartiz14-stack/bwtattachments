<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\ProductPrices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductPricingRepository
{
    /**
     * Relationships to eager load.
     */
    private const RELATIONS = [
        'product',
        'user',
        'assignedBy',
    ];

    public function __construct(
        protected ProductPrices $model
    ) {
    }

    /**
     * Get paginated product pricing.
     */
    public function paginate(
        int $perPage = 15,
        array $filters = []
    ): LengthAwarePaginator {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->whereHas('product', function ($productQuery) use ($search) {
                            $productQuery
                                ->where('product_title', 'like', "%{$search}%")
                                ->orWhere('product_code', 'like', "%{$search}%");
                        })->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                    });
                }
            )
            ->when(
                $filters['product_id'] ?? null,
                fn ($query, $productId) => $query->where('product_id', $productId)
            )
            ->when(
                $filters['user_id'] ?? null,
                fn ($query, $userId) => $query->where('user_id', $userId)
            )
            ->when(
                $filters['type'] ?? null,
                fn ($query, $type) => $query->where('type', $type)
            )
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get all pricing records.
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
     * Find pricing by ID.
     */
    public function findById(string|int $id): ProductPrices
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->findOrFail($id);
    }

    /**
     * Create pricing.
     */
    public function create(array $data): ProductPrices
    {
        return $this->model->create($data);
    }

    /**
     * Update pricing.
     */
    public function update(string|int $id, array $data): ProductPrices
    {
        $pricing = $this->findById($id);

        $pricing->update($data);

        return $pricing
            ->refresh()
            ->loadMissing(self::RELATIONS);
    }

    /**
     * Delete pricing.
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Find existing pricing record.
     */
    public function findExisting(
        string|int $productId,
        string|int $userId,
        string $type
    ): ?ProductPrices {
        return $this->model
            ->query()
            ->where('product_id', $productId)
            ->where('user_id', $userId)
            ->where('type', $type)
            ->first();
    }

    /**
     * Bulk upsert pricing records.
     */
    public function upsert(array $rows, array $uniqueBy, array $update): int
    {
        return $this->model->upsert($rows, $uniqueBy, $update);
    }
}