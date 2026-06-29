<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\ProductPrices;
use App\Repositories\ProductPricingRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductPricingService
{
    public function __construct(
        protected ProductPricingRepository $productPricingRepository,
        protected ProductService $productService,
        protected UserService $userService,
    ) {}

    public function paginate(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->productPricingRepository->paginate($perPage, $filters);
    }

    public function getAll(): Collection
    {
        return $this->productPricingRepository->getAll();
    }

    public function findById(string|int $id): ProductPrices
    {
        return $this->productPricingRepository->findById($id);
    }

    public function store(array $data): ProductPrices
    {
        $existing = $this->productPricingRepository->findExisting(
            $data['product_id'],
            $data['user_id'],
            $data['type'] ?? 'wholesale_purchase'
        );

        if ($existing) {
            $existing->update([
                'price' => $data['price'],
                'margin' => $data['margin'] ?? null,
            ]);
            return $existing->fresh(['product', 'user', 'assignedBy']);
        }

        return $this->productPricingRepository->create($data);
    }

    public function update(string|int $id, array $data): ProductPrices
    {
        return $this->productPricingRepository->update($id, $data);
    }

    public function delete(string|int $id): bool
    {
        return $this->productPricingRepository->delete($id);
    }

    public function syncPrices(mixed $product, array $prices, string $type = 'wholesale_purchase'): void
    {
        foreach ($prices as $priceData) {
            if (empty($priceData['user_id']) || !isset($priceData['price'])) {
                continue;
            }

            ProductPrices::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $priceData['user_id'],
                    'type' => $type,
                ],
                [
                    'price' => (float) $priceData['price'],
                    'margin' => $priceData['margin'] ?? null,
                    'assigned_by' => $priceData['assigned_by'] ?? auth()->id(),
                ]
            );
        }
    }

    public function getProductsForSelect(): array
    {
        return $this->productService->getAll()->pluck('product_title', 'id')->toArray();
    }

    public function getWholesaleUsersForSelect(): array
    {
        return $this->userService->getByRole('Wholesale Client')->pluck('name', 'id')->toArray();
    }
}
