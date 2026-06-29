<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Connection;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ConnectionRepository
{
    public function __construct(
        protected Connection $model,
    ) {
    }

    /**
     * Get all connections for dropdown.
     *
     * @return array<int, string>
     */
    public function getAll(): array
    {
        return $this->model
            ->query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Get paginated connections.
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
            ->query()
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Find connection by ID.
     */
    public function findById(string|int $id): Connection
    {
        return $this->model
            ->query()
            ->findOrFail($id);
    }

    /**
     * Create connection.
     */
    public function create(array $data): Connection
    {
        return $this->model->create($data);
    }

    /**
     * Update connection.
     */
    public function update(string|int $id, array $data): Connection
    {
        $connection = $this->findById($id);

        $connection->update($data);

        return $connection->refresh();
    }

    /**
     * Delete connection.
     */
    public function delete(string|int $id): bool
    {
        return $this->findById($id)->delete();
    }

    /**
     * Get all connections.
     */
    public function all(): Collection
    {
        return $this->model
            ->query()
            ->orderBy('name')
            ->get();
    }
}