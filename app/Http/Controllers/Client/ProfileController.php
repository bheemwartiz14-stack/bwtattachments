<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function edit(): View
    {
        $user = auth()->user();

        return view('client.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return redirect()->route('client.profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        $changed = $this->authService->changePassword(
            $user,
            $request->input('current_password'),
            $request->input('password')
        );

        if (!$changed) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        return redirect()->route('client.profile.edit')->with('success', 'Password changed successfully.');
    }
}
