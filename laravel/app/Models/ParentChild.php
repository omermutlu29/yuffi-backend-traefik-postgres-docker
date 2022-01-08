<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentChild extends Model
{
    use SoftDeletes;
    protected $fillable=[
        'child_year_id',
        'gender_id',
        'disable',
        'parent_id'
    ];
    public function parent(){
        return $this->belongsTo(Parents::class);
    }

    public function child_year(){
        return $this->belongsTo(ChildYear::class);
    }

    public function gender(){
        return $this->belongsTo(Gender::class);
    }
}
