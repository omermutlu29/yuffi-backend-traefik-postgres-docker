<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AppointmentChild extends Model
{
    protected $guarded = [];

    protected $with=['gender','child_year'];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function child_year()
    {
        return $this->belongsTo(ChildYear::class);
    }
}
