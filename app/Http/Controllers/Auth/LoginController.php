<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function showLoginForm(): View
    {
        return view('pages.public.auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('login', 'password');
        $remember = $request->boolean('remember');

        $loginStatus = $this->authService->login($credentials, $remember);
        if (! $loginStatus) {
            return back()
                ->withErrors([
                    'login' => 'The provided credentials do not match our records.',
                ])
                ->onlyInput('login');
        }
        $request->session()->regenerate();
        $user = auth()->user();
        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('Reseller')) {
            return redirect()->route('reseller.dashboard');
        }
        if ($user->hasRole('customer')) {
            return redirect()->route('customer.dashboard');
        }
        if ($user->hasRole('Wholesaler')) {
            return redirect()->route('client.dashboard');
        }

        return redirect()->route('client.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
