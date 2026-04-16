<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallanItem extends Model
{
    protected $fillable = [
        'challan_id',
        'column_no',
        'row_no',
        'meters',
    ];

    public function challan()
    {
        return $this->belongsTo(Challan::class);
    }
}
