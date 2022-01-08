<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FilterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'surname'=>$this->surname,
            'point'=>$this->point,
            'work_count'=>count($this->appointments),
            'detail'=>route('baby-sitter.show',$this->id),
            'price'=>$this->price_per_hour
        ];
    }
}
