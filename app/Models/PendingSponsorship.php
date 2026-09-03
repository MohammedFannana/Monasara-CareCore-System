<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingSponsorship extends Model
{
    use HasFactory;

    protected $fillable = ['duration', 'bail_amount' , 'order_id' ,'type' ,'notes'];

    public function orphans()
    {
        return $this->belongsToMany(
            Orphan::class,
            'pending_sponsorship_orphan', // اسم جدول الـ Pivot
            'pending_sponsorship_id',
            'orphan_id'
        );
    }
}
