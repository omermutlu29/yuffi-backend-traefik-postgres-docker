<?php

namespace App\Events;

use App\Http\Resources\ChatUserResource;
use App\Models\AppointmentMessage;
use Carbon\Carbon;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAppointmentMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $appointmentMessage;

    public function __construct(AppointmentMessage $appointmentMessage)
    {
        $this->appointmentMessage = $appointmentMessage;
    }


    public function broadcastOn()
    {
        return [
            new PrivateChannel('App.Models.Parent.' . $this->appointmentMessage->appointment->parent_id),
            new PrivateChannel('App.Models.BabySitter.' . $this->appointmentMessage->appointment->baby_sitter_id)
        ];
    }

    public function broadcastAs()
    {
        return 'newMessage';
    }

    public function broadcastWith()
    {
        return ChatUserResource::make($this->appointmentMessage);
        /*[
            '_id' => $this->appointmentMessage->id,
            'text' => $this->appointmentMessage->message,
            'createdAt' => $this->appointmentMessage->created_at->format('d/m/Y H:i:s'),
            'user' => [
                '_id' => $this->appointmentMessage->userable_id,
                'name' => $this->appointmentMessage->userable->name . ' ' . $this->appointmentMessage->userable->last_name,
                'avatar' => $this->appointmentMessage->userable->photo,
            ]
        ];*/
    }
}
