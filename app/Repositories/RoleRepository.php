<?php
declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function __construct(
        protected Role $model
    ) {
    }

    /**
     * Get roles by names.
     */
    public function getByNames(array $names): Collection
    {
        return $this->model
            ->query()
            ->whereIn('name', $names)
            ->get();
    }

    /**
     * Find a role by name.
     */
    public function findByName(string $name): ?Role
    {
        return $this->model
            ->query()
            ->where('name', $name)
            ->first();
    }
}