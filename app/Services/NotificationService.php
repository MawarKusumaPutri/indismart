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
        $namaProyek = self::generateNamaProyek($dokumen);
        
        $title = '📄 Dokumen Baru Diupload';
        $message = "Mitra {$mitra->name} telah mengupload dokumen '{$dokumen->nama_dokumen}' dengan jenis proyek {$dokumen->jenis_proyek}";
        
        $data = [
            'dokumen_id' => $dokumen->id,
            'mitra_name' => $mitra->name,
            'nama_proyek' => $namaProyek,
            'jenis_proyek' => $dokumen->jenis_proyek,
            'lokasi' => "{$dokumen->witel} - {$dokumen->sto} - {$dokumen->site_name}",
            'status' => $dokumen->status_implementasi,
            'tanggal_upload' => $dokumen->created_at->format('d M Y H:i'),
            'has_file' => $dokumen->file_path ? true : false,
            'action_url' => route('dokumen.show', $dokumen->id),
        ];

        self::notifyStaff($title, $message, 'success', $data);
    }

    /**
     * Notifikasi ketika mitra update dokumen
     */
    public static function notifyDokumenUpdated(Dokumen $dokumen)
    {
        $mitra = $dokumen->user;
        $namaProyek = self::generateNamaProyek($dokumen);
        
        $title = '✏️ Dokumen Diperbarui';
        $message = "Mitra {$mitra->name} telah memperbarui dokumen '{$dokumen->nama_dokumen}' dengan jenis proyek {$dokumen->jenis_proyek}";
        
        $data = [
            'dokumen_id' => $dokumen->id,
            'mitra_name' => $mitra->name,
            'nama_proyek' => $namaProyek,
            'jenis_proyek' => $dokumen->jenis_proyek,
            'lokasi' => "{$dokumen->witel} - {$dokumen->sto} - {$dokumen->site_name}",
            'status' => $dokumen->status_implementasi,
            'tanggal_update' => $dokumen->updated_at->format('d M Y H:i'),
            'has_file' => $dokumen->file_path ? true : false,
            'action_url' => route('dokumen.show', $dokumen->id),
        ];

        self::notifyStaff($title, $message, 'warning', $data);
    }

    /**
     * Notifikasi ketika mitra hapus dokumen
     */
    public static function notifyDokumenDeleted($dokumenData)
    {
        $title = '🗑️ Dokumen Dihapus';
        $message = "Mitra {$dokumenData['mitra_name']} telah menghapus dokumen '{$dokumenData['nama_dokumen']}' dengan jenis proyek {$dokumenData['jenis_proyek']}";
        
        $data = [
            'mitra_name' => $dokumenData['mitra_name'] ?? 'Unknown',
            'nama_proyek' => $dokumenData['nama_proyek'] ?? 'Unknown',
            'jenis_proyek' => $dokumenData['jenis_proyek'] ?? 'Unknown',
            'lokasi' => $dokumenData['lokasi'] ?? 'Unknown',
            'tanggal_hapus' => now()->format('d M Y H:i'),
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

    /**
     * Generate nama proyek berdasarkan data dokumen
     */
    private static function generateNamaProyek(Dokumen $dokumen)
    {
        // Buat nama proyek berdasarkan jenis proyek, lokasi, dan nomor kontak
        $jenisShort = self::getShortJenisProyek($dokumen->jenis_proyek);
        $lokasiShort = $dokumen->sto . '-' . $dokumen->site_name;
        $nomorKontak = $dokumen->nomor_kontak;
        
        return "{$jenisShort} {$lokasiShort} ({$nomorKontak})";
    }

    /**
     * Get singkatan jenis proyek
     */
    private static function getShortJenisProyek($jenisProyek)
    {
        $mapping = [
            'Instalasi Baru' => 'IB',
            'Migrasi' => 'MIG',
            'Upgrade' => 'UPG',
            'Maintenance' => 'MNT',
            'Troubleshooting' => 'TRB',
            'Survey' => 'SRV',
            'Audit' => 'AUD',
            'Lainnya' => 'LYN'
        ];

        return $mapping[$jenisProyek] ?? 'UNK';
    }
} 