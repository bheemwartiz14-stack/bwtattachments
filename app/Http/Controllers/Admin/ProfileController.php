<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdateProfileDetails;
use App\Http\Requests\Admin\Profile\UpdateProfilePassword;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function edit(): View
    {
        return view('admin.profile.edit');
    }

    public function update(UpdateProfileDetails $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(UpdateProfilePassword $request): RedirectResponse
    {
        if (!$this->authService->changePassword($request->user(), $request->input('current_password'), $request->input('password'))) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        return redirect()->route('admin.profile.edit')->with('success', 'Password changed successfully.');
    }
}
