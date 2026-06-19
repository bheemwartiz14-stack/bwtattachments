<?php

namespace App\Repositories;

use App\Models\Quotation;
use App\Repositories\Interfaces\QuotationRepositoryInterface;

class QuotationRepository extends BaseRepository implements QuotationRepositoryInterface
{
    public function __construct(Quotation $model)
    {
        parent::__construct($model);
    }

    public function findByUser(string $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function findByStatus(string $status)
    {
        return $this->model->where('status', $status)->get();
    }
}
