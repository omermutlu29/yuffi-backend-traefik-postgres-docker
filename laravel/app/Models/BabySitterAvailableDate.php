<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BabySitterAvailableDate extends Model
{
    protected $fillable=[
        'baby_sitter_id','date'
    ];
    protected $with=['times'];
    public function baby_sitter(){
        return $this->belongsTo(BabySitter::class);
    }

    public function times(){
        return $this->hasMany(BabySitterAvailableTime::class,'available_date_id');
    }

    public function scopeNextFifteenDays($query){
        return $query->where('date', '>=', now()->format('Y-m-d'))
            ->where('date', '<=', now()->addDays(15)->format('Y-m-d'));
    }
}
