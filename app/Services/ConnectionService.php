<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\ConnectionRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class ConnectionService
{
    public function __construct(protected ConnectionRepository $connectionRepository)
    {
    }

    public function getAll(): array
    {
        return Cache::remember('connections:all', 3600, fn() =>
            $this->connectionRepository->getAll()
        );
    }

    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return $this->connectionRepository->paginate($perPage);
    }

    public function findById(string $id): Model
    {
        return $this->connectionRepository->findById($id);
    }

    public function create(array $data): Model
    {
        $result = $this->connectionRepository->create($data);
        Cache::forget('connections:all');
        return $result;
    }

    public function update(string $id, array $data): Model
    {
        $result = $this->connectionRepository->update($id, $data);
        Cache::forget('connections:all');
        return $result;
    }

    public function delete(string $id): bool
    {
        $result = $this->connectionRepository->delete($id);
        Cache::forget('connections:all');
        return $result;
    }
}
