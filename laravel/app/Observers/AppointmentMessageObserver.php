<?php

namespace App\Observers;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Models\AppointmentMessage;
use App\Models\BabySitter;
use App\Models\Parents;

class AppointmentMessageObserver
{

    /**
     * Handle the AppointmentMessage "created" event.
     *
     * @param \App\Models\AppointmentMessage $appointmentMessage
     * @return void
     */
    public function created(AppointmentMessage $appointmentMessage)
    {
        if ($appointmentMessage->userable instanceof BabySitter){
            $receiver = $appointmentMessage->appointment->parent;
            event(new \App\Events\NewAppointmentMessageEvent($appointmentMessage->load('appointment','userable'),$receiver));
        }

        if ($appointmentMessage->userable instanceof Parents){
            $receiver = $appointmentMessage->appointment->baby_sitter;
            event(new \App\Events\NewAppointmentMessageEvent($appointmentMessage->load('appointment','userable'),$receiver));
        }

    }

}
