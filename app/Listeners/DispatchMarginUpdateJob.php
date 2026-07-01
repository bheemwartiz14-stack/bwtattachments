<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdateProductMarginByUser;
use App\Http\Resources\MarginUpdateResource;
use App\Models\User;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

class DispatchMarginUpdateJob
{
    public function handle(UpdateProductMarginByUser $event): void
    {
        $user = User::find($event->user);
        if (!$user) return;

        $baseUserId = $event->type === 'retailer' ? $user->parent_id : $event->user;
        $productService = app(ProductService::class);
        $syncService = app(ProductPricingService::class);

        $products = $productService->getActiveProductsWithUserPrices($baseUserId);

        $payload = $products->map(fn ($product) => MarginUpdateResource::make([
            'product' => $product,
            'user'    => $user,
        ])->resolve())->values()->toArray();

        $syncService->syncProductPricesForAllUsers($payload);

        Log::info('Products synced for margin update', [
            'count'   => count($payload),
            'user_id' => $event->user,
        ]);
    }
}
