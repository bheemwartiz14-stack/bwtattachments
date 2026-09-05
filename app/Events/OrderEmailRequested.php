<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;

class OrderEmailRequested
{
    use Dispatchable;

    public function __construct(
        public Order $order,
    ) {}
}
