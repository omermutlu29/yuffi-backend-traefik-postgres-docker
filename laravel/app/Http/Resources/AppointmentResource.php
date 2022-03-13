<?php

namespace App\Http\Resources;

use Carbon\Carbon;
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
            'id'=>$this->id,
            'datetime'=>Carbon::createFromFormat('Y-m-d',$this->date)->format('d/m/Y').' '.Carbon::createFromFormat('H:i:s',$this->start)->format('H:i').' - '.Carbon::createFromFormat('H:i:s',$this->finish)->format('H:i'),
            'baby_sitter'=>BabySitterResource::make($this->baby_sitter),
            'parent'=>ParentResource::make($this->parent),
            'location'=>$this->appointment_location->name,
            'town'=>$this->town->name,
            'price'=>$this->price,
            'child_information' => AppointmentChildrenResource::collection($this->registered_children)
        ];
    }

}
