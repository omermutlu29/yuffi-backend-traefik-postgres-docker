<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ParentPaymentResource extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'surname' => $this->surname,
            'phoneNumber' => $this->phone,
            'email' => $this->email,
            'identity' => $this->tc,
            'address' => $this->address,
            'city' => 'istanbul', //TODO
            'country' => 'Türkiye', //todo
            'zip_code' => '34520' //todo
        ];
    }
}
