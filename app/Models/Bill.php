<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Bill extends Model
{
    protected $fillable = [
        'owner_id',
        'challan_id',
        'bill_number',
        'total_meters',
        'rate',
        'amount',
        'cgst_amount',
        'sgst_amount',
        'final_total',
    ];

    protected static function booted()
    {
        static::addGlobalScope('owner', function (Builder $builder) {
            if (auth()->check() && auth()->user()->role !== 'admin') {
                $ownerId = auth()->user()->role === 'owner'
                    ? auth()->id()
                    : auth()->user()->owner_id;
                $builder->where('owner_id', $ownerId);
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
                // role === 'admin' must provide owner_id via form
            }
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function challan()
    {
        return $this->belongsTo(Challan::class);
    }
}
