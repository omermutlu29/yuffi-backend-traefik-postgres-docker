<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $hidden = ['id'];
    protected $casts = ['payment_raw_result' => 'object'];
    protected $with = ['baby_sitter', 'appointment_location', 'town', 'registered_children'];
    protected $guarded = [];
    protected $appends = ['is_cancelable_by_baby_sitter', 'is_cancelable_by_parent', 'has_rated'];

    public function have30MinutesPassed()
    {
        return now()->floatDiffInMinutes($this->created_at) <= 30;
    }

    public function payment_transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }


    public function getIsCancelableByBabySitterAttribute()
    {
        return $this->have30MinutesPassed() ? 'Eşleşmeyi iptal ediyorsunuz. Emin misiniz?'
            : 'Eşleşmeyi iptal etmek üzeresiniz. Bir sonraki eşleşmeden alacağınız ücrette kesintiye sebep olabilir. Detaylar için S.S.S’leri inceleyebilirsiniz. İptal etmek istediğinize emin misiniz?';
    }

    public function getIsCancelableByParentAttribute()
    {
        $appointmentFinishDate = Carbon::createFromFormat('Y-m-d H:i:s', $this->date . ' ' . $this->finish);
        return $appointmentFinishDate > now();
    }

    public function getHasRatedAttribute()
    {

        return $this->points()->count() > 0;
    }

    public function baby_sitter()
    {
        return $this->belongsTo(BabySitter::class);
    }

    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }

    public function points()
    {
        return $this->hasMany(BabySitterPoint::class);
    }


    public function appointment_location()
    {
        return $this->belongsTo(AppointmentLocation::class);
    }

    public function town()
    {
        return $this->belongsTo(Town::class);
    }

    public function registered_children()
    {
        return $this->hasMany(AppointmentChild::class);
    }

    public function appointment_status()
    {
        return $this->belongsTo(AppointmentStatus::class);
    }

    public function appointment_messages()
    {
        return $this->hasMany(AppointmentMessage::class);
    }


    /**
     * @param $query
     * @return mixed Scopes By Status
     */

    public function scopePendingApprove($query)
    {
        return $query->where('appointment_status_id', 1);
    }

    public function scopePendingPayment($query)
    {
        return $query->where('appointment_status_id', 3);
    }

    public function scopePaid($query)
    {
        return $query->where('appointment_status_id', 4);
    }

    public function scopeNotApproved($query)
    {
        return $query->where('appointment_status_id', 5);
    }

    public function scopeBabySitter($query, $babySitterId)
    {
        return $query->where('baby_sitter_id', $babySitterId);
    }

    public function scopePast($query)
    {
        $query->notCanceled();
        $nowDate = now()->format('Y-m-d');
        $nowHour = now()->format('H:i');
        return $query->where('date', '<', $nowDate)->orWhere(function ($query) use ($nowDate, $nowHour) {
            $query->where('date', $nowDate)->where('start', '<', $nowHour);
        });
    }

    public function scopeNotCanceled($query)
    {
        return $query->whereNotIn('appointment_status_id', [2, 5, 3])
            ->where('is_rejected_by_baby_sitter', null);
    }

    public function scopeFuture($query, int $days = 15)
    {
        $query->notCanceled();
        $nowDate = now()->format('Y-m-d');
        $futureDate = now()->addDays($days)->format('Y-m-d');
        $nowHour = now()->format('H:i');
        return $query->where('date', '>', $nowDate)->where('date', '<', $futureDate)->orWhere(function ($query) use ($nowDate, $nowHour) {
            $query->where('date', $nowDate)->where('start', '>', $nowHour);
        });/*->orWhere(function ($query) use ($futureDate, $nowHour) {
            $query->where('date', $futureDate)->where('date')->where('start', '>', $nowHour);
        });*/
    }

    public function scopeNotificationDidNotSent($query)
    {
        $query->notCanceled();
        $nowDate = now()->format('Y-m-d');
        $nowSubTwoHour = now()->subHours(2)->format('H:i');
        $query->where('date', '<', $nowDate)->orWhere(function ($query) use ($nowDate, $nowSubTwoHour) {
            $query->where('date', $nowDate)->where('finish', '<=', $nowSubTwoHour);
        });
        return $query->where('sent_point_notification', false);
    }


}
