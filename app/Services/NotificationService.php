<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Dokumen;

class NotificationService
{
    /**
     * Kirim notifikasi ke semua staff
     */
    public static function notifyStaff($title, $message, $type = 'info', $data = [])
    {
        $staffUsers = User::where('role', 'staff')->get();
        
        foreach ($staffUsers as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => $data,
            ]);
        }
    }

    /**
     * Notifikasi ketika mitra upload dokumen
     */
    public static function notifyDokumenUploaded(Dokumen $dokumen)
    {
        $mitra = $dokumen->user;
        
        $title = 'Dokumen Baru Diupload';
        $message = "Mitra {$mitra->name} telah mengupload dokumen baru: {$dokumen->jenis_proyek}";
        
        $data = [
            'dokumen_id' => $dokumen->id,
            'mitra_name' => $mitra->name,
            'jenis_proyek' => $dokumen->jenis_proyek,
            'lokasi' => "{$dokumen->witel} - {$dokumen->sto} - {$dokumen->site_name}",
            'status' => $dokumen->status_implementasi,
        ];

        self::notifyStaff($title, $message, 'info', $data);
    }

    /**
     * Notifikasi ketika mitra update dokumen
     */
    public static function notifyDokumenUpdated(Dokumen $dokumen)
    {
        $mitra = $dokumen->user;
        
        $title = 'Dokumen Diperbarui';
        $message = "Mitra {$mitra->name} telah memperbarui dokumen: {$dokumen->jenis_proyek}";
        
        $data = [
            'dokumen_id' => $dokumen->id,
            'mitra_name' => $mitra->name,
            'jenis_proyek' => $dokumen->jenis_proyek,
            'lokasi' => "{$dokumen->witel} - {$dokumen->sto} - {$dokumen->site_name}",
            'status' => $dokumen->status_implementasi,
        ];

        self::notifyStaff($title, $message, 'warning', $data);
    }

    /**
     * Notifikasi ketika mitra hapus dokumen
     */
    public static function notifyDokumenDeleted($dokumenData)
    {
        $title = 'Dokumen Dihapus';
        $message = "Dokumen telah dihapus oleh mitra";
        
        $data = [
            'mitra_name' => $dokumenData['mitra_name'] ?? 'Unknown',
            'jenis_proyek' => $dokumenData['jenis_proyek'] ?? 'Unknown',
        ];

        self::notifyStaff($title, $message, 'error', $data);
    }

    /**
     * Get jumlah notifikasi yang belum dibaca untuk user
     */
    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get notifikasi yang belum dibaca untuk user
     */
    public static function getUnreadNotifications($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notifikasi sebagai sudah dibaca
     */
    public static function markAsRead($notificationId, $userId)
    {
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark semua notifikasi sebagai sudah dibaca
     */
    public static function markAllAsRead($userId)
    {
        return Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Kirim notifikasi ke user tertentu
     */
    public static function notifyUser($userId, $title, $message, $type = 'info', $data = [])
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }
} 