<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Primary key sesuai tabel lo
    protected $primaryKey = 'id_user';

    // Role constants
    const ROLE_ADMIN   = 'admin';
    const ROLE_PETUGAS = 'petugas';
    const ROLE_OWNER   = 'owner';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isPetugas(): bool
    {
        return $this->role === self::ROLE_PETUGAS;
    }
    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }
    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
