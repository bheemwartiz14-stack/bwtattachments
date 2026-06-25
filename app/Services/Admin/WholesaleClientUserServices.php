<?php

namespace App\Services\Admin;

use App\Events\WholesaleClientsRegistered;
use App\Models\UserMeta;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Model;

class WholesaleClientUserServices
{
    public function __construct(
        protected UserService $userService,
    ) {}

    public function create(array $data): Model
{
    $plainPassword = $data['password'] ?? '';

    $wholesaleClientName = $data['wholesale_client_name'] ?? null;
    $logoFile = $data['wholesale_client_logo'] ?? null;
    unset($data['wholesale_client_name'], $data['wholesale_client_logo']);
    $user = $this->userService->create($data);
    $metadata = [
        'client_name' => $wholesaleClientName,
    ];
    // 3. Create UserMeta first
    $userMeta = UserMeta::create([
        'user_id' => $user->id,
        'metadata' => $metadata,
    ]);

    // 4. Upload logo (if exists) and update metadata at last
    if ($logoFile) {
        $userMeta->addMedia($logoFile)
            ->toMediaCollection('wholesale_client_logo');

        // get final URL
        $logoUrl = $userMeta->getFirstMediaUrl('wholesale_client_logo');

        // "push" into metadata (PHP way)
        $metadata['wholesale_client_logo_url'] = $logoUrl;

        // update final metadata
        $userMeta->update([
            'metadata' => $metadata,
        ]);
    }

    // 5. Fire event at last
    event(new WholesaleClientsRegistered($user, $plainPassword));

    return $user;
}

    public function update(string|int $id, array $data): Model
    {
        $wholesaleClientName = $data['wholesale_client_name'] ?? null;
        $logoFile = $data['wholesale_client_logo'] ?? null;
     
        unset($data['wholesale_client_name'], $data['wholesale_client_logo']);

        $user = $this->userService->update($id, $data);

        $userMeta = $user->userMeta ?? UserMeta::create([
            'user_id' => $user->id,
            'metadata' => [],
        ]);

        $metadata = $userMeta->metadata ?? [];
        $metadata['client_name'] = $wholesaleClientName;
        $userMeta->metadata = $metadata;
        $userMeta->save();
        if ($logoFile) {
            $userMeta->clearMediaCollection('wholesale_client_logo');
            $userMeta->addMedia($logoFile)->toMediaCollection('wholesale_client_logo');
        }

        return $user;
    }

    public function findById(string|int $id): Model
    {
        return $this->userService->findById($id);
    }

    public function paginate(int $perPage = 10, array $filters = []): mixed
    {
        return $this->userService->paginate($perPage, $filters);
    }

    public function delete(string|int $id): bool
    {
        return $this->userService->delete($id);
    }

}
