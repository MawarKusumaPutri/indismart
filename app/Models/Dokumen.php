<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'user_id',
        'nama_dokumen',
        'jenis_proyek',
        'nomor_kontrak',
        'witel',
        'sto',
        'site_name',
        'status_implementasi',
        'jenis_dokumen',
        'tanggal_dokumen',
        'file_path',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
    ];

    /**
     * Relasi dengan User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan Review
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relasi dengan Foto
     */
    public function fotos()
    {
        return $this->hasMany(Foto::class)->ordered();
    }

    /**
     * Get latest review
     */
    public function latestReview()
    {
        return $this->hasOne(Review::class)->latest();
    }

    /**
     * Check apakah dokumen sudah direview
     */
    public function isReviewed()
    {
        return $this->reviews()->exists();
    }

    /**
     * Check apakah dokumen disetujui
     */
    public function isApproved()
    {
        return $this->reviews()->where('status', 'approved')->exists();
    }

    /**
     * Check apakah dokumen ditolak
     */
    public function isRejected()
    {
        return $this->reviews()->where('status', 'rejected')->exists();
    }

    /**
     * Check apakah dokumen pending review
     */
    public function isPendingReview()
    {
        return !$this->isReviewed() || $this->reviews()->where('status', 'pending')->exists();
    }

    /**
     * Get status review
     */
    public function getReviewStatus()
    {
        if (!$this->isReviewed()) {
            return 'pending';
        }
        
        $latestReview = $this->latestReview;
        return $latestReview ? $latestReview->status : 'pending';
    }

    /**
     * Get status badge class
     */
    public function getReviewStatusBadgeClass()
    {
        return match($this->getReviewStatus()) {
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'pending' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status text
     */
    public function getReviewStatusText()
    {
        return match($this->getReviewStatus()) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'pending' => 'Menunggu Review',
            default => 'Unknown'
        };
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    /**
     * Get file name
     */
    public function getFileNameAttribute()
    {
        if ($this->file_path) {
            return basename($this->file_path);
        }
        return null;
    }

    /**
     * Get file size in KB
     */
    public function getFileSizeAttribute()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            return round(Storage::size($this->file_path) / 1024, 2);
        }
        return null;
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan status implementasi
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status_implementasi', $status);
    }

    /**
     * Scope untuk filter berdasarkan jenis proyek
     */
    public function scopeByJenisProyek($query, $jenis)
    {
        return $query->where('jenis_proyek', $jenis);
    }
} 