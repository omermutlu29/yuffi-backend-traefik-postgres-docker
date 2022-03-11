<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'full_name' => $this->name.' '.$this->last_name,
            'photo'=>$this->photo,
        ];
    }
}
