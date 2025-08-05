<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        
        if ($role === 'mitra' && !$user->isMitra()) {
            Auth::logout();
            return redirect('/login')->with('error', 'Akses ditolak. Anda bukan Mitra.');
        }

        if ($role === 'staff' && !$user->isStaff()) {
            Auth::logout();
            return redirect('/login')->with('error', 'Akses ditolak. Anda bukan Staff.');
        }

        return $next($request);
    }
}