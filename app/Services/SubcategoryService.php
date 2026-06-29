<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\SubcategoryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SubcategoryService
{
    public function __construct(protected SubcategoryRepository $subcategoryRepository)
    {
    }

    public function getAll(): array
    {
        return Cache::remember('subcategories:all', 3600, fn() =>
            $this->subcategoryRepository->getAll()
        );
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->subcategoryRepository->paginate($perPage);
    }

    public function findById(string $id): Model
    {
        return $this->subcategoryRepository->findById($id);
    }

    public function create(array $data): Model
    {
        $result = $this->subcategoryRepository->create($data);
        Cache::forget('subcategories:all');
        return $result;
    }

    public function update(string $id, array $data): Model
    {
        $result = $this->subcategoryRepository->update($id, $data);
        Cache::forget('subcategories:all');
        return $result;
    }

    public function delete(string $id): bool
    {
        $result = $this->subcategoryRepository->delete($id);
        Cache::forget('subcategories:all');
        return $result;
    }

    public function getByCategory(string $categoryId): Collection
    {
        return $this->subcategoryRepository->getByCategory($categoryId);
    }

    public function getAllWithCategory(): Collection
    {
        return $this->subcategoryRepository->getAllWithCategory();
    }
}
