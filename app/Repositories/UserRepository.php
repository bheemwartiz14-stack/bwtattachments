<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function paginate(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if ($search = $filters['search'] ?? null) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('company', fn($cq) => $cq->where('company_name', 'like', "%{$search}%"));
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
}
