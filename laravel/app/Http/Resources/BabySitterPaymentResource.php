<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BabySitterPaymentResource extends JsonResource
{

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

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
