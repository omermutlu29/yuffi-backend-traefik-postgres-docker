<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Town extends Model
{
    protected $hidden=['created_at','updated_at','deleted_at'];

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function avaliable_baby_sitters(){
        return $this->belongsToMany(BabySitter::class,'baby_sitter_regions');
    }

}
