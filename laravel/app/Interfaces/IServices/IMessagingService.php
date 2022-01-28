<?php


namespace App\Interfaces\IServices;


use App\Models\Appointment;

interface IMessagingService
{
    public function sendMessage($user, Appointment $appointment, $text = '');

    public function getMessages(Appointment $appointment);
}
