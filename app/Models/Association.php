<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Association extends  Authenticatable

{

    use Notifiable;

    protected $fillable=[
        'name',
        'address',
        'responsible_person',
        'email',
        'fax',
        'license_number',
        'phone',
        'phone1',
        'phone2',
        'password',
        'role',
        'parent_association_id'
    ];

    protected $hidden = [
        'password',
    ];

    public function getReportAssociationId(): int
    {
        return $this->role === 'association_staff'
            ? (int) $this->parent_association_id
            : (int) $this->id;
    }

    // one to many
    public function researchers()
    {
        return $this->hasMany(Researcher::class);
    }

    // one to many
    public function orphans()
    {
        return $this->hasMany(Orphan::class);
    }
}
