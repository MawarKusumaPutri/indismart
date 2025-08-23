<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class PreventKaryawanRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah sudah ada user karyawan
        $karyawanExists = User::where('role', 'staff')->exists();
        
        if ($karyawanExists) {
            // Jika sudah ada karyawan, redirect ke login dengan pesan
            return redirect()->route('login')->with('info', 'Sistem karyawan sudah tersedia. Silakan login dengan akun yang ada.');
        }

        return $next($request);
    }
}
