<?php
declare(strict_types=1);

namespace App\Services;

use App\Events\ContactMessageSubmitted;
use App\Repositories\ContactMessageRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ContactMessageService
{
    public function __construct(
        protected ContactMessageRepository $contactMessageRepository,
    ) {}

    public function create(array $data): Model
    {
        $message = $this->contactMessageRepository->create($data);

        ContactMessageSubmitted::dispatch($message);

        return $message;
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->contactMessageRepository->paginate($perPage);
    }

    public function delete(string $id): bool
    {
        return $this->contactMessageRepository->delete($id);
    }
}
