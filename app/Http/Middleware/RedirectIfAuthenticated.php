<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if ($user->hasRole('Super Admin')) {
                    return redirect()->route('admin.dashboard');
                }
                if ($user->hasRole('Wholesale Client')) {
                    return redirect()->route('client.dashboard');
                }
                if ($user->hasRole('Retailer')) {
                    return redirect()->route('retailer.dashboard');
                }
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
