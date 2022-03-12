<?php

namespace App\Listeners;

use App\Events\NewAppointmentMessageEvent;
use App\Interfaces\NotificationInterfaces\INotification;
use Illuminate\Support\Facades\Log;

class NewAppointmentMessageListener
{
    private INotification $notification;

    public function __construct(INotification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\NewAppointmentMessageEvent $event
     * @return void
     */
    public function handle(NewAppointmentMessageEvent $event)
    {
        Log::info($event->getReceiver()->google_st);
        if ($event->getReceiver()->google_st){
            $this->notification->notify(['appointment_id'=>$event->getAppointmentId()],'Yeni Mesaj', $event->getMessage(), $event->getReceiver()->google_st);
        }
    }
}
