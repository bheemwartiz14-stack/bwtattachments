<?php
declare(strict_types=1);

namespace App\Jobs;

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

        $syncService->syncProductPricesForUser(
            $this->user,
            $this->newMargin,
            $this->type,
            $this->marginType,
            $products->toArray(),
        );
    }
}
