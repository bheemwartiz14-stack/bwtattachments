<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\UserRegistered;
use Illuminate\Support\Collection;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository)
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

    public function findById(string|int $id): Model
    {
        return $this->userRepository->findById($id);
    }

    public function create(array $data): Model
    {
        $roles = [$data['roles']] ?? [];
        unset($data['roles']);
        $user = $this->userRepository->create($data);
        if (!empty($roles)) {
            $user->assignRole($roles);
        }
        $plainPassword = $data['password'];
        event(new UserRegistered($user, $plainPassword));
        return $user;
    }

    public function update(string|int $id, array $data): Model
    {
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

    public function restore(string|int $id): Model
    {
        return $this->userRepository->restore($id);
    }
}
