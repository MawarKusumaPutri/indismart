<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isMitra()) {
            Auth::logout();
            return redirect('/login');
        }

        return $next($request);
    }
}