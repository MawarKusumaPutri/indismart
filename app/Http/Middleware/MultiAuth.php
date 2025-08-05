<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiAuth
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if ($guard) {
            $sessionKey = 'login_' . $guard . '_' . auth()->id();
            if (!session()->has($sessionKey)) {
                Auth::guard($guard)->logout();
                return redirect('/login');
            }
        }

        return $next($request);
    }
}