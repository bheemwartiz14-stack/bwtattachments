<?php

namespace App\Services;

use App\Models\ProductPrices;
use Illuminate\Database\Eloquent\Model;

readonly class ProductPricingService
{
    public function syncPrices(Model $product, array $prices): void
    {
        foreach ($prices as $priceData) {
            if (empty($priceData['user_id']) || !isset($priceData['price'])) {
                continue;
            }

            ProductPrices::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $priceData['user_id'],
                ],
                [
                    'price' => (float) $priceData['price'],
                ]
            );
        }
    }
}
