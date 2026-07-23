<?php
declare(strict_types=1);

namespace App\Services;

use App\Events\ResellerApplicationSubmitted;
use App\Models\ResellerApplication;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ResellerApplicationService
{
    public function create(array $data): Model
    {
        $application = ResellerApplication::create($data);

        ResellerApplicationSubmitted::dispatch($application);

        return $application;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return ResellerApplication::query()
            ->latest()
            ->paginate($perPage);
    }

    public function delete(string $id): bool
    {
        return ResellerApplication::findOrFail($id)->delete();
    }
}
