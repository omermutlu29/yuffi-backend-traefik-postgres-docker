<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BabySitterDeposit extends Model
{
    public function baby_sitter(){
        return $this->belongsTo(BabySitter::class);
    }
}
