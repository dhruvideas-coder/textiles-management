<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public const CODE_FREE = 'free';

    public const CODE_PREMIUM = 'premium';

    protected $fillable = [
        'name',
        'code',
        'monthly_price',
        'currency',
        'billing_cycle',
        'max_bills_per_month',
        'max_staff_users',
        'features',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'monthly_price' => 'decimal:2',
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
