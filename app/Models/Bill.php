<?php

namespace App\Models;

use App\Models\Concerns\BelongsToShop;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use BelongsToShop;
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'user_id',
        'customer_id',
        'bill_number',
        'order_number',
        'challan_number',
        'broker_name',
        'bill_date',
        'due_date',
        'delivered_to',
        'status',
        'subtotal',
        'discount',
        'transport_charges',
        'cgst',
        'sgst',
        'igst',
        'tax_total',
        'round_off',
        'total',
        'paid_amount',
        'notes',
        'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'bill_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'transport_charges' => 'decimal:2',
            'cgst' => 'decimal:2',
            'sgst' => 'decimal:2',
            'igst' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'round_off' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class)->orderBy('sort_order');
    }

    public function getWhatsappShareLinkAttribute(): string
    {
        $message = urlencode("Bill {$this->bill_number} total ₹{$this->total}");

        return "https://wa.me/?text={$message}";
    }
}
