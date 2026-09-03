<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;




class Orphan extends Authenticatable
{
    use Notifiable;

    protected $fillable = [

        'image' , 'name' , 'role' ,'association_id','birth_date' ,'birth_place' ,'country' ,'city' ,'landmark' ,'id_number' ,'orphan_status' ,'gender' ,'mother_name'
        ,'death_mother_date' ,'cause_mother_death' ,'father_name' ,'death_father_date' ,'cause_father_death' ,'mother_id_number' ,'mother_marital_status' ,'mother_phone' ,'father_id_number'
        ,'father_marital_status' ,'father_phone' ,'income' ,'income_value' ,'income_source' ,'father_death_certificate' ,'not_available_father_death' ,'mother_death_certificate' ,'not_available_mother_death'
        ,'guardian_name' , 'guardian_relation' , 'guardian_jop','password' , 'nominating_authority'

    ];

    protected $hidden = [
        'password',
    ];

    // protected $hidden = [
    //     'password',
    // ];

    // one to one relation with profiles table
    public function profile(){
        return $this->hasOne(Profile::class);
    }

    // one to many relation with associations table
    public function association(){
        return $this->belongsTo(Association::class)->withDefault();
    }

    // relationship with reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);

    }

    public function firstReview()
    {
        return $this->hasOne(Review::class)->where('review_number', 'first');
    }

    public function sponsorships(){
       return $this->hasMany(Sponsorship::class);
    }

    /**
     * Latest sponsorship helper to avoid using collection->last() in views
     * Use eager-loading: ->with('latestSponsorship') when returning lists
     */
    public function latestSponsorship()
    {
        return $this->hasOne(Sponsorship::class)->latestOfMany('sponsorship_date');
    }

    public function gifts()
    {
        return $this->hasMany(Gift::class);
    }

    public function activeSponsorships(){
       return $this->hasOne(Sponsorship::class)->where('status' , 'active')->latest();
    }

    public function expenses(){
        return $this->hasMany(Expense::class);
    }

    public function getImageUrlAttribute()
    {
        // column image in database
        if (!$this->image) {
             return asset('images/profile.png');

        }

        // if $this->image start with http:// or https://
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    public function sibling(){
        return $this->hasOne(Sibling::class);
    }

    public function pendingSponsorships()
    {
        return $this->belongsToMany(PendingSponsorship::class, 'pending_sponsorship_orphan', 'orphan_id', 'pending_sponsorship_id');
    }


    public function media()
    {
        return $this->hasMany(OrphanMedia::class);
    }

    



    // protected $casts = [
    // 'brother_name' => 'array',
    // 'brother_gender' => 'array',
    // 'brother_age' => 'array',
    // 'brother_marital_status' => 'array',
    // 'brother_jop' => 'array',
    // 'brother_id_number' => 'array',
    // ];

    /*
     * Role helpers and scopes
     */
    public function isWaiting(): bool
    {
        return ($this->role ?? null) === \App\Enums\OrphanRole::WAITING->value;
    }

    public function isCandidate(): bool
    {
        return ($this->role ?? null) === \App\Enums\OrphanRole::CANDIDATE->value;
    }

    public function isAuditor(): bool
    {
        return ($this->role ?? null) === \App\Enums\OrphanRole::AUDITOR->value;
    }

    public function isCertified(): bool
    {
        return ($this->role ?? null) === \App\Enums\OrphanRole::CERTIFIED->value;
    }

    public function isSponsored(): bool
    {
        return ($this->role ?? null) === \App\Enums\OrphanRole::SPONSORED->value;
    }

    public function isArchived(): bool
    {
        return ($this->role ?? null) === \App\Enums\OrphanRole::ARCHIVED->value;
    }

    public function scopeOfRole(\Illuminate\Database\Eloquent\Builder $query, \App\Enums\OrphanRole|string $role)
    {
        $value = $role instanceof \App\Enums\OrphanRole ? $role->value : $role;
        return $query->where('role', $value);
    }

    public function scopeWaiting(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('role', \App\Enums\OrphanRole::WAITING->value);
    }

    public function scopeCandidate(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('role', \App\Enums\OrphanRole::CANDIDATE->value);
    }

    public function scopeAuditor(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('role', \App\Enums\OrphanRole::AUDITOR->value);
    }

    public function scopeCertified(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('role', \App\Enums\OrphanRole::CERTIFIED->value);
    }

    public function scopeSponsored(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('role', \App\Enums\OrphanRole::SPONSORED->value);
    }

    public function scopeArchived(\Illuminate\Database\Eloquent\Builder $query)
    {
        return $query->where('role', \App\Enums\OrphanRole::ARCHIVED->value);
    }
}
