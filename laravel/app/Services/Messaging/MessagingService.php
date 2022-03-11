<?php


namespace App\Services\Messaging;


use App\Interfaces\IServices\IMessagingService;
use App\Models\Appointment;

class MessagingService implements IMessagingService
{
    public function sendMessage($user, Appointment $appointment, $text = ''): \Illuminate\Database\Eloquent\Model
    {
        $messageSent = $appointment->appointment_messages()->create([
            'userable_type' => get_class($user),
            'userable_id' => $user->id,
            'text' => $text
        ]);
        if (!$messageSent) {
            throw new \Exception('Message could not sent', 401);
        }
        return $messageSent;
    }

    public function getMessages(Appointment $appointment): \Illuminate\Database\Eloquent\Collection
    {
        $messages = $appointment->appointment_messages()->get();
        if (!$messages) {
            throw  new \Exception('Message could not find', 401);
        }
        return $messages;
    }
}
