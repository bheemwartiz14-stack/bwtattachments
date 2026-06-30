<?php
declare(strict_types=1);

namespace App\Events;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;

class UpdatedProductMarginForAllUsersByProduct
{
    use Dispatchable;

    public function __construct(
        public Product $product,
    ) {}
}
