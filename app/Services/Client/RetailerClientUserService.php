<?php
declare(strict_types=1);

namespace App\Services\Client;

use App\Events\RetailerClientInvited;
use App\Events\UpdateProductMarginByUser;
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
            $commission = (float) ($data['commission_percentage'] ?? 0);
            unset($data['retailer_client_logo'], $data['retailer_client_name'], $data['commission_percentage']);
            $user = $this->userService->create($data);
            if ($logoFile instanceof UploadedFile) {
                $user->addMedia($logoFile)->toMediaCollection('retailer_client_logo');
            }
            $user->userMeta()->updateOrCreate(
                ['user_id' => $user->id],
                ['metadata' => ['client_name' => $retailerClientName]]
            );
            $this->saveMargin($user, $commission);
            event(new RetailerClientInvited($user, $plainPassword));
            return $user;
        });
    }

    public function update(string|int $id, array $data): Model
    {
        return DB::transaction(function () use ($id, $data) {
            $retailerClientName = $data['retailer_client_name'] ?? null;
            $logoFile = $data['retailer_client_logo'] ?? null;
            $commission = isset($data['commission_percentage']) ? (float) $data['commission_percentage'] : null;
            unset($data['retailer_client_name'], $data['retailer_client_logo'], $data['commission_percentage']);
            $user = $this->userService->update($id, $data);
            $user->userMeta()->updateOrCreate(
                ['user_id' => $user->id],
                ['metadata' => array_merge($user->userMeta?->metadata ?? [], ['retailer_client_name' => $retailerClientName])]
            );
            if ($logoFile instanceof UploadedFile) {
                $user->clearMediaCollection('retailer_client_logo');
                $user->addMedia($logoFile)->toMediaCollection('retailer_client_logo');
            }
            if ($commission !== null) {
                $this->saveMargin($user, $commission);
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

    public function paginate(int $perPage = 10, array $filters = []): mixed
    {
        return $this->userService->fetchUsers('Retailer', $perPage, $filters['search'] ?? null, auth()->id());
    }

    private function saveMargin(Model $user, float $value): void
    {
        $oldMargin = (float) ($user->userMargin?->margin_value ?? 0);
        $user->userMargin()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'type' => 'retailer',
                'margin_type' => 'percentage',
                'margin_value' => $value,
            ]
        );
        if ($value !== $oldMargin) {
            event(new UpdateProductMarginByUser($user->id, 'percentage', $value, 'retailer'));
        }
    }
}
