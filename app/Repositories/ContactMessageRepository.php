<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\ContactMessage;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ContactMessageRepository
{
    public function __construct(
        protected ContactMessage $model,
    ) {}

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->latest()
            ->paginate($perPage);
    }

    public function delete(string $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }
}
