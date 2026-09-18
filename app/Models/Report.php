<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report' , 'date', 'date_to' , 'type', 'association_id'
    ];
}
