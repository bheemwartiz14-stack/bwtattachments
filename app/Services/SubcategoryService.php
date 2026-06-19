<?php

namespace App\Services;

use App\Models\Subcategory;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SubcategoryService
{
    public function __construct(protected SubcategoryRepositoryInterface $subcategoryRepository)
    {
    }

    public function getAll(): Collection
    {
        return $this->subcategoryRepository->getAll();
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->subcategoryRepository->paginate($perPage);
    }

    public function findById(int $id): Model
    {
        return $this->subcategoryRepository->findById($id);
    }

    public function create(array $data): Model
    {
        return $this->subcategoryRepository->create($data);
    }

    public function update(string $id, array $data): Model
    {
        return $this->subcategoryRepository->update($id, $data);
    }

    public function delete(string $id): bool
    {
        return $this->subcategoryRepository->delete($id);
    }

    public function restore(string $id): Model
    {
        return $this->subcategoryRepository->restore($id);
    }

    public function getByCategory(string $categoryId): Collection
    {
        return Subcategory::where('category_id', $categoryId)->get();
    }
}
