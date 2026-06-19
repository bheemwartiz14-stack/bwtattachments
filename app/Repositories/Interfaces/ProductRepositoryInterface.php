<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function findByCategory(int $categoryId);
    public function findBySubcategory(int $subcategoryId);
    public function findByConnection(int $connectionId);
    public function search(string $term);
}
