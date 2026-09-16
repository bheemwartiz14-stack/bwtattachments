<?php

declare(strict_types=1);

namespace App\Services\Profile;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\ResolvesTempFiles;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileService
{
    use ResolvesTempFiles;

    public function __construct(
        protected UserRepository $userRepository,
        protected PasswordService $passwordService,
        protected AvatarService $avatarService,
    ) {}

    public function updateProfile(User $user, array $data): User
    {
        try {
            return DB::transaction(function () use ($user, $data) {
                $user = $this->userRepository->update($user->id, [
                    'name' => $data['name'] ?? $user->name,
                    'country' => $data['country_name'] ?? '',
                    'country_code' => $data['country_code'] ?? '',
                    'phone' => $data['phone'] ?? null,
                ]);
                if ($user->hasRole('Wholesaler')) {
                    $this->updateClientProfile($user, $data, 'wholesale');
                }

                if ($user->hasRole('Retailer')) {
                    $this->updateClientProfile($user, $data, 'retailer');
                }

                if ($user->hasRole('Admin')) {
                    $this->updateCompanyProfile($user, $data);
                }

                $avatarPath = $this->resolveTempFile($data['avatar_temp'] ?? null);
                if ($avatarPath) {
                    $user->clearMediaCollection('avatar');
                    $user->addMedia($avatarPath)->toMediaCollection('avatar');
                }

                return $user->load('userMeta');
            });
        } catch (Exception $e) {
            Log::error('Failed to update profile', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function updateClientProfile(User $user, array $data, string $type): void
    {
        $nameField = $type . '_client_name';
        $logoField = $type . '_client_logo';

        $meta = $user->userMeta()->firstOrNew();
        $metadata = $meta->metadata ?? [];
        $metadata['client_name'] = $data[$nameField] ?? ($metadata['client_name'] ?? '');
        // Company details (editable from Personal Information tab for wholesalers).
        // canonical key for wholesalers is wholesale_company_name, keep company_name in sync for BC.
        if (array_key_exists('company_name', $data)) {
            $metadata['company_name'] = $data['company_name'] ?? '';
            if ($type === 'wholesale') {
                $metadata['wholesale_company_name'] = $data['company_name'] ?? '';
            }
        }
        foreach (['vat_number', 'address', 'postal_code', 'city'] as $key) {
            if (array_key_exists($key, $data)) {
                $metadata[$key] = $data[$key] ?? '';
            }
        }
        if (array_key_exists('country_name', $data)) {
            $metadata['country'] = $data['country_name'] ?? '';
        }
        if (array_key_exists('country_code', $data)) {
            $metadata['country_code'] = $data['country_code'] ?? '';
        }
        $meta->metadata = $metadata;
        $meta->user()->associate($user);
        $meta->save();

        $logoFile = $data[$logoField] ?? null;
        if (! $logoFile instanceof UploadedFile) {
            $logoFile = $this->resolveTempFile($data[$logoField . '_temp'] ?? null);
        }
        if ($logoFile) {
            // Canonical storage is the User model (matches admin CRUD, profile edit read,
            // and PDF/email logo lookups). Clear legacy meta copy as well.
            $user->clearMediaCollection($logoField);
            $user->addMedia($logoFile)->toMediaCollection($logoField);
            if ($type === 'wholesale' && $meta->exists) {
                $meta->clearMediaCollection($logoField);
            }
        }
    }

    private function updateCompanyProfile(User $user, array $data): void
    {
        $meta = $user->userMeta()->firstOrNew();
        $metadata = $meta->metadata ?? [];
        foreach (['company_name', 'vat_number', 'address', 'postal_code', 'city'] as $key) {
            if (array_key_exists($key, $data)) {
                $metadata[$key] = $data[$key] ?? '';
            }
        }
        $meta->metadata = $metadata;
        $meta->user()->associate($user);
        $meta->save();

        $logoFile = $data['company_logo'] ?? null;
        if (! $logoFile instanceof UploadedFile) {
            $logoFile = $this->resolveTempFile($data['company_logo_temp'] ?? null);
        }
        if ($logoFile) {
            $user->clearMediaCollection('company_logo');
            $user->addMedia($logoFile)->toMediaCollection('company_logo');
        }
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        return $this->passwordService->changePassword($user, $currentPassword, $newPassword);
    }

    public function uploadAvatar(User $user, array $file): User
    {
        return $this->avatarService->upload($user, $file);
    }

    public function deleteAvatar(User $user): User
    {
        return $this->avatarService->delete($user);
    }

    public function deleteWholesaleClientLogo(User $user): User
    {
        try {
            $user->clearMediaCollection('wholesale_client_logo');
            $meta = $user->userMeta;
            if ($meta) {
                $meta->clearMediaCollection('wholesale_client_logo');
            }

            return $user->fresh(['userMeta']);
        } catch (Exception $e) {
            Log::error('Failed to delete Wholesale logo', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function deleteRetailerClientLogo(User $user): User
    {
        try {
            $user->clearMediaCollection('retailer_client_logo');

            return $user->fresh(['userMeta']);
        } catch (Exception $e) {
            Log::error('Failed to delete retailer client logo', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function updateNotificationPreference(User $user, array $data): User
    {
        try {
            $meta = $user->userMeta()->firstOrNew();
            $metadata = array_merge($meta->metadata ?? [], $data);
            $meta->metadata = $metadata;
            $meta->user()->associate($user);
            $meta->save();

            return $user->fresh(['userMeta']);
        } catch (Exception $e) {
            Log::error('Failed to update notification preferences', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
