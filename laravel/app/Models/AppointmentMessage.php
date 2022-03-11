<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AppointmentMessage extends Model
{
    protected $guarded = [];

    protected $with = ['userable'];

    public static function boot()
    {
        parent::boot();
        static::created(function ($appointmentMessage) {
            event(
                new \App\Events\NewAppointmentMessageEvent(
                    $appointmentMessage->userable,
                    $appointmentMessage->message,
                    $appointmentMessage->appointment
                )
            );
        });
    }

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function userable()
    {
        return $this->morphTo();
    }
}
