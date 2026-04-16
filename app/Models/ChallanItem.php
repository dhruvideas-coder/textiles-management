<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ChallanItem extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'challan_id',
        'product_id',
        'product_name',
        'pieces',
        'meters',
        'weight',
        'remarks',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'meters' => 'decimal:2',
            'weight' => 'decimal:2',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function challan(): BelongsTo
    {
        return $this->belongsTo(Challan::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
