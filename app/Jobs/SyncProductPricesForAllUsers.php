<?php
declare(strict_types=1);

namespace App\Jobs;

use App\Http\Resources\ProductPricingResource;
use App\Models\Product;
use App\Services\ProductPricingService;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncProductPricesForAllUsers implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Product $product,
    ) {}
    public function handle(
        UserService $userService,
        ProductPricingService $pricingService,
    ): void {
        $users = $userService->getWithMarginQuery()
            ->where('status', 1)
            ->with(['userMargin:user_id,margin_type,margin_value', 'roles:id,name'])
            ->select(['id', 'name', 'email'])
            ->get();

        $payload = $users->map(fn ($user) => ProductPricingResource::make([
            'product' => $this->product,
            'user'    => $user,
        ])->resolve())->values()->toArray();

        $pricingService->syncProductPricesForAllUsers($payload);

        Log::info('SyncProductPricesForAllUsers done', [
            'product_id' => $this->product->id,
            'users'      => count($payload),
        ]);
    }
}
