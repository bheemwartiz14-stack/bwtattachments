<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\ProductCreated;
use Illuminate\Support\Facades\Log;

class LogProductCreated
{
    public function handle(ProductCreated $event): void
    {
        Log::info('Product created: ' . $event->product->product_code);
    }
}
