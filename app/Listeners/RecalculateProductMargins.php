<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdateUserMargins;
use App\Http\Resources\MarginUpdateResource;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

class RecalculateProductMargins
{
    public function handle(UpdateUserMargins $event): void
    {
        $userData = $event->user;
        $baseUserId = $userData->parent_id ?? '';
        $filter = ['user_id' => $baseUserId];
        Log::info('filter ', [
            'filter' => $filter,
        ]);
        $productService = app(ProductService::class);
        if ($baseUserId) {
            $products = $productService->getActiveProductsWithUserPrices($baseUserId);
        } else {
            $products = $productService->getAll();
        }

        $payload = $products->map(fn ($product) => MarginUpdateResource::make([
            'product' => $product,
            'userData' => $userData,
        ])->resolve())->values()->toArray();
        // dd($payload);
        //   Log::info('payload ', [
        //     'payload' => $payload,
        // ]);
        $syncService = app(ProductPricingService::class);
        $syncService->syncProductPricesForAllUsers($payload);
    }
}
