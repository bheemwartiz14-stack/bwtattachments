<?php

namespace App\Repositories;

use App\Models\ProductPrices;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductPricingRepository
{
    public function __construct(protected ProductPrices $model)
    {
    }

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['product', 'user', 'assignedBy']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('product', fn($pq) => $pq->where('product_title', 'like', "%{$search}%")
                    ->orWhere('product_code', 'like', "%{$search}%"))
                  ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return $this->model->newQuery()->with(['product', 'user', 'assignedBy'])->get();
    }

    public function findById(string|int $id): ProductPrices
    {
        return $this->model->newQuery()
            ->with(['product', 'user', 'assignedBy'])
            ->findOrFail($id);
    }

    public function create(array $data): ProductPrices
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(string|int $id, array $data): ProductPrices
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record->fresh(['product', 'user', 'assignedBy']);
    }

    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function findExisting(string|int $productId, string|int $userId, string $type): ?ProductPrices
    {
        return $this->model->newQuery()
            ->where('product_id', $productId)
            ->where('user_id', $userId)
            ->where('type', $type)
            ->first();
    }
}
