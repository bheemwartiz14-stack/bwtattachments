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
                    'name' => $data['name'],
                    'phone' => $data['phone'] ?? null,
                ]);

                if ($user->hasRole('Wholesale Client')) {
                    $this->updateClientProfile($user, $data, 'wholesale');
                }

                if ($user->hasRole('Retailer')) {
                    $this->updateClientProfile($user, $data, 'retailer');
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
        $meta->metadata = $metadata;
        $meta->user()->associate($user);
        $meta->save();

        if (isset($data[$logoField]) && $data[$logoField] instanceof UploadedFile) {
            if ($type === 'wholesale') {
                $meta->clearMediaCollection('wholesale_client_logo');
                $meta->addMedia($data[$logoField])->toMediaCollection('wholesale_client_logo');
            } else {
                $user->clearMediaCollection('retailer_client_logo');
                $user->addMedia($data[$logoField])->toMediaCollection('retailer_client_logo');
            }
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
            $meta = $user->userMeta;
            if ($meta) {
                $meta->clearMediaCollection('wholesale_client_logo');
            }

            return $user->fresh(['userMeta']);
        } catch (Exception $e) {
            Log::error('Failed to delete wholesale client logo', [
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
