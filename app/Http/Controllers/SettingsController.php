<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Tampilkan halaman pengaturan utama
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('settings.index', compact('user'));
    }

    /**
     * Tampilkan pengaturan profil
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('settings.profile', compact('user'));
    }

    /**
     * Update pengaturan profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->birth_date = $request->birth_date;
            $user->gender = $request->gender;

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = $avatarPath;
            }

            $user->save();

            return back()->with('success', 'Profil berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pengaturan keamanan
     */
    public function security()
    {
        return view('settings.security');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui password: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pengaturan notifikasi
     */
    public function notifications()
    {
        $user = Auth::user();
        
        return view('settings.notifications', compact('user'));
    }

    /**
     * Update pengaturan notifikasi
     */
    public function updateNotifications(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'document_upload_notifications' => 'boolean',
            'review_notifications' => 'boolean',
            'system_notifications' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            // Update notification settings with safe defaults
            $user->email_notifications = $request->has('email_notifications') ? true : false;
            $user->document_upload_notifications = $request->has('document_upload_notifications') ? true : false;
            $user->review_notifications = $request->has('review_notifications') ? true : false;
            $user->system_notifications = $request->has('system_notifications') ? true : false;
            
            $user->save();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Pengaturan notifikasi berhasil diperbarui!']);
            }
            return back()->with('success', 'Pengaturan notifikasi berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error updating notification settings: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui pengaturan notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pengaturan tampilan
     */
    public function appearance()
    {
        return view('settings.appearance');
    }

    /**
     * Update pengaturan tampilan
     */
    public function updateAppearance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'required|in:light,dark,auto',
            'language' => 'required|in:id,en',
            'sidebar_collapsed' => 'boolean',
            'compact_mode' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $user = Auth::user();
            
            // Update appearance settings with safe defaults
            $user->theme = $request->theme ?? 'light';
            $user->language = $request->language ?? 'id';
            $user->sidebar_collapsed = $request->has('sidebar_collapsed') ? true : false;
            $user->compact_mode = $request->has('compact_mode') ? true : false;
            
            $user->save();

            return back()->with('success', 'Pengaturan tampilan berhasil diperbarui!');
        } catch (\Exception $e) {
            \Log::error('Error updating appearance settings: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui pengaturan tampilan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan pengaturan sistem
     */
    public function system()
    {
        return view('settings.system');
    }

    /**
     * Clear cache
     */
    public function clearCache()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
            
            return back()->with('success', 'Cache berhasil dibersihkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * Export data user
     */
    public function exportData()
    {
        try {
            $user = Auth::user();
            
            $data = [
                'user_info' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'birth_date' => $user->birth_date,
                    'gender' => $user->gender,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ],
                'documents' => $user->dokumen()->get()->map(function($doc) {
                    return [
                        'nama_dokumen' => $doc->nama_dokumen,
                        'jenis_proyek' => $doc->jenis_proyek,
                        'witel' => $doc->witel,
                        'status_implementasi' => $doc->status_implementasi,
                        'tanggal_dokumen' => $doc->tanggal_dokumen,
                        'created_at' => $doc->created_at
                    ];
                }),
                'notifications' => $user->notifications()->get()->map(function($notif) {
                    return [
                        'title' => $notif->title,
                        'message' => $notif->message,
                        'type' => $notif->type,
                        'read_at' => $notif->read_at,
                        'created_at' => $notif->created_at
                    ];
                })
            ];

            $filename = 'user_data_' . $user->id . '_' . date('Y-m-d_H-i-s') . '.json';
            
            return response()->json($data, 200, [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat export data: ' . $e->getMessage());
        }
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'confirmation' => 'required|in:DELETE'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Password tidak sesuai']);
        }

        try {
            // Delete user's documents
            foreach ($user->dokumen as $dokumen) {
                if ($dokumen->file_path && Storage::disk('public')->exists($dokumen->file_path)) {
                    Storage::disk('public')->delete($dokumen->file_path);
                }
                $dokumen->delete();
            }

            // Delete user's avatar
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Delete user's notifications
            $user->notifications()->delete();

            // Delete user
            $user->delete();

            Auth::logout();
            
            return redirect()->route('login')->with('success', 'Akun berhasil dihapus. Terima kasih telah menggunakan Indismart!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus akun: ' . $e->getMessage());
        }
    }
} 