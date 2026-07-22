<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateAvatarRequest;
use App\Http\Requests\Profile\UpdateNotificationRequest;
use App\Http\Requests\Profile\UpdatePasswordRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use App\Services\Profile\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService,
    ) {}

    public function edit(): View
    {
        /** @var User $user */
        $user = auth()->user()->load('userMeta');

        $avatar = $user->getFirstMedia('avatar');

        $viewData = [
            'user' => $user,
            'prefix' => $this->resolveRoutePrefix($user),
            'avatarUrl' => $avatar?->getUrl(),
            'avatarId' => $avatar?->id,
        ];

        $meta = $user->userMeta?->metadata ?? [];
        $roleData = match (true) {
            $user->hasRole('Wholesale') => [
                'company_name' => $meta['wholesale_company_name'] ?? '',
                'logo' => $user->getFirstMedia('wholesale_client_logo'),
            ],

            $user->hasRole('Retailer') => [
                'company_name' => $meta['company_name'] ?? '',
                'logo' => $user->getFirstMedia('retailer_client_logo'),
            ],

            $user->hasRole('customer') => [
                'company_name' => $meta['company_name'] ?? '',
                'logo' => $user->getFirstMedia('customer_logo'),
            ],
            default => [],
        };

        if (! empty($roleData)) {
            $viewData['company_name'] = $roleData['company_name'] ?? '';
            $viewData['logo'] = $roleData['logo']?->getUrl();
            $viewData['logo_id'] = $roleData['logo']?->id;
        }

        return view('pages.private.profile.edit', $viewData);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        return $this->handleUpdate(
            $request,
            fn ($user, $data) => $this->profileService->updateProfile($user, $data),
            'Profile updated successfully.'
        );
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        if (! $this->profileService->changePassword(
            $user,
            $request->input('current_password'),
            $request->input('password'),
        )) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        return $this->successRedirect($user, 'Password changed successfully.');
    }

    public function uploadAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        return $this->handleUpdate(
            $request,
            fn ($user, $data) => $this->profileService->uploadAvatar($user, $data),
            'Avatar uploaded successfully.'
        );
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        return $this->handleSimple($request, 'deleteAvatar', 'Avatar deleted successfully.');
    }

    public function deleteWholesaleClientLogo(Request $request): RedirectResponse
    {
        return $this->handleSimple($request, 'deleteWholesaleClientLogo', 'Logo removed successfully.');
    }

    public function deleteRetailerClientLogo(Request $request): RedirectResponse
    {
        return $this->handleSimple($request, 'deleteRetailerClientLogo', 'Logo removed successfully.');
    }

    public function updateNotificationPreference(UpdateNotificationRequest $request): RedirectResponse
    {
        return $this->handleUpdate(
            $request,
            fn ($user, $data) => $this->profileService->updateNotificationPreference($user, $data),
            'Notification preferences updated successfully.'
        );
    }

    /* ---------------- Helpers ---------------- */

    private function handleUpdate($request, callable $action, string $message): RedirectResponse
    {
        $user = $request->user();
        $action($user, $request->validated());

        return $this->successRedirect($user, $message);
    }

    private function handleSimple(Request $request, string $method, string $message): RedirectResponse
    {
        $user = $request->user();
        $this->profileService->$method($user);

        return $this->successRedirect($user, $message);
    }

    private function successRedirect(User $user, string $message): RedirectResponse
    {
        return redirect()
            ->route($this->resolveRoutePrefix($user).'.profile.edit')
            ->with('success', $message);
    }

    private function resolveRoutePrefix(User $user): string
    {
        return match (true) {
            $user->hasRole('Super Admin') => 'admin',
            $user->hasRole('Wholesale') => 'client',
            $user->hasRole('Retailer') => 'retailer',
            $user->hasRole('customer') => 'customer',
            default => 'admin',
        };
    }
}
