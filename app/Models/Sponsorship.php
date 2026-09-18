<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Model
{
    protected $fillable = [
        'order_id', 'orphan_id' , 'sponsor_id' ,'sponsorship_date' , 'duration' , 'status','bail_amount','total','payment_received' , 'currency', 'sponsorship_delivery'
    ];

    public function orphan(){
        return $this->belongsTo(Orphan::class)->withDefault();
    }

    public function sponsor(){
        return $this->belongsTo(Sponsor::class)->withDefault();
    }
}
