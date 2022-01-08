<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BabySitterAvaliableTime extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'avaliable_date_id','start','finish','time_status_id'
    ];
    public function baby_sitter_avaliable_date(){
        return $this->belongsTo(BabySitterAvaliableDate::class,'avaliable_date_id');
    }

    public function time_status(){
        return $this->belongsTo(TimeStatus::class);
    }
}
