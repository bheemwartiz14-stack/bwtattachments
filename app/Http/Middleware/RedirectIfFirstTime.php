<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfFirstTime
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->is_first_time && !$request->routeIs('first-time-password.*', 'logout')) {
            return redirect()->route('first-time-password.form');
        }

        return $next($request);
    }
}
