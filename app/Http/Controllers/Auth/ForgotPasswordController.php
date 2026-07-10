<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
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
     * Send the password reset link.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        Log::info('Forgot password request received.', [
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        Log::info('Validation passed.', [
            'email' => $request->email,
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            Log::info('Password::sendResetLink() executed.', [
                'email' => $request->email,
                'status' => $status,
            ]);

            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Password reset email sent successfully.', [
                    'email' => $request->email,
                ]);

                return back()->with('status', __($status));
            }

            Log::warning('Password reset email was not sent.', [
                'email' => $request->email,
                'status' => $status,
            ]);

            return back()->withErrors([
                'email' => __($status),
            ]);
        } catch (\Throwable $e) {
            Log::error('Exception while sending password reset link.', [
                'email' => $request->email,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()->withErrors([
                'email' => 'An unexpected error occurred while sending the reset link.',
            ]);
        }
    }
}
