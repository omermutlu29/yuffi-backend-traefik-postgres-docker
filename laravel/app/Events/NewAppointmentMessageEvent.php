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

    public function __construct($message, $appointmentId)
    {
        $this->message = $message;
        $this->appointmentId = $appointmentId;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.Appointment.' . $this->appointmentId);
    }
}
