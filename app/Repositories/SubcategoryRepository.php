<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class SubcategoryRepository
{
    /**
     * Relationships to eager load.
     */
    private const RELATIONS = [
        'category:id,name',
    ];

    public function __construct(
        protected Subcategory $model
    ) {
    }

    /**
     * Get all subcategories for dropdown.
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
     * Get subcategories by category for dropdown.
     *
     * @return array<int, string>
     */
    public function getByCategory(string|int $categoryId): array
    {
        return $this->model
            ->query()
            ->where('category_id', $categoryId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Get all subcategories with category.
     */
    public function getAllWithCategory(): Collection
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'category_id',
            ]);
    }

    /**
     * Paginate subcategories.
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find subcategory by ID.
     */
    public function findById(string|int $id): Subcategory
    {
        return $this->model
            ->query()
            ->with(self::RELATIONS)
            ->findOrFail($id);
    }

    /**
     * Create subcategory.
     */
    public function create(array $data): Subcategory
    {
        return $this->model->create($data);
    }

    /**
     * Update subcategory.
     */
    public function update(string|int $id, array $data): Subcategory
    {
        $subcategory = $this->findById($id);

        $subcategory->update($data);

        return $subcategory
            ->refresh()
            ->loadMissing(self::RELATIONS);
    }

    /**
     * Delete subcategory.
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }
}