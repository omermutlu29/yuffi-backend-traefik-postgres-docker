<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressPaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'contact_name' => $this->name. ' '. $this->surname,
            'address' => $this->address,
            'city' => 'istanbul', //TODO
            'country' => 'Türkiye', //todo
            'zip_code' => '34520' //todo
        ];
    }
}
