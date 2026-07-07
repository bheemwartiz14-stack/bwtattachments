<?php
declare(strict_types=1);

namespace App\Services\Retailer;
use App\Data\UserData;
use App\Events\UpdateUserMargins;
use App\Events\RetailerClientInvited;
use App\Services\UserService;
use App\Traits\ExtractsUserMeta;
use App\Traits\ResolvesTempFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class CustomerService
{
    use ExtractsUserMeta, ResolvesTempFiles;
    public function __construct(
        protected UserService $userService
    ) {}

    public function create(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $plainPassword = $data['password'] ?? '';
            $user = $this->userService->create($data);
            $this->processMetaAndMargin($user, $data, $plainPassword);
            event(new RetailerClientInvited($user, $plainPassword));
            return $user;
        });
    }

    public function update(string|int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->userService->update($id, $data);
            $this->processMetaAndMargin($user, $data);
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

    public function paginate(int $perPage = 10, array $filters = []): mixed
    {
        return $this->userService->fetchUsers('customer', $perPage, $filters['search'] ?? null, auth()->id());
    }

    private function processMetaAndMargin(Model $user, array $data, ?string $plainPassword = null): void
    {
        [$meta, $margin] = $this->extract($data, 'company_name');
        if ($plainPassword) {
            $meta['plain_password'] = \App\Helpers\PasswordHelper::encrypt($plainPassword);
        }
        $this->saveMeta($user, $meta);
        $this->saveMargin($user, (float) $margin, 'customer');
        $logo = $this->resolveTempImage($data, 'customer_logo');
        if ($logo) {
            $user->clearMediaCollection('customer_logo');
            $user->addMedia($logo)->toMediaCollection('customer_logo');
        }
        $this->dispatchMarginEvent($user, (float) $margin);
    }

    private function dispatchMarginEvent(Model $user, float $margin): void
    {
        event(new UpdateUserMargins(new UserData(
            user_id: $user->id,
            parent_id: $user->parent_id,
            role_name: $user->roles->pluck('name')->first(),
            name: $user->name,
            margin_type: 'percentage',
            type: 'customer',
            margin_value: $margin,
        )));
    }
}
