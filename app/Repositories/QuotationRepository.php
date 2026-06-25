<?php

namespace App\Repositories;

use App\Models\Quotation;

class QuotationRepository
{
    public function __construct(protected Quotation $model)
    {
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function paginate(int $perPage = 10)
    {
        return $this->model->query()->paginate($perPage);
    }

    public function findById(string|int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(string|int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function delete(string|int $id)
    {
        $record = $this->findById($id);
        return $record->delete();
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
