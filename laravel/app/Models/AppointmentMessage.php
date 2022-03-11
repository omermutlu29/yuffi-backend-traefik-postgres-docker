<?php

namespace App\Models;


use App\Http\Resources\ChatUserResource;
use Illuminate\Database\Eloquent\Model;

class AppointmentMessage extends Model
{
    protected $guarded = [];

    protected $with = ['user'];

    public static function boot()
    {
        parent::boot();
        static::created(function ($appointmentMessage) {
            event(
                new \App\Events\NewAppointmentMessageEvent(
                    ChatUserResource::make($appointmentMessage->user),
                    $appointmentMessage->message,
                    $appointmentMessage->appointment->id
                )
            );
        });
    }

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function user()
    {
        return $this->morphTo();
    }
}
