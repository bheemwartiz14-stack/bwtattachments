<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository
{
    private const RELATIONS = ['fromUser.userMeta', 'toUser.userMeta', 'items.product.media'];

    public function __construct(protected Order $model) {}

    public function getAll(): Collection
    {
        return $this->model->query()->with(self::RELATIONS)->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->query()->with(self::RELATIONS)->paginate($perPage);
    }

    public function findById(string|int $id): Order
    {
        return $this->model->query()->with(self::RELATIONS)->findOrFail($id);
    }

    public function create(array $data): Order
    {
        return $this->model->create($data);
    }
    public function findByUser(string $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->query()->with(self::RELATIONS)->where('order_from_user_id', $userId)->latest()->paginate($perPage);
    }

    public function findByUserCollection(string $userId): Collection
    {
        return $this->model->query()->with(self::RELATIONS)->where('order_from_user_id', $userId)->latest()->get();
    }

    public function findByStatus(string $status): Collection
    {
        return $this->model->query()->with(self::RELATIONS)->where('status', $status)->get();
    }

    public function createItem(string $orderId, string $productId, float $price, int $quantity): \App\Models\OrderItems
    {
        return \App\Models\OrderItems::create([
            'order_id' => $orderId,
            'product_id' => $productId,
            'price' => $price,
            'quantity' => $quantity,
        ]);
    }

    public function deleteItem(string $orderId, int $itemId): bool
    {
        return \App\Models\OrderItems::where('order_id', $orderId)->where('id', $itemId)->delete() > 0;
    }

    public function update(string|int $id, array $data): Order
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }
}
?>
