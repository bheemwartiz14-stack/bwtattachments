<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function __construct(protected User $model)
    {
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if ($search = $filters['search'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                ;
            });
        }

        if ($role = $filters['role'] ?? null) {
            $query->whereHas('roles', fn($q) => $q->where('name', $role));
        }

        if ($status = $filters['status'] ?? null) {
            $query->where('status', $status === 'active');
        }

        return $query->paginate($perPage);
    }

    public function findById(string|int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(string|int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function delete(string|int $id)
    {
        $record = $this->findById($id);
        return $record->delete();
    }

    public function getUsersByRole(string $role, int $perPage = 10, ?string $search = null, ?string $parentId = null)
    {
        return User::role($role)
            ->when($parentId, fn($q) => $q->where('parent_id', $parentId))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    public function getAllByRole(string $role): \Illuminate\Database\Eloquent\Collection
    {
        return User::role($role)->orderBy('name')->get();
    }
}
