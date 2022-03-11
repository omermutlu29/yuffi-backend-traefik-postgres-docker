<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAppointmentMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $appointment;
    public $message;
    public $sender;

    public function __construct($sender, $message, Appointment $appointment)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->appointment = $appointment;
    }


    public function broadcastOn()
    {
        return [
            new PrivateChannel('App.Models.Parent.' . $this->appointment->parent_id),
            new PrivateChannel('App.Models.BabySitter.' . $this->appointment->baby_sitter_id)
        ];
    }

    public function broadcastAs()
    {
        return 'newMessage';
    }
}
