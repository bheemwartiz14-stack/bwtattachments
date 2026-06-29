<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Relationships to eager load to prevent N+1 queries.
     */
    private const RELATIONS = [
        'roles',
        'userMeta',
    ];
    public function __construct(
        protected User $model
    ) {
    }

    /**
     * Get all users.
     */
    public function getAll(): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->get();
    }

    /**
     * Paginate users with filters.
     */
    public function paginate(
        int $perPage = 10,
        array $filters = []
    ): LengthAwarePaginator {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->when(
                $filters['search'] ?? null,
                function ($query, $search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                $filters['role'] ?? null,
                fn ($query, $role) => $query->whereHas(
                    'roles',
                    fn ($query) => $query->where('name', $role)
                )
            )
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                fn ($query) => $query->where(
                    'status',
                    $filters['status'] === 'active'
                )
            )
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find user by ID.
     */
    public function findById(string|int $id): User
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->findOrFail($id);
    }

    /**
     * Create user.
     */
    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Update user.
     */
    public function update(string|int $id, array $data): User
    {
        $user = $this->findById($id);

        $user->update($data);

        return $user->refresh()->loadMissing(self::RELATIONS);
    }

    /**
     * Delete user.
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Get paginated users by role.
     */
    public function getUsersByRole(
        string $role,
        int $perPage = 10,
        ?string $search = null,
        ?string $parentId = null
    ): LengthAwarePaginator {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->role($role)
            ->when(
                $parentId,
                fn ($query) => $query->where('parent_id', $parentId)
            )
            ->when(
                $search,
                function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            )
            ->oldest()
            ->paginate($perPage);
    }

    /**
     * Get all users by role.
     */
    public function getAllByRole(string $role): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->role($role)
            ->orderBy('name')
            ->get();
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(string|int $id, string $password): User
    {
        $user = $this->findById($id);

        $user->password = $password;
        $user->save();

        return $user->refresh()->loadMissing(self::RELATIONS);
    }

    /**
     * Upload and set the user's avatar.
     */
    public function updateAvatar(string|int $id, string $filePath): User
    {
        $user = $this->findById($id);

        $user->addMedia($filePath)->toMediaCollection('avatar');

        return $user->refresh()->loadMissing(self::RELATIONS);
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(string|int $id): User
    {
        $user = $this->findById($id);

        $user->clearMediaCollection('avatar');

        return $user->refresh()->loadMissing(self::RELATIONS);
    }
}