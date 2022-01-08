<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BabySitterResource extends JsonResource
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
            'name'=>$this->name,
            'surname'=>$this->surname,
            'birthday'=>$this->birthday,
            'about'=>$this->about,
            'phone'=>$this->phone,
        ];
    }
}
