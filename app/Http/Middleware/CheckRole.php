<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        if ($role == 'mitra' && !Auth::user()->isMitra()) {
            return redirect('staff/dashboard');
        }

        if ($role == 'staff' && !Auth::user()->isKaryawan()) {
            return redirect('mitra/dashboard');
        }

        return $next($request);
    }
}