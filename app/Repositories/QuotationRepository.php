<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Quotation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class QuotationRepository
{
    private const RELATIONS = ['user', 'items'];

    public function __construct(
        protected Quotation $model,
    ) {}

    public function getAll(): Collection
    {
        return $this->model->query()->with(self::RELATIONS)->get();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->query()->with(self::RELATIONS)->paginate($perPage);
    }

    public function findById(string|int $id): Quotation
    {
        return $this->model->query()->with(self::RELATIONS)->findOrFail($id);
    }

    public function create(array $data): Quotation
    {
        return $this->model->create($data);
    }

    public function update(string|int $id, array $data): Quotation
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function findByUser(string $userId): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->where('user_id', $userId)
            ->get();
    }

    public function findByStatus(string $status): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->where('status', $status)
            ->get();
    }

    public function createItem(int $quotationId, int $productId, float $price, int $quantity): \App\Models\QuotationItem
    {
        return \App\Models\QuotationItem::create([
            'quotation_id' => $quotationId,
            'product_id' => $productId,
            'price' => $price,
            'quantity' => $quantity,
        ]);
    }

    public function deleteItem(int $quotationId, int $itemId): bool
    {
        return \App\Models\QuotationItem::where('quotation_id', $quotationId)
            ->where('id', $itemId)
            ->delete() > 0;
    }
}
