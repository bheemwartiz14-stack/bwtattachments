<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdateProductMarginByWholesaleAccounts;
use App\Http\Resources\MarginUpdateResourceWholesale;
use App\Models\User;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

class DispatchMarginUpdateJobWholesale
{
    public function handle(UpdateProductMarginByWholesaleAccounts $event): void
    {
        $user = User::with('userMargin')->find($event->user);
        if (!$user) return;
        $productService = app(ProductService::class);
        $pricingService = app(ProductPricingService::class);
        $products = $productService->getAll();
        $payload = $products->map(fn ($product) => MarginUpdateResourceWholesale::make([
            'product' => $product,
            'user'    => $user,
        ])->resolve())->values()->toArray();
        $pricingService->syncProductPricesForAllUsers($payload);
        Log::info('Wholesale margin synced', [
            'count'   => count($payload),
            'user_id' => $event->user,
            'payload'=> $payload,
            'products'=> $products
        ]);
    }
}
