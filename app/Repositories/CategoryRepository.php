<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryRepository
{
    public function __construct(
        protected Category $model,
    ) {
    }

    /**
     * Get all categories for dropdown.
     *
     * @return array<int, string>
     */
    public function getAll(): array
    {
        return $this->model
            ->query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Get paginated categories.
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->withCount([
                'products',
                'subcategories',
            ])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find category by ID.
     */
    public function findById(string|int $id): Category
    {
        return $this->model
            ->query()
            ->withCount([
                'products',
                'subcategories',
            ])
            ->findOrFail($id);
    }

    /**
     * Create category.
     */
    public function create(array $data): Category
    {
        return $this->model->create($data);
    }

    /**
     * Update category.
     */
    public function update(string|int $id, array $data): Category
    {
        $category = $this->findById($id);

        $category->update($data);

        return $category->refresh();
    }

    /**
     * Delete category.
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Get all categories.
     */
    public function all(): Collection
    {
        return $this->model
            ->query()
            ->orderBy('name')
            ->get();
    }
}