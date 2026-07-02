<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdateMarginsForRetailers;
use App\Http\Resources\MarginUpdateResourceWholesale;
use App\Models\User;
use App\Services\ProductPricingService;
use App\Services\ProductService;
use Illuminate\Support\Facades\Log;

class DispatchResellerMargin
{
    public function handle(UpdateProductMarginByWholesaleAccounts $event): void
    {
        // $user = User::with('userMargin')->find($event->user);
        // dd($event);
        Log::info('DispatchResellerMargin event triggered', [
            'user_id' => $event->user,
            'marginType' => $event->marginType,
            'newMargin' => $event->newMargin,
        ]);
    }
}
