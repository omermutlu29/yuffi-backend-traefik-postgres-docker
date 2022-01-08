<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'card_familiy'=>$this->cardfamily,
            'card_alias'=>$this->cardalias,
            'last_four_digits'=>$this->lastfourdigits,
            'delete_url'=>route('card.delete',$this->id)
        ];
    }
}
