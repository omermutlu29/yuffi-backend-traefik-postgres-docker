<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $hidden=['created_at','updated_at','deleted_at'];

    public function parents(){
        return $this->hasMany(Parents::class);
    }


    public function baby_sitters(){
        return $this->hasMany(BabySitter::class);
    }
}
