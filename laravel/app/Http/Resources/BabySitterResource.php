<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BabySitterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'last_name' => $this->last_name,
            'birthday' => $this->birthday,
            'about' => $this->about,
            'phone' => $this->phone,
            'photo'=>$this->photo,
            'has_calendar_for_future'=>$this->has_calendar_for_future,
        ];
    }
}
