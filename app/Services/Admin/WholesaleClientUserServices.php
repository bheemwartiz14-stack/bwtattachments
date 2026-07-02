<?php
declare(strict_types=1);

namespace App\Services\Admin;

use App\Events\UpdateProductMarginByWholesaleAccounts;
use App\Events\WholesaleClientsRegistered;
use App\Services\UserService;
use App\Traits\ResolvesTempFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WholesaleClientUserServices
{
    use ResolvesTempFiles;
    public function __construct(
        protected UserService $userService,
    ) {}

    public function create(array $data): Model
    {
        $plainPassword = $data['password'] ?? null;
        [$meta, $margin] = $this->extract($data);
        $meta['plain_password'] = $plainPassword;

        $user = $this->userService->create($data);
        $this->saveMargin($user, $margin);
        $userMeta = $this->saveMeta($user, $meta);

        $logo = $this->resolveTempImage($data, 'wholesale_client_logo');
        if ($logo) {
            $user->addMedia($logo)->toMediaCollection('wholesale_client_logo');
        }
        event(new WholesaleClientsRegistered($user, $plainPassword));
        return $user;
    }

    public function update(string|int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->userService->update($id, $data);
            [$meta, $margin] = $this->extract($data);
            $this->saveMargin($user, $margin);
            $this->saveMeta($user, $meta);

            $logo = $this->resolveTempImage($data, 'wholesale_client_logo');
            if ($logo) {
                $user->clearMediaCollection('wholesale_client_logo');
                $user->addMedia($logo)->toMediaCollection('wholesale_client_logo');
            }

            return $user;
        });
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

    /* ---------------- Helpers ---------------- */

    private function extract(array &$data): array
    {
        $meta = [
            'wholesale_company_name' => $data['wholesale_company_name'] ?? null,
            'address' => $data['address'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'city' => $data['city'] ?? null,
            'country' => $data['country'] ?? null,
            'website' => $data['website'] ?? null,
            'vat_number' => $data['vat_number'] ?? null,
        ];
        $margin = $data['commission_percentage'] ?? 0;

        unset(
            $data['wholesale_company_name'],
            $data['commission_percentage']
        );

        return [$meta, $margin];
    }

    private function saveMeta(Model $user, array $meta): mixed
    {
        return $user->userMeta()->updateOrCreate(
            ['user_id' => $user->id],
            ['metadata' => array_merge($user->userMeta?->metadata ?? [], $meta)]
        );
    }

    private function saveMargin(Model $user, $value): void
    {
        $user->userMargin()->updateOrCreate(
            ['user_id' => $user->id],
            ['type' => 'wholesale', 'margin_type' => 'percentage', 'margin_value' => $value ?? 0]
        );
        event(new UpdateProductMarginByWholesaleAccounts($user->id, 'percentage', (float) $value));
    }
}
