<?php
declare(strict_types=1);

namespace App\Services\Reseller;
use App\Data\UserData;
use App\Events\UpdateUserMargins;
use App\Events\WelcomeOnboardingUser;
use App\Models\VatRate;
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
            $this->normalizeCountryFromVatId($data);
            $plainPassword = $data['password'] ?? '';
            $user = $this->userService->create($data);
            $this->processMetaAndMargin($user, $data, $plainPassword);
            $user->load(['userMeta']);
            event(new WelcomeOnboardingUser($user, $plainPassword, 'customer'));
            return $user;
        });
    }

    public function update(string|int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $this->normalizeCountryFromVatId($data);
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

    /**
     * The country select (vat_id) is the source of truth. Hidden
     * country/country_code inputs are only JS mirrors and can go stale
     * (e.g. change handler not bound after wire:navigate), so always
     * derive them from the selected VAT rate before persisting.
     */
    private function normalizeCountryFromVatId(array &$data): void
    {
        if (empty($data['vat_id'])) {
            return;
        }
        $vatRate = VatRate::query()->find($data['vat_id']);
        if ($vatRate) {
            $data['country'] = $vatRate->country;
            $data['country_code'] = $vatRate->iso_code;
        }
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
