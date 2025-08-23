<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'fotos';

    protected $fillable = [
        'dokumen_id',
        'file_path',
        'original_name',
        'caption',
        'order',
    ];

    /**
     * Relasi dengan Dokumen
     */
    public function dokumen()
    {
        return $this->belongsTo(Dokumen::class);
    }

    /**
     * Get foto URL
     */
    public function getFotoUrlAttribute()
    {
        if ($this->file_path) {
            return Storage::url($this->file_path);
        }
        return null;
    }

    /**
     * Get foto name
     */
    public function getFotoNameAttribute()
    {
        if ($this->file_path) {
            return basename($this->file_path);
        }
        return null;
    }

    /**
     * Get foto size in KB
     */
    public function getFotoSizeAttribute()
    {
        if ($this->file_path && Storage::exists($this->file_path)) {
            return round(Storage::size($this->file_path) / 1024, 2);
        }
        return null;
    }

    /**
     * Scope untuk mengurutkan berdasarkan order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }

    /**
     * Scope untuk filter berdasarkan dokumen
     */
    public function scopeByDokumen($query, $dokumenId)
    {
        return $query->where('dokumen_id', $dokumenId);
    }
}
