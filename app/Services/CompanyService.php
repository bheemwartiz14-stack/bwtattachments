<?php

namespace App\Services;

use App\Repositories\Interfaces\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CompanyService
{
    public function __construct(protected CompanyRepositoryInterface $companyRepository)
    {
    }

    public function getAll(): Collection
    {
        return $this->companyRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->companyRepository->paginate($perPage);
    }

    public function findById(string $id): Model
    {
        return $this->companyRepository->findById($id);
    }

    public function create(array $data): Model
    {
        return $this->companyRepository->create($data);
    }

    public function update(string $id, array $data): Model
    {
        return $this->companyRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->companyRepository->delete($id);
    }
}
