<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sibling extends Model
{
    protected $fillable = [
        'male_number' , 'female_number',
        // 'brother_name' , 'brother_gender' , 'brother_age' ,'brother_marital_status' , 'brother_jop' , 'brother_id_number'
    ];

    public function orphan(){
        return $this->belongsTo(Orphan::class)->withDefault();
    }
}
