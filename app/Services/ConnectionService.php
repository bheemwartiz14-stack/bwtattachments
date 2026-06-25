<?php

namespace App\Services;

use App\Repositories\ConnectionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ConnectionService
{
    public function __construct(protected ConnectionRepository $connectionRepository)
    {
    }

    public function getAll(): array
    {
        return $this->connectionRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->connectionRepository->paginate($perPage);
    }

    public function findById(string $id): Model
    {
        return $this->connectionRepository->findById($id);
    }

    public function create(array $data): Model
    {
        return $this->connectionRepository->create($data);
    }

    public function update(string $id, array $data): Model
    {
        return $this->connectionRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->connectionRepository->delete($id);
    }
}
