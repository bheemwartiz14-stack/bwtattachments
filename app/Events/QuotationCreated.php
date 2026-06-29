<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Quotation;
use Illuminate\Foundation\Events\Dispatchable;

class QuotationCreated
{
    use Dispatchable;

    public function __construct(
        public Quotation $quotation,
    ) {}
}
