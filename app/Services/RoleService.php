<?php

namespace App\Services;

use App\Repositories\RoleRepository;

class RoleService
{
    public function __construct(
        protected RoleRepository $roleRepository
    ) {}

    /**
     * Get roles by names
     */
    public function getByNames(array $names)
    {
        return $this->roleRepository->getByNames($names);
    }

    public function getByName(string $name)
    {
        return $this->roleRepository->findByName($name);
    }
}