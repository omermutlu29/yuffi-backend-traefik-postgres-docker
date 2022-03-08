<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'datetime'=>$this->date.' '.$this->start.' - '.$this->finish,
            'location'=>$this->location->name,
            'town'=>$this->town->name,
            'price'=>$this->price,
            'child_information' => AppointmentChildrenResource::collection($this->registered_children)
        ];
    }

}
