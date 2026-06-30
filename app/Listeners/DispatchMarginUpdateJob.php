<?php
declare(strict_types=1);

namespace App\Listeners;

use App\Events\UpdateProductMarginByUser;
use App\Jobs\ProcessMarginUpdate;

class DispatchMarginUpdateJob
{
    public function handle(UpdateProductMarginByUser $event): void
    {
        ProcessMarginUpdate::dispatch(
            $event->user,
            $event->marginType,
            $event->newMargin,
            $event->type,
        );
    }
}
