<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class BabySitter extends Authenticatable
{
    use  HasApiTokens, Notifiable, SoftDeletes;

    protected $hidden = ['id', 'created_at', 'updated_at'];
    protected $fillable = [
        'phone',
        'name',
        'surname',
        'email',
        'address',
        'birthday',
        'photo',
        'tc',
        'criminal_record',
        'about',
        'service_contrct',
        'gender_id',
        'price_per_hour',
        'child_gender_id',
        'iban',
    ];

    public function modelName()
    {
        return 'App\BabySitter';
    }

    public function sms_codes()
    {
        return $this->hasMany(BabySitterSmsCode::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function avaliable_towns()
    {
        return $this->belongsToMany(Town::class, 'baby_sitter_regions');
    }

    public function baby_sitter_avaliable_dates()
    {
        return $this->hasMany(BabySitterAvaliableDate::class);
    }

    public function baby_sitter_deposits()
    {
        return $this->hasMany(BabySitterDeposit::class);
    }

    public function accepted_locations()
    {
        return $this->belongsToMany(AppointmentLocation::class, 'baby_sitter_appointment_locations', 'baby_sitter_id', 'location_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function comments()
    {
        return $this->hasMany(BabySitterComment::class);
    }

    public function points()
    {
        return $this->hasMany(BabySitterPoint::class);
    }

    public function child_gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function child_year()
    {
        return $this->belongsTo(ChildYear::class);
    }

    public function baby_sitter_status()
    {
        return $this->belongsTo(BabySitterStatus::class);
    }

}
