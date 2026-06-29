<?php
declare(strict_types=1);

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
        'address' => $data['address'] ?? null,
        'postal_code' => $data['postal_code'] ?? null,
        'city' => $data['city'] ?? null,
        'country' => $data['country'] ?? null,
        'website' => $data['website'] ?? null,
        'vat_number' => $data['vat_number'] ?? null,
    ];

    $userMeta = UserMeta::create([
        'user_id' => $user->id,
        'metadata' => $metadata,
    ]);

    if ($logoFile) {
        $userMeta->addMedia($logoFile)
            ->toMediaCollection('wholesale_client_logo');

        $logoUrl = $userMeta->getFirstMediaUrl('wholesale_client_logo');

        $metadata['wholesale_client_logo_url'] = $logoUrl;

        $userMeta->update([
            'metadata' => $metadata,
        ]);
    }

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
        $metadata['address'] = $data['address'] ?? null;
        $metadata['postal_code'] = $data['postal_code'] ?? null;
        $metadata['city'] = $data['city'] ?? null;
        $metadata['country'] = $data['country'] ?? null;
        $metadata['website'] = $data['website'] ?? null;
        $metadata['vat_number'] = $data['vat_number'] ?? null;
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
