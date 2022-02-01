<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Parents extends Authenticatable
{
    use  HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'parents';
    protected $appends = ['has_registered_card'];

    protected $guarded = [

    ];

    public function modelName()
    {
        return '\Parents';
    }

    public function sms_codes()
    {
        return $this->hasMany(ParentSmsCode::class, 'parent_id');
    }

    public function card_parents()
    {
        return $this->hasMany(CardParent::class, 'parent_id');
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }



    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'parent_id')->with("baby_sitter:id,name,surname,photo,point");
    }

    public function scopePhone($query, $phone)
    {
        return $query->where('phone', $phone);
    }

    public function getHasRegisteredCardAttribute()
    {
        return count($this->card_parents) > 0;
    }
}
