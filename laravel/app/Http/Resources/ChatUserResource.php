<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            '_id' => (int)$this->id,
            'text' => $this->message,
            'createdAt' => $this->created_at->format('m/d/Y H:i:s'),
            'user' => [
                '_id' => $this->userable->uuid,
                'name' => $this->userable->name . ' ' . $this->userable->last_name,
                'avatar' => $this->userable->photo,
            ]
        ];
    }
}
