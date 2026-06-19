<?php

namespace App\Repositories;

use App\Models\Subcategory;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;

class SubcategoryRepository extends BaseRepository implements SubcategoryRepositoryInterface
{
    public function __construct(Subcategory $model)
    {
        parent::__construct($model);
    }
}
