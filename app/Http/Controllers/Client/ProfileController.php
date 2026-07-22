<?php

declare(strict_types=1);

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\Profile\UpdatePasswordRequest;
use App\Http\Requests\Client\Profile\UpdateProfileRequest;
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
        $user = auth()->user()->load(['userMeta', 'userMargin']);

        $avatar = $user->getFirstMedia('avatar');

        $viewData = [
            'user' => $user,
            'avatarUrl' => $avatar?->getUrl(),
            'avatarId' => $avatar?->id,
        ];
        $meta = $user->userMeta?->metadata ?? [];
        $viewData['company_name'] = $meta['wholesale_company_name'] ?? '';
        $logo = $user->getFirstMedia('wholesale_client_logo');
        $viewData['logo'] = $logo?->getUrl();
        $viewData['logo_id'] = $logo?->id;
        $viewData['prefix'] = 'client';
        $viewData['breadcrumbLabel'] = 'Client Portal';
        $viewData['breadcrumbRoute'] = 'client.dashboard';
        $viewData['hasCompany'] = true;
        $viewData['roleLabel'] = 'Wholesale';
        $viewData['commissionPercentage'] = $user->userMargin?->margin_value;

        return view('pages.private.client.profile.edit', $viewData);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $this->profileService->updateProfile($user, $request->validated());

        return redirect()
            ->route('client.profile.edit', ['tab' => 'personal'])
            ->with('success', 'Profile updated successfully.');
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

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('status', 'Password changed successfully. Please login with your new password.');
    }

    public function uploadAvatar(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ]);

        $this->profileService->uploadAvatar($request->user(), $data);

        return redirect()
            ->route('client.profile.edit')
            ->with('success', 'Avatar uploaded successfully.');
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $this->profileService->deleteAvatar($request->user());

        return redirect()
            ->route('client.profile.edit')
            ->with('success', 'Avatar deleted successfully.');
    }

    public function deleteWholesaleClientLogo(Request $request): RedirectResponse
    {
        $this->profileService->deleteWholesaleClientLogo($request->user());

        return redirect()
            ->route('client.profile.edit', ['tab' => 'company'])
            ->with('success', 'Company logo removed successfully.');
    }
}
