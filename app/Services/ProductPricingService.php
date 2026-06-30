<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ProductPricingRepository;
use Illuminate\Support\Facades\Log;

class ProductPricingService
{
    public function __construct(
        protected ProductPricingRepository $productPricingRepository,
    ) {}

    public function syncProductPricesForUser(string $userId, float $mainPrice, string $type, string $marginType, array $products): void
    {
        $rows = array_map(fn ($p) => [
            'product_id'  => $p['id'],
            'user_id'     => $userId,
            'type'        => $type,
            ...$this->calculatePrice(
                (float) ($p['product_prices'][0]['final_price'] ?? $p['ddp_price'] ?? 0),
                $marginType,
                $mainPrice,
            ),
            'created_at'  => now(),
            'updated_at'  => now(),
        ], $products);
        $this->upsertRows($rows);
    }

    public function syncProductPricesForAllUsers(array $payload): void
    {
        $rows = array_map(fn ($item) => [
            'product_id'  => $item['product_id'],
            'user_id'     => $item['user_id'],
            'type'        => $item['type'],
            'base_price'  => $item['base_price'],
            'final_price' => $item['final_price'],
            'margin'      => $item['margin'],
            'created_at'  => now(),
            'updated_at'  => now(),
        ], $payload);

        $this->upsertRows($rows);
    }

    private function upsertRows(array $rows): void
    {
        if (empty($rows)) return;

        $this->productPricingRepository->upsert(
            $rows,
            ['product_id', 'user_id', 'type'],
            ['base_price', 'final_price', 'margin', 'updated_at'],
        );
    }

    private function calculatePrice(float $basePrice, string $marginType, float $marginValue): array
    {
        $finalPrice = $marginType === 'value'
            ? $basePrice + $marginValue
            : $basePrice + ($basePrice * $marginValue / 100);

        return [
            'base_price'  => $basePrice,
            'final_price' => round($finalPrice, 2),
            'margin'      => $marginValue,
        ];
    }
}
