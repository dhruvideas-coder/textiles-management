<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'plan_id',
        'status',
        'started_at',
        'trial_ends_at',
        'ends_at',
        'current_period_start',
        'current_period_end',
        'gateway',
        'gateway_customer_id',
        'gateway_subscription_id',
        'last_payment_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'date',
            'trial_ends_at' => 'date',
            'ends_at' => 'date',
            'current_period_start' => 'date',
            'current_period_end' => 'date',
            'last_payment_at' => 'datetime',
            'meta' => 'array',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
