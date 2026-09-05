<?php

declare(strict_types=1);

namespace App\Services;
use App\Http\Resources\WholesalerHierarchyResource;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\ResolvesTempFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    use ResolvesTempFiles;
    public function __construct(protected UserRepository $userRepository) {}

    public function getAuthenticatedUserMetadata(): array
    {
        return auth()->user()?->userMeta?->metadata ?? [];
    }

    public function getAuthenticatedUser(): ?Model
    {
        return auth()->user();
    }

    public function getParentUser(): ?Model
    {
        $user = auth()->user();
        if ($user && $user->parent_id) {
            return $this->userRepository->findById($user->parent_id);
        }
        return null;
    }

    public function getAdminUser(): ?\App\Models\User
    {
        return $this->userRepository->getAdminUser();
    }

    public function getWholesalerHierarchyWithMargins()
    {
            return $this->userRepository->getWholesalerHierarchyWithMargins();
    }
    public function getAll(): Collection
    {
        return $this->userRepository->getAll();
    }

    public function getWithMarginQuery(): Builder
    {
        return $this->userRepository->getWithMarginQuery();
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
        $roles = ! empty($data['roles']) ? [$data['roles']] : [];
        unset($data['roles']);
        $user = $this->userRepository->create($data);
        if (! empty($roles)) {
            $user->assignRole($roles);
        }

        return $user;
    }

    public function update(string|int $id, array $data): Model
    {
        $roles = ! empty($data['roles']) ? [$data['roles']] : [];
        unset($data['roles']);
        $user = $this->userRepository->update($id, $data);
        if (! empty($roles)) {
            $user->syncRoles($roles);
        }

        return $user;
    }

    public function delete(string|int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    public function getMyCustomers(string|int $id)
    {
        return $this->userRepository->getMyCustomers($id);

    }
    public function updateUserCompayLogo($filetempData)
    {
        $user = $this->getAuthenticatedUser();
        $collections = [
            'Wholesaler' => 'wholesale_client_logo',
            'Reseller'   => 'retailer_client_logo',
            'customer'   => 'customer_logo',
        ];

        $collection = $collections[$user->roles->first()?->name] ?? null;
        $logo = $this->resolveTempImage($filetempData, $collection);
           if ($logo) {
            $user->addMedia($logo)->toMediaCollection($collection);
        }
    }
}
