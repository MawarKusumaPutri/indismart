<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'dokumen_id',
        'reviewer_id', // karyawan yang review
        'status', // approved, rejected, pending
        'komentar',
        'rating', // 1-5 stars
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Relasi dengan Dokumen
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    /**
     * Relasi dengan User (Karyawan yang review)
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Scope untuk review yang sudah disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk review yang ditolak
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope untuk review yang pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check apakah review sudah disetujui
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check apakah review ditolak
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check apakah review pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'pending' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    /**
     * Get status text
     */
    public function getStatusText()
    {
        return match($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'pending' => 'Menunggu Review',
            default => 'Unknown'
        };
    }
} 