<?php
declare(strict_types=1);

namespace App\Data;

class UserData
{
    public function __construct(
        public string $user_id,
        public ?string $parent_id,
        public ?string $role_name,
        public string $name,
        public string $margin_type,
        public string $type,
        public float $margin_value,
    ) {}
}
