<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'bill_id',
        'product_id',
        'description',
        'pieces',
        'meters',
        'rate',
        'amount',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'meters' => 'decimal:2',
            'rate' => 'decimal:2',
            'amount' => 'decimal:2',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
