<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = $user->notifications()->orderBy('created_at', 'desc');
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
            // 'all' tidak perlu filter tambahan
        }
        
        // Filter berdasarkan type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $notifications = $query->paginate(15)->withQueryString();
        
        // Statistik notifikasi
        $stats = [
            'total' => $user->notifications()->count(),
            'unread' => $user->notifications()->unread()->count(),
            'read' => $user->notifications()->read()->count(),
            'today' => $user->notifications()->whereDate('created_at', today())->count(),
        ];
        
        // Jenis notifikasi yang tersedia
        $types = $user->notifications()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return view('notifications.index', compact('notifications', 'stats', 'types'));
    }

    public function markAsRead(Request $request)
    {
        // Handle both route parameter and JSON body
        $notificationId = $request->route('notification') ?? $request->notification_id;
        
        $notification = Notification::findOrFail($notificationId);
        
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['success' => false], 403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()
            ->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()
            ->notifications()
            ->unread()
            ->count();

        return response()->json(['count' => $count]);
    }

    public function getUnreadNotifications()
    {
        $notifications = Auth::user()
            ->notifications()
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($notifications);
    }
}