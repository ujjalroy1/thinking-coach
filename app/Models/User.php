<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    // True if user is on premium plan
    public function isPremium(): bool
    {
        return $this->role === 'premium';
    }

    // Relationship: one user has many entries
    public function entries()
    {
        return $this->hasMany(\App\Models\Entrie::class);
    }

    // Relationship: one user has one streak record
    public function streak()
    {
        return $this->hasOne(\App\Models\Streak::class);
    }

    // Count today's entries
    public function todayEntryCount(): int
    {
        return $this->entries()
            ->whereDate('created_at', today())
            ->count();
    }

    // Free plan limit check
    public function canSubmitEntry(): bool
    {
        if ($this->isPremium()) return true;
        return $this->todayEntryCount() < 30;
    }
}