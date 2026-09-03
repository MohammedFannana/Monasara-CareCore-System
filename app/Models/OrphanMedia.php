<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrphanMedia extends Model
{
    protected $fillable = [
        'orphan_id',
        'association_id',
        'researcher_id',
        'type',
        'file_path',
        'note',
    ];

    public function orphan()
    {
        return $this->belongsTo(Orphan::class);
    }

    public function association()
    {
        return $this->belongsTo(Association::class);
    }

    public function researcher()
    {
        return $this->belongsTo(Researcher::class, 'researcher_id');
    }
}
