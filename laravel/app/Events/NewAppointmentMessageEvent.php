<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewAppointmentMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $appointmentId;
    public $message;
    public $sender;

    public function __construct($sender,$message, $appointmentId)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->appointmentId = $appointmentId;
    }


    public function broadcastOn()
    {
        dump('App.Models.Appointment.' . $this->appointmentId);
        return new PrivateChannel('App.Models.Appointment.' . $this->appointmentId);
    }

    public function broadcastAs()
    {
        return 'newMessage';
    }
}
