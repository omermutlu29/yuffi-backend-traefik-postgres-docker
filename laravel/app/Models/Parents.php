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

    protected $fillable = [
        'name',
        'surname',
        'tc',
        'birthday',
        'photo',
        'phone',
        'service_contract',
        'gender_id',
        'kvkk',
        'black_list',
        'network',
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

    public function parent_children()
    {
        return $this->hasMany(ParentChild::class, 'parent_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'parent_id')->with("baby_sitter:id,name,surname,photo,point");
    }
}
