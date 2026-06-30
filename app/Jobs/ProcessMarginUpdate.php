<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Http\Resources\MarginUpdateResource;
use App\Models\User;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessMarginUpdate implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $user,
        public string $marginType,
        public float $newMargin,
        public string $type,
    ) {}

    public function handle(
        ProductService $productService,
        ProductPricingService $syncService,
    ): void {
        $user = User::find($this->user);
        if (!$user) return;

        $baseUserId = $this->type === 'retailer' ? $user->parent_id : $this->user;
        $products = $productService->getActiveProductsWithUserPrices($baseUserId);

        $payload = $products->map(fn ($product) => MarginUpdateResource::make([
            'product' => $product,
            'user'    => $user,
        ])->resolve())->values()->toArray();

        $syncService->syncProductPricesForAllUsers($payload);

        Log::info('Products synced for margin update', [
            'count'   => count($payload),
            'user_id' => $this->user,
            '$payload' => $payload
        ]);
    }
}
