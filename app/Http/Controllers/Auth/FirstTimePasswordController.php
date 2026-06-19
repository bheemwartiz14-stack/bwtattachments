<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class FirstTimePasswordController extends Controller
{
    public function showForm(): View
    {
        return view('auth.first-time-password');
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->password),
            'is_first_time' => false,
        ]);

        return redirect()->intended(
            $user->hasRole('Super Admin')
                ? route('admin.dashboard')
                : route('client.dashboard')
        );
    }
}
