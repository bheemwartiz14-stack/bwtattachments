<?php
declare(strict_types=1);

namespace App\Jobs;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        $products = $productService->getActiveQuery()
            ->where('status', 1)
            ->select(['id', 'ddp_price'])
            ->get();
        $syncService->syncProductPricesForUser(
            $this->user,
            $this->newMargin,
            $this->type,
            $this->marginType,
            $products->toArray()
        );
    }
}
