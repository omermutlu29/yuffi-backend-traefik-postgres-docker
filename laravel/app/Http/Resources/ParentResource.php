<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
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
            'phone'=>$this->phone,
            'photo'=>$this->photo,
            'has_registered_card'=>$this->has_registered_card,
            'tc'=>$this->tc,
            'birthday'=>$this->birthday,
            'service_contract'=>$this->service_contract,
            'gender_id'=>$this->gender_id,
            'black_list'=>$this->black_list,
            'google_st'=>$this->google_st,
            'network'=>$this->network,
            'last_name'=>$this->last_name,
        ];
    }
}
