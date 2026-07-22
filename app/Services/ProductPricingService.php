<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductPricingRepository;
use Illuminate\Support\Facades\Auth;

class ProductPricingService
{
    public function __construct(
        protected ProductPricingRepository $productPricingRepository,
    ) {}

    public function getPrice(Product $product): ?float
    {
        $user = Auth::user();
        $price = null;
        if (!$user) {
            $price = $product->productPrices()
                ->where('type', 'wholesale')
                ->first();
        } else {
            $role = $user->roles->first()?->name;
            switch ($role) {
                case 'Wholesale Client':
                    $price = $product->productPrices()
                        ->where('user_id', $user->id)
                        ->where('type', 'wholesale')
                        ->first();
                    break;
                case 'Reseller':
                    $price = $product->productPrices()
                        ->where('user_id', $user->id)
                        ->where('type', 'reseller')
                        ->first()
                        ?? $product->productPrices()
                        ->where('type', 'wholesale')
                        ->first();
                    break;

                case 'customer':
                    $price = $product->productPrices()
                        ->where('user_id', $user->id)
                        ->where('type', 'customer')
                        ->first()
                        ?? $product->productPrices()
                        ->where('type', 'reseller')
                        ->first() ?? $product->productPrices()
                        ->where('type', 'wholesale')
                        ->first();
                    break;

                default:
                    $price = $product->productPrices()
                        ->where('type', 'wholesale')
                        ->first();
            }
        }

        return $price ? (float) $price->final_price : (float) ($product->ddp_price ?? 0);
    }

    public function syncProductPricesForAllUsers(array $payload): void
    {
        $rows = array_map(fn($item) => [
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

    public function calculatePrice(float $basePrice, string $marginType, float $marginValue): array
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
