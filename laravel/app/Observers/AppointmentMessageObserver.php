<?php

namespace App\Observers;

use App\Interfaces\NotificationInterfaces\INotification;
use App\Models\AppointmentMessage;
use App\Models\BabySitter;

class AppointmentMessageObserver
{
    private INotification $notificationService;

    public function __construct(INotification $notification)
    {
        $this->notificationService = $notification;
    }

    /**
     * Handle the AppointmentMessage "created" event.
     *
     * @param \App\Models\AppointmentMessage $appointmentMessage
     * @return void
     */
    public function created(AppointmentMessage $appointmentMessage)
    {
        $appointment = $appointmentMessage->appointment;
        if ($appointmentMessage->user instanceof BabySitter) {
            $to = $appointment->parent->google_st;
        } else {
            $to = $appointment->baby_sitter->google_st;
        }
        $this->notificationService->notify('Yeni mesaj', $appointmentMessage->text, $to);

    }

}
