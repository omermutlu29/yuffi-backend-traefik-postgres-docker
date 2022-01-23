<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BabySitterComment extends Model
{
    public function baby_sitter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BabySitter::class);
    }

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
