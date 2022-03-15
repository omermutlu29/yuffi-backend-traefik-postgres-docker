<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Str;

class Parents extends Authenticatable
{
    use  HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'parents';
    protected $appends = ['has_registered_card', 'last_name'];
    protected $hidden = ['card_parents'];
    protected $guarded = [];


    private function mb_ucfirst($string, $encoding)
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }


    public function getLastNameAttribute()
    {
        try {
            return mb_strtoupper($this->surname[0]).'.';
        }catch (\Exception $exception){
            return '';
        }
    }

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
