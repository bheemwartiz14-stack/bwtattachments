<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
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

        return view('pages.private.profile.edit', [
            'user' => $user,
            'avatarUrl' => $avatar?->getUrl(),
            'avatarId' => $avatar?->id,
            'prefix' => 'admin',
            'breadcrumbLabel' => 'Admin',
            'breadcrumbRoute' => 'admin.dashboard',
            'hasCompany' => false,
            'roleLabel' => '',
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $this->profileService->updateProfile($user, $request->validated());

        return redirect()
            ->route('admin.profile.edit')
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
            ->route('admin.profile.edit')
            ->with('success', 'Avatar uploaded successfully.');
    }

    public function deleteAvatar(Request $request): RedirectResponse
    {
        $this->profileService->deleteAvatar($request->user());
        return redirect()  ->route('admin.profile.edit')  ->with('success', 'Avatar deleted successfully.');
    }
}
