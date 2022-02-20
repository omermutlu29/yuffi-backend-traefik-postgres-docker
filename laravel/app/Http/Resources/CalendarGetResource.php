<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarGetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];
        $data[$this->date] = $this->prapareString($this);
        return $data;
    }

    private function prapareString($date)
    {
        $data = [];
        foreach ($date->times as $time) {
            $string['name'] = $time->start . ' ' . $time->finish . ' ve ' . $time->time_status->name;
            $data[] = $string;
        }
        return $data;
    }
}
