<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $model = $this->user_type;
        if ($this->phone==null){
            dd($this);
        }else{
            $from = $model::where('phone', $this->phone)->first();
            $isUs = $from->phone == Auth::user()->phone ? true : false;
        }

        return [
            'fromYou'=>$isUs,
            'from' => $from->name . ' ' . $from->surname,
            'message'=>$this->message,
            'saw'=>$this->saw,
            'send'=>$this->send_status,
            'datetime'=>$this->created_at
        ];
    }
}
