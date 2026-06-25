<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    public function __construct(protected Category $model)
    {
    }

    /**
     * Get all categories for dropdown (id => name)
     */
    public function getAll(): array
    {
        return $this->model->query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Paginated list (for admin table)
     */
    public function paginate(int $perPage = 10)
    {
        return $this->model->query()
            ->withCount(['products', 'subcategories'])
            ->paginate($perPage);
    }

    /**
     * Find single record
     */
    public function findById(string|int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create category
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update category
     */
    public function update(string|int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);

        return $record;
    }

    /**
     * Delete category
     */
    public function delete(string|int $id)
    {
        $record = $this->findById($id);
        return $record->delete();
    }
}