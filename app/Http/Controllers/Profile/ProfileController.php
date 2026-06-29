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

    /**
     * Show the profile edit form.
     */
    public function edit(): View
    {
        /** @var User $user */
        $user = auth()->user()->load('userMeta');

        $viewData = [
            'user' => $user,
            'prefix' => $this->resolveRoutePrefix($user),
        ];

        if ($user->hasRole('Wholesale Client')) {
            $meta = $user->userMeta;
            $viewData['wholesaleClientName'] = $meta?->metadata['client_name'] ?? '';
            $logoMedia = $meta?->getFirstMedia('wholesale_client_logo');
            $viewData['wholesaleClientLogoUrl'] = $logoMedia?->getUrl();
            $viewData['wholesaleClientLogoId'] = $logoMedia?->id;
        }

        if ($user->hasRole('Retailer')) {
            $meta = $user->userMeta;
            $viewData['retailerClientName'] = $meta?->metadata['client_name'] ?? '';
            $logoMedia = $user->getFirstMedia('retailer_client_logo');
            $viewData['retailerClientLogoUrl'] = $logoMedia?->getUrl();
            $viewData['retailerClientLogoId'] = $logoMedia?->id;
        }

        return view('profile.edit', $viewData);
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->profileService->updateProfile($user, $request->validated());

        return redirect()
            ->route($this->resolveRoutePrefix($user) . '.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $changed = $this->profileService->changePassword(
            $user,
            $request->input('current_password'),
            $request->input('password'),
        );

        if (!$changed) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        return redirect()
            ->route($this->resolveRoutePrefix($user) . '.profile.edit')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * Upload avatar for the user.
     */
    public function uploadAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->profileService->uploadAvatar($user, $request->validated());

        return redirect()
            ->route($this->resolveRoutePrefix($user) . '.profile.edit')
            ->with('success', 'Avatar uploaded successfully.');
    }

    /**
     * Delete the user's avatar.
     */
    public function deleteAvatar(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->profileService->deleteAvatar($user);

        return redirect()
            ->route($this->resolveRoutePrefix($user) . '.profile.edit')
            ->with('success', 'Avatar deleted successfully.');
    }

    /**
     * Delete wholesale client logo.
     */
    public function deleteWholesaleClientLogo(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->profileService->deleteWholesaleClientLogo($user);

        return redirect()
            ->route($this->resolveRoutePrefix($user) . '.profile.edit')
            ->with('success', 'Logo removed successfully.');
    }

    /**
     * Delete retailer client logo.
     */
    public function deleteRetailerClientLogo(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->profileService->deleteRetailerClientLogo($user);

        return redirect()
            ->route($this->resolveRoutePrefix($user) . '.profile.edit')
            ->with('success', 'Logo removed successfully.');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotificationPreference(UpdateNotificationRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $this->profileService->updateNotificationPreference($user, $request->validated());

        return redirect()
            ->route($this->resolveRoutePrefix($user) . '.profile.edit')
            ->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Resolve the route prefix based on the user's role.
     */
    private function resolveRoutePrefix(User $user): string
    {
        if ($user->hasRole('Super Admin')) {
            return 'admin';
        }

        if ($user->hasRole('Wholesale Client')) {
            return 'client';
        }

        if ($user->hasRole('Retailer')) {
            return 'retailer';
        }

        return 'admin';
    }
}
