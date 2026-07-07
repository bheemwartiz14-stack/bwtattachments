<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotForm(): View
    {
        return view('pages.public.auth.forgot-password');
    }

    /**
     * Send reset password link.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => __('We can\'t find a user with that email address.'),
            ]);
        }

        // Create reset token
        $token = Password::broker()->createToken($user);

        // Generate reset URL
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);
        // Send email normally
        $status = Password::sendResetLink([
            'email' => $user->email,
        ]);
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors([
                'email' => __($status),
            ]);
    }
}
