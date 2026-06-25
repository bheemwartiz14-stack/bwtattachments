<?php

namespace App\Repositories;

use App\Models\Subcategory;

class SubcategoryRepository
{
    public function __construct(protected Subcategory $model)
    {
    }

    /**
     * Dropdown data: id => name
     */
    public function getAll(): array
    {
        return $this->model->query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Filtered dropdown: by category
     */
    public function getByCategory(string|int $categoryId): array
    {
        return $this->model->query()
            ->where('category_id', $categoryId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Paginated list (admin table)
     */
    public function paginate(int $perPage = 10)
    {
        return $this->model->query()->paginate($perPage);
    }

    /**
     * Find single record
     */
    public function findById(string|int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update
     */
    public function update(string|int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);

        return $record;
    }

    /**
     * Delete
     */
    public function delete(string|int $id)
    {
        $record = $this->findById($id);
        return $record->delete();
    }
}