<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Researcher extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'association_id' , 'name' ,'email' , 'id_number' , 'phone' , 'phone_whats' , 'password',
    ];

    protected $hidden = [
        'password',

    ];


    // one to many
    public function association()
    {
        return $this->belongsTo(Association::class)->withDefault();
    }

    public function uploadedMedia()
    {
        return $this->hasMany(OrphanMedia::class, 'researcher_id');
    }

}
