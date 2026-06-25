<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\SubcategoryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected SubcategoryRepository $subcategoryRepository,
    ) {}

    public function getAll(): array
    {
        return $this->categoryRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->categoryRepository->paginate($perPage);
    }

    public function findById(string|int $id): Model
    {
        return $this->categoryRepository->findById($id);
    }

    public function create(array $data): Model
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->categoryRepository->create($data);
    }

    public function update(string|int $id, array $data): Model
    {
        if (empty($data['slug']) && isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->categoryRepository->update($id, $data);
    }

    public function delete(string|int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }

    public function getSubcategories(string|int $id)
    {
        return $this->subcategoryRepository->getByCategory($id);
    }
}
