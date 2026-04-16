<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'owner_id',
        'name',
        'default_rate',
        'last_used_rate',
    ];

    protected static function booted()
    {
        static::addGlobalScope('owner', function (Builder $builder) {
            if (auth()->check() && auth()->user()->role !== 'admin') {
                $ownerId = auth()->user()->role === 'owner'
                    ? auth()->id()
                    : auth()->user()->owner_id;
                $builder->where(function ($q) use ($ownerId) {
                    $q->where('owner_id', $ownerId)
                      ->orWhereNull('owner_id');
                });
            }
        });

        static::creating(function ($model) {
            if (auth()->check() && empty($model->owner_id)) {
                $user = auth()->user();
                if ($user->role === 'owner') {
                    $model->owner_id = $user->id;
                } elseif ($user->role === 'staff') {
                    $model->owner_id = $user->owner_id;
                }
                // If admin, owner_id remains null (global record)
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
