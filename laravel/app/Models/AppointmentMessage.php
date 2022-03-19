<?php

namespace App\Models;


use App\Http\Resources\ChatUserResource;
use Illuminate\Database\Eloquent\Model;

class AppointmentMessage extends Model
{
    protected $guarded = [];

    protected $with = ['userable'];

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function userable()
    {
        return $this->morphTo();
    }
}
