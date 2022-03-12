<?php

namespace App\Listeners;

use App\Events\NewAppointmentMessageEvent;
use App\Interfaces\NotificationInterfaces\INotification;

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
        $this->notification->notify($event->getAppointmentId(),'Yeni Mesaj', $event->getMessage(), $event->getReceiver()->google_st);
    }
}
