<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BabySitterPoint extends Model
{
    protected $guarded=[];
    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }

    public function baby_sitter(){
        return $this->belongsTo(BabySitter::class);
    }

    public function point_type(){
        return $this->belongsTo(PointType::class);
    }

}
