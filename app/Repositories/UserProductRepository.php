<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\UserProduct;

class UserProductRepository
{
    public function __construct(
        protected UserProduct $model
    ) {}

    public function firstOrCreate(array $attributes, array $values = []): UserProduct
    {
        return $this->model->firstOrCreate($attributes, $values);
    }

    public function save(UserProduct $userProduct): bool
    {
        return $userProduct->save();
    }

    public function countByUser(string $userId, string $column): int
    {
        return $this->model
            ->where('user_id', $userId)
            ->where($column, true)
            ->count();
    }

    public function getProductIdsByColumn(string $userId, string $column): array
    {
        return $this->model
            ->where('user_id', $userId)
            ->where($column, true)
            ->pluck('product_id')
            ->toArray();
    }
}
