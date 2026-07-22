<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdateUserMargins;
use App\Http\Resources\MarginUpdateResource;
use App\Services\ProductPricingService;
use App\Services\ProductService;

class RecalculateProductMargins
{
    public function handle(UpdateUserMargins $event): void
    {
        $userData = $event->user;
        $baseUserId = $userData->parent_id ?? '';
        $productService = app(ProductService::class);
        $products = $productService->getAll();

        $payload = $products->map(fn($product) => MarginUpdateResource::make([
            'product' => $product,
            'userData' => $userData,
        ])->resolve())->values()->toArray();
        $syncService = app(ProductPricingService::class);
        $syncService->syncProductPricesForAllUsers($payload);
    }
}
