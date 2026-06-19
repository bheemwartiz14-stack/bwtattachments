<?php

namespace App\Services;

use App\Repositories\Interfaces\ConnectionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ConnectionService
{
    public function __construct(protected ConnectionRepositoryInterface $connectionRepository)
    {
    }

    public function getAll(): Collection
    {
        return $this->connectionRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->connectionRepository->paginate($perPage);
    }

    public function findById(int $id): Model
    {
        return $this->connectionRepository->findById($id);
    }

    public function create(array $data): Model
    {
        return $this->connectionRepository->create($data);
    }

    public function update(int $id, array $data): Model
    {
        return $this->connectionRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->connectionRepository->delete($id);
    }

    public function restore(int $id): Model
    {
        return $this->connectionRepository->restore($id);
    }
}
