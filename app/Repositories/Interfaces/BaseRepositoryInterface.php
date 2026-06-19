<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{
    public function getAll();
    public function paginate(int $perPage = 10);
    public function findById(string|int $id);
    public function create(array $data);
    public function update(string|int $id, array $data);
    public function delete(string|int $id);
    public function restore(string|int $id);
}
