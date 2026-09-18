<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $fillable = [
        'order_id',
        'orphan_id',
        'sponsor_id',
        'gift_date',
        'amount',
        'duration',
        'currency',
        'total',
        'notes',
    ];

    public function orphan()
    {
        return $this->belongsTo(Orphan::class);
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class);
    }
}
