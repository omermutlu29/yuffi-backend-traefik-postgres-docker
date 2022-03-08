<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentChildrenResource extends JsonResource
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
            'year'=>$this->child_year->name,
            'gender'=>$this->gender->child_name,
            'disabled'=>$this->disabled ? 'Engel durumu var' : 'Engel durumu yok'
        ];
    }
}
