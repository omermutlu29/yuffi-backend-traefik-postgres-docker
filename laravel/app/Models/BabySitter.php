<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class BabySitter extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $hidden = [];
    protected $guarded = [];
    protected $appends = ['last_name'];

    public function getLastNameAttribute()
    {
        return isset($this->surname) ? ucfirst($this->surname[0]) . '.' : '';
    }

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

    public function available_towns()
    {
        return $this->belongsToMany(Town::class, 'baby_sitter_regions');
    }

    public function shareable_talents()
    {
        return $this->belongsToMany(ShareableTalent::class, 'baby_sitter_shareable_talents','baby_sitter_id','shareable_talent_id');
    }

    public function baby_sitter_available_dates()
    {
        return $this->hasMany(BabySitterAvailableDate::class);
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

    public function parent_gender()
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

    /**
     * Scopes
     * @param $query
     * @param string $phone
     * @return mixed
     */

    public function scopePhone($query, string $phone)
    {
        return $query->where('phone', $phone);
    }

    public function scopeAcceptedLocation($query, $acceptedLocationId)
    {
        return $query->whereHas('accepted_locations', function ($q) use ($acceptedLocationId) {
            $q->where('location_id', $acceptedLocationId);
        });
    }

    public function scopeChildGenderStatus($query, $childGender)
    {
        if ($childGender != null) {
            $query->whereRaw('( child_gender_id = ' . $childGender . '  OR child_gender_id = 3 )');
        } else {
            $query->whereRaw('( child_gender_id = 3 )');
        }
        return $query;
    }

    public function scopeAcceptsDisabledChild($query, $disable)
    {
        if ($disable)
            $query->where('disabled_status', 1);
        return $query;
    }

    public function scopeGender($query, $genderId = null)
    {
        if ($genderId && $genderId != 3) {//3 Farketmez
            $query->where('gender_id', $genderId);
        }
        return $query;
    }

    public function scopeChildrenCount($query, $count)
    {
        return $query->where('child_count', '>=', $count);
    }

    public function scopeDepositPaid($query)
    {
        return $query->where('deposit', 30);
    }

    public function scopeAvailableTown($query, $townId)
    {
        return $query->whereHas('available_towns', function ($q) use ($townId) {
            $q->where('town_id', $townId);
        });
    }

    public function scopeDateTime($query, $date, array $times)
    {
        return $query->whereHas('baby_sitter_available_dates', function ($q) use ($date, $times) {//O gün yer var mı ?
            $q->where('date', Carbon::make($date));
            foreach ($times as $time) {
                $q->whereHas('times', function ($q1) use ($time) {//O günün saatlerinde yer var mı varsa meşgul mü değil mi ?
                    $q1->where('start', $time)->where('time_status_id', 1);
                });
            }
        });
    }

    public function scopeBabySitterId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopePricePerHour($query)
    {
        return $query->where('price_per_hour', '>', 0);
    }

    public function scopeWcStatus($query, $status)
    {
        if ($status) {
            return $query->where('wc_status', true);
        }
        return $query;
        // return $query->where('wc_status', false)->orWhere('wc_status', null);
    }

    public function scopeAnimalStatus($query, $status)
    {
        if ($status) {
            return $query->where('animal_status', true);
        }
        return $query;

        //return $query->where('wc_status', false)->orWhere('wc_status', null);

    }

}
