<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BabySitterAvaliableDate extends Model
{
    protected $fillable=[
        'baby_sitter_id','date'
    ];
    public function baby_sitter(){
        return $this->belongsTo(BabySitter::class);
    }

    public function times(){
        return $this->hasMany(BabySitterAvaliableTime::class,'avaliable_date_id');
    }
}
