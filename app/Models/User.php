<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Tambahkan role ke fillable
        'nomor_kontrak',
        'phone',
        'address',
        'avatar',
        'birth_date',
        'gender',
        'email_notifications',
        'document_upload_notifications',
        'review_notifications',
        'system_notifications',
        'theme',
        'language',
        'sidebar_collapsed',
        'compact_mode',
        'last_password_change',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
        ];
    }
    
    /**
     * Check if user is mitra
     *
     * @return bool
     */
    public function isMitra(): bool
    {
        return $this->role === 'mitra';
    }

    /**
     * Check if user is karyawan
     *
     * @return bool
     */
    public function isKaryawan(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Get all documents belonging to the user
     */
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class);
    }

    /**
     * Get all notifications belonging to the user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
