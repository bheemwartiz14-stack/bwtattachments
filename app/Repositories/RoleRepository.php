<?php

namespace App\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository
{
    public function getByNames(array $names)
    {
        return Role::whereIn('name', $names)->get();
    }

    public function findByName(string $name)
    {
        return Role::where('name', $name)->first();
    }
}