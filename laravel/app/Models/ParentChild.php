<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentChild extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $with = ['child_year', 'gender'];

    public function parent()
    {
        return $this->belongsTo(Parents::class);
    }

    public function child_year()
    {
        return $this->belongsTo(ChildYear::class);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function scopeParent($query,$parentId){
        return $query->where('parent_id',$parentId);
    }
}
