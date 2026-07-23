<?php
declare(strict_types=1);

namespace App\Services\Admin;
use App\Data\UserData;
use App\Events\UpdateUserMargins;
use App\Events\WelcomeOnboardingUser;
use App\Services\UserService;
use App\Traits\ExtractsUserMeta;
use App\Traits\ResolvesTempFiles;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WholesaleClientUserServices
{
    use ExtractsUserMeta;
    use ResolvesTempFiles;
    public function __construct(
        protected UserService $userService,
    ) {}

    public function create(array $data): Model
    {
        $plainPassword = $data['password'] ?? null;
        [$meta, $margin] = $this->extract($data);
        $meta['plain_password'] = \App\Helpers\PasswordHelper::encrypt($plainPassword);
        $user = $this->userService->create($data);
        $this->saveMargin($user, (float) $margin, 'Wholesaler');
        $this->saveMeta($user, $meta);
        $logo = $this->resolveTempImage($data, 'wholesale_client_logo');
        if ($logo) {
            $user->addMedia($logo)->toMediaCollection('wholesale_client_logo');
        }
        $user->load(['userMeta']);
        $this->dispatchMarginEvent($user, (float) $margin);
        event(new WelcomeOnboardingUser($user, $plainPassword, 'Wholesaler'));
        return $user;
    }

    public function update(string|int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $user = $this->userService->update($id, $data);
            [$meta, $margin] = $this->extract($data);
            $this->saveMeta($user, $meta);
            $this->saveMargin($user, (float) $margin, 'Wholesaler');
            $logo = $this->resolveTempImage($data, 'wholesale_client_logo');
            if ($logo) {
                $user->clearMediaCollection('wholesale_client_logo');
                $user->addMedia($logo)->toMediaCollection('wholesale_client_logo');
            }
            $this->dispatchMarginEvent($user, (float) $margin);
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

       private function dispatchMarginEvent(Model $user, float $margin): void
    {
        $dispytechdata = new UserData(
            user_id: $user->id,
            parent_id: $user->parent_id,
            role_name: $user->roles->pluck('name')->first(),
            name: $user->name,
            margin_type: 'percentage',
            type: 'Wholesaler',
            margin_value: $margin,
        );
       event(new UpdateUserMargins($dispytechdata));
    }

}
