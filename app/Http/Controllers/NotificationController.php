<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get jumlah notifikasi yang belum dibaca
     */
    public function getUnreadCount()
    {
        $count = NotificationService::getUnreadCount(Auth::id());
        return response()->json(['count' => $count]);
    }

    /**
     * Get notifikasi yang belum dibaca
     */
    public function getUnreadNotifications()
    {
        $notifications = NotificationService::getUnreadNotifications(Auth::id(), 10);
        return response()->json($notifications);
    }

    /**
     * Mark notifikasi sebagai sudah dibaca
     */
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');
        $success = NotificationService::markAsRead($notificationId, Auth::id());
        
        return response()->json(['success' => $success]);
    }

    /**
     * Mark semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $success = NotificationService::markAllAsRead(Auth::id());
        return response()->json(['success' => $success]);
    }

    /**
     * Tampilkan halaman notifikasi
     */
    public function index()
    {
        $notifications = \App\Models\Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
} 