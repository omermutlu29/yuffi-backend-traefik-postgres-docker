<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AppointmentMessage extends Model
{
    protected  $fillable=['phone','user_type','message','send_status','saw'];

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user(){
        if ($this->user_type=='App\BabySitter'){
            return $this->appointment->baby_sitter;
        }else{
            return $this->appointment->parent;
        }

    }
}
