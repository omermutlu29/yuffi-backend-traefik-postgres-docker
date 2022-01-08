<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BabySitterComment extends Model
{
    public function baby_sitter(){
        return $this->belongsTo(BabySitter::class);
    }

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }
}
