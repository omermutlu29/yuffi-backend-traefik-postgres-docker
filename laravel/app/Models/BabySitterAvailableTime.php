<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BabySitterAvailableTime extends Model
{
    use SoftDeletes;

    protected $fillable = [
        '_date_id', 'start', 'finish', 'time_status_id'
    ];

    protected $with = ['time_status'];

    public function baby_sitter_available_date()
    {
        return $this->belongsTo(BabySitterAvailableDate::class, 'available_date_id');
    }

    public function time_status()
    {
        return $this->belongsTo(TimeStatus::class);
    }
}
