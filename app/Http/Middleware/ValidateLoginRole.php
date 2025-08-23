<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ValidateLoginRole
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
        // Hanya validasi untuk request login
        if ($request->is('login') && $request->isMethod('post')) {
            $loginAs = $request->input('login_as');
            $email = $request->input('email');
            
            // Jika mencoba login sebagai karyawan, validasi email
            if ($loginAs === 'staff') {
                // Hanya email karyawan yang diizinkan
                if ($email !== 'karyawan@telkom.co.id') {
                    Log::warning('Percobaan login karyawan dengan email tidak sah: ' . $email . ' - ' . $request->ip());
                    
                    return back()->withErrors([
                        'email' => 'Email ini tidak memiliki akses sebagai Karyawan. Hanya email karyawan@telkom.co.id yang diizinkan.'
                    ])->withInput($request->except('password'));
                }
            }
            
            // Jika mencoba login sebagai mitra, pastikan bukan email karyawan
            if ($loginAs === 'mitra') {
                if ($email === 'karyawan@telkom.co.id') {
                    Log::warning('Percobaan login mitra dengan email karyawan: ' . $email . ' - ' . $request->ip());
                    
                    return back()->withErrors([
                        'login_as' => 'Email karyawan tidak bisa digunakan untuk login sebagai Mitra. Silakan pilih role Karyawan.'
                    ])->withInput($request->except('password'));
                }
            }
        }

        return $next($request);
    }
}
