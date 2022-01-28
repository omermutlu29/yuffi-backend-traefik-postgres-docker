<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BabySitterComment extends Model
{
    protected $guarded = [];
    protected $with=['parent'];

    public function baby_sitter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BabySitter::class);
    }

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function parent(){
        return $this->belongsTo(Parents::class,'parent_id');
    }

    public function scopeBabySitter($query, $babySitterId)
    {
        return $query->where('baby_sitter_id', $babySitterId);
    }
}
