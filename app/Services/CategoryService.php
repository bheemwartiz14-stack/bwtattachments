<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\SubcategoryRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected SubcategoryRepository $subcategoryRepository,
    ) {}

    public function getAll(): array
    {
        return Cache::remember('categories:all', 3600, fn() =>
            $this->categoryRepository->getAll()
        );
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

        $result = $this->categoryRepository->create($data);
        Cache::forget('categories:all');
        return $result;
    }

    public function update(string|int $id, array $data): Model
    {
        if (empty($data['slug']) && isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $result = $this->categoryRepository->update($id, $data);
        Cache::forget('categories:all');
        return $result;
    }

    public function delete(string|int $id): bool
    {
        $result = $this->categoryRepository->delete($id);
        Cache::forget('categories:all');
        return $result;
    }

    public function getSubcategories(string|int $id)
    {
        return $this->subcategoryRepository->getByCategory($id);
    }
}
