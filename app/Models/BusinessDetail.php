<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDetail extends Model
{
    protected $fillable = [
        'owner_id',
        'business_name',
        'mobile',
        'business_address',
        'gstin',
        'manufacturers_dealers_in',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
