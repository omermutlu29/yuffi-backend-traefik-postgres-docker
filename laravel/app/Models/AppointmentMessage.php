<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AppointmentMessage extends Model
{
    protected  $guarded=[];

    protected $with=['user'];

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user(){
        return $this->morphTo();
    }
}
