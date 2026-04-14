<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'shop_id',
        'sku',
        'name',
        'description',
        'current_stock_meters',
        'low_stock_threshold',
        'rate',
        'unit',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'current_stock_meters' => 'decimal:2',
            'low_stock_threshold' => 'decimal:2',
            'rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function billItems(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    public function challanItems(): HasMany
    {
        return $this->hasMany(ChallanItem::class);
    }

    public function isLowStock(): bool
    {
        return (float) $this->current_stock_meters <= (float) $this->low_stock_threshold;
    }
}
