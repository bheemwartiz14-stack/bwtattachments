<?php

declare(strict_types=1);

namespace App\Services\Profile;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class AvatarService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    /**
     * Upload avatar for the user.
     */
    public function upload(User $user, array $file): User
    {
        try {
            return $this->userRepository->updateAvatar(
                $user->id,
                $file['file']->getPathname(),
            );
        } catch (Exception $e) {
            Log::error('Failed to upload avatar', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Delete the user's avatar.
     */
    public function delete(User $user): User
    {
        try {
            return $this->userRepository->deleteAvatar($user->id);
        } catch (Exception $e) {
            Log::error('Failed to delete avatar', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
