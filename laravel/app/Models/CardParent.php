<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CardParent extends Model
{
    public function parent(){
        return $this->belongsTo(Parents::class);
    }
}
