<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'role',
        'owner_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, ['admin', 'owner', 'staff']);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->owner_id)) {
                $user = auth()->user();
                if ($user->role === 'owner') {
                    $model->owner_id = $user->id;
                }
            }
        });
    }
}
