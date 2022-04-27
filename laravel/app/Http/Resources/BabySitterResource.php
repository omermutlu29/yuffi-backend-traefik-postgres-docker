<?php

namespace App\Http\Resources;

use App\Models\Parents;
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
            'id'=>$this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'last_name' => $this->last_name,
            'birthday' => $this->birthday,
            'about' => $this->about,
            'phone' => $this->phone,
            'photo'=>$this->photo,
            'has_calendar_for_future'=>$this->has_calendar_for_future,
            'price_per_hour'=>$this->price_per_hour,
            'experience_count'=>$this->experience_count(),
            'talents'=>$this->shareable_talents,
            'other_talents'=>$this->other_talents

        ];
        if (auth()->check() && auth()->user() instanceof Parents){
            $data['is_favorite']=in_array($this->id,auth()->user()->favorite_baby_sitters()->pluck('baby_sitter_id')->toArray());
        }

        return $data;
    }
}
