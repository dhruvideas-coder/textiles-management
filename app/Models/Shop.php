<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'slug',
        'code',
        'owner_user_id',
        'email',
        'phone',
        'gstin',
        'bank_name',
        'account_number',
        'ifsc_code',
        'logo_path',
        'theme_color',
        'footer_text',
        'address',
        'city',
        'state',
        'pincode',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }



    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

    public function challans(): HasMany
    {
        return $this->hasMany(Challan::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
