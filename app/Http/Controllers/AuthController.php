<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'login_as' => 'required|in:mitra,staff',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Validasi ketat: User hanya bisa login sesuai role aslinya
            if ($request->login_as === 'staff') {
                // Jika mencoba login sebagai karyawan, pastikan user benar-benar karyawan
                if (!$user->isKaryawan()) {
                    Auth::logout();
                    return back()->withErrors([
                        'login_as' => 'Akun ini tidak memiliki akses sebagai Karyawan. Silakan pilih role Mitra.'
                    ])->withInput($request->except('password'));
                }
                
                // Log aktivitas login karyawan untuk audit trail
                \Log::info('User karyawan login: ' . $user->email . ' - ' . now());
                
                $request->session()->regenerate();
                return redirect()->intended('staff/dashboard');
            } 
            
            if ($request->login_as === 'mitra') {
                // Jika mencoba login sebagai mitra, pastikan user benar-benar mitra
                if (!$user->isMitra()) {
                    Auth::logout();
                    return back()->withErrors([
                        'login_as' => 'Akun ini tidak memiliki akses sebagai Mitra. Silakan pilih role Karyawan.'
                    ])->withInput($request->except('password'));
                }
                
                // Log aktivitas login mitra untuk audit trail
                \Log::info('User mitra login: ' . $user->email . ' - ' . now());
                
                $request->session()->regenerate();
                return redirect()->intended('mitra/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ]);
    }

    /**
     * Tampilkan halaman registrasi
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi
     */
    public function register(Request $request)
    {
        // Mencegah registrasi sebagai karyawan
        if ($request->role === 'staff') {
            return back()->withErrors([
                'role' => 'Registrasi sebagai Karyawan tidak diizinkan. Silakan hubungi administrator.'
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:mitra',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        Auth::login($user);

        // Kirim notifikasi ke staff jika yang registrasi adalah mitra
        if ($user->isMitra()) {
            NotificationService::notifyMitraRegistration($user);
        }

        // Redirect berdasarkan role
        if ($user->isKaryawan()) {
            return redirect('staff/dashboard');
        } else {
            return redirect('mitra/dashboard');
        }
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        $guard = null;
        
        // Tentukan guard yang sedang aktif berdasarkan URL
        if (str_contains($request->url(), '/mitra/')) {
            $guard = 'mitra';
        } elseif (str_contains($request->url(), '/staff/')) {
            $guard = 'staff';
        }
        
        if ($guard) {
            // Hapus session untuk guard ini saja
            $request->session()->forget($guard . '_session_id');
            Auth::guard($guard)->logout();
            
            // Regenerate token tapi jangan invalidate semua session
            $request->session()->regenerateToken();
        } else {
            // Jika tidak ada guard spesifik, logout dari semua
            Auth::guard('mitra')->logout();
            Auth::guard('staff')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect('/');
    }
}