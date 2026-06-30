<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateProductMarginByUser
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public string $user,
        public string $marginType,
        public float $newMargin,
        public string $type,
    ) {}
}
