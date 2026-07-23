<?php

declare(strict_types=1);

namespace App\Services;

use App\Events\ContactMessageSubmitted;
use App\Repositories\ContactMessageRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;

class ContactMessageService
{
    public function __construct(
        protected ContactMessageRepository $contactMessageRepository,
    ) {}

    public function create(array $data): Model
    {
        Log::info('ContactMessageService::create called');
        $message = $this->contactMessageRepository->create($data);
        Log::info('Dispatching ContactMessageSubmitted event', [
            'id' => $message->id,
        ]);
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
