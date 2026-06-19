<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function findByCategory(int $categoryId)
    {
        return $this->model->where('category_id', $categoryId)->get();
    }

    public function findBySubcategory(int $subcategoryId)
    {
        return $this->model->where('subcategory_id', $subcategoryId)->get();
    }

    public function findByConnection(int $connectionId)
    {
        return $this->model->where('connection_id', $connectionId)->get();
    }

    public function search(string $term)
    {
        return $this->model->where('name', 'like', "%{$term}%")
            ->orWhere('description', 'like', "%{$term}%")
            ->orWhere('sku', 'like', "%{$term}%")
            ->get();
    }
}
