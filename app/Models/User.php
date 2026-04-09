<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'shift',
        'status_override'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status_override' => 'boolean',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ==============================
    // 🔥 SHIFT ACTIVE (REALTIME)
    // ==============================
    public function isShiftActive(): bool
    {
        $hour = now()->hour;

        return match ($this->shift) {
            '1' => $hour >= 5  && $hour < 12,
            '2' => $hour >= 12 && $hour < 19,
            '3' => $hour >= 19 || $hour < 2,
            default => false,
        };
    }

    // ==============================
    // 🔥 STATUS FINAL (REALTIME)
    // ==============================
    public function getEffectiveStatusAttribute(): string
    {
        // kalau di override manual
        if ($this->status_override) {
            return $this->status;
        }

        // kalau petugas + ada shift → ikut jam
        if ($this->role === 'petugas' && $this->shift) {
            return $this->isShiftActive() ? 'aktif' : 'nonaktif';
        }

        // selain itu pakai status biasa
        return $this->status;
    }
}
