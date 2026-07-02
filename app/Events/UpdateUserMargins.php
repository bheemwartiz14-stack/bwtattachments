<?php
declare(strict_types=1);
namespace App\Events;
use App\Data\UserData;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class UpdateUserMargins
{
    use Dispatchable, SerializesModels;
    public function __construct(
        public UserData $user,
    ) {}
}
