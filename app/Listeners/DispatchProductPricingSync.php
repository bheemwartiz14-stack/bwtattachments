<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdatedProductMarginForAllUsersByProduct;
use App\Jobs\SyncProductPricesForAllUsers;

class DispatchProductPricingSync
{
    public function handle(UpdatedProductMarginForAllUsersByProduct $event): void
    {
        SyncProductPricesForAllUsers::dispatch($event->product);
    }
}
