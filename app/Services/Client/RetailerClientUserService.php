<?php

namespace App\Services\Client;

use App\Events\RetailerClientInvited;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class RetailerClientUserService
{
    public function __construct(
        protected UserService $userService
    ) {}
    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $plainPassword = $data['password'] ?? '';
            $logoFile = $data['retailer_client_logo'] ?? null;
            $retailerClientName = $data['retailer_client_name'] ?? null;
            unset($data['retailer_client_logo'], $data['retailer_client_name']);
            $user = $this->userService->create($data);
            if ($logoFile instanceof UploadedFile) {
                $user->addMedia($logoFile)->toMediaCollection('retailer_client_logo');
            }
            $metadata = [
                'client_name' => $retailerClientName,
            ];
            if (method_exists($user, 'userMeta')) {
                $user->userMeta()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['metadata' => $metadata]
                );
            }

            event(new RetailerClientInvited($user, $plainPassword));

            return $user;
        });
    }

    public function paginate(int $perPage = 10, array $filters = []): mixed
    {
        $search = $filters['search'] ?? null;
        return $this->userService->fetchUsers('Retailer', $perPage, $search, auth()->id());
    }

    public function update(string|int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $retailerClientName = $data['retailer_client_name'] ?? null;
            $logoFile = $data['retailer_client_logo'] ?? null;
            unset($data['retailer_client_name'], $data['retailer_client_logo']);
            $user = $this->userService->update($id, $data);
            $metadata = $user->userMeta?->metadata ?? [];
            $metadata['retailer_client_name'] = $retailerClientName;

            if (method_exists($user, 'userMeta')) {
                $user->userMeta()->updateOrCreate(
                    ['user_id' => $user->id],
                    ['metadata' => $metadata]
                );
            }

            if ($logoFile instanceof UploadedFile) {
                $user->clearMediaCollection('retailer_client_logo');
                $user->addMedia($logoFile)->toMediaCollection('retailer_client_logo');
            }

            return $user;
        });
    }

    public function delete(string|int $id): bool
    {
        return $this->userService->delete($id);
    }

    public function findById(string|int $id): Model
    {
        return $this->userService->findById($id);
    }
}