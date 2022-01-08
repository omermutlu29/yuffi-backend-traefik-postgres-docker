<?php

namespace App\Http\Resources;

use App\TimeStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeResource extends JsonResource
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
            'time'=>$this->start.' - '.$this->finish,
            'is_active'=>$this->is_active,
            'status'=>$this->time_status->name

        ];
    }
}
