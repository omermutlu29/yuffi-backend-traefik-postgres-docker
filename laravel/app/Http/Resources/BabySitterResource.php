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
        $data =  [
            'name' => $this->name,
            'surname' => $this->surname,
            'last_name' => $this->last_name,
            'birthday' => $this->birthday,
            'about' => $this->about,
            'phone' => $this->phone,
            'photo'=>$this->photo,
            'has_calendar_for_future'=>$this->has_calendar_for_future,
            'price_per_hour'=>$this->price_per_hour,

        ];
        if (auth()->check()){
            $data['is_favorite']=in_array($this->id,auth()->user()->favorite_baby_sitters()->pluck('baby_sitter_id')->toArray());
        }

        return $data;
    }
}
