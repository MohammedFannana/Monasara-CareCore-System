<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Sponsor extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name' , 'phone' ,'email' ,'country' ,'address' ,'password' ,'receive_report',
        'payment_reminder' ,'payment_mechanism'
    ];

    public function sponsorships(){
        return $this->hasMany(Sponsorship::class);
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }
}
