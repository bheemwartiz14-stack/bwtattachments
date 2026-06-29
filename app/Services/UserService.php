<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function getAll(): Collection
    {
        return $this->userRepository->getAll();
    }

    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage, $filters);
    }

    public function fetchUsers(string $role, int $perPage = 10, ?string $search = null, ?string $parentId = null)
    {
        return $this->userRepository->getUsersByRole($role, $perPage, $search, $parentId);
    }

    public function getByRole(string $role): Collection
    {
        return $this->userRepository->getAllByRole($role);
    }

    public function findById(string|int $id): Model
    {
        return $this->userRepository->findById($id);
    }

    public function create(array $data): Model
    {
        $roles = !empty($data['roles']) ? [$data['roles']] : [];
        unset($data['roles']);
        $user = $this->userRepository->create($data);
        if (!empty($roles)) {
            $user->assignRole($roles);
        }
        return $user;
    }

    public function update(string|int $id, array $data): Model
    {
        $roles = !empty($data['roles']) ? [$data['roles']] : [];
        unset($data['roles']);
        $user = $this->userRepository->update($id, $data);
        if (!empty($roles)) {
            $user->syncRoles($roles);
        }
        return $user;
    }

    public function delete(string|int $id): bool
    {
        return $this->userRepository->delete($id);
    }

}
