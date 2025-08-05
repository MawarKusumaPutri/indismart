<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $notification = Notification::findOrFail($request->notification_id);
        
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