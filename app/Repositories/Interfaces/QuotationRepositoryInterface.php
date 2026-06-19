<?php

namespace App\Repositories\Interfaces;

interface QuotationRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUser(string $userId);
    public function findByStatus(string $status);
}
