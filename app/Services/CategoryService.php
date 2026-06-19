<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function getAll(): Collection
    {
        return $this->categoryRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->categoryRepository->paginate($perPage);
    }

    public function findById(string $id): Model
    {
        return $this->categoryRepository->findById($id);
    }

    public function create(array $data): Model
    {
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }
        return $this->categoryRepository->create($data);
    }

    public function update(string $id, array $data): Model
    {
        if (empty($data['slug']) && isset($data['name'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }
        return $this->categoryRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
