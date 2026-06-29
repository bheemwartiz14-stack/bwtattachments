<?php

declare(strict_types=1);

namespace App\Services\Profile;

use App\Models\User;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PasswordService
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}

    /**
     * Change the user's password after verifying the current password.
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        try {
            $this->userRepository->updatePassword($user->id, $newPassword);

            Auth::login($user);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to change password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
