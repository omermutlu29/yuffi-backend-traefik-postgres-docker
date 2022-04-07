<?php


namespace App\Interfaces\IServices;


use App\Models\Appointment;

interface IAppointmentPayment
{
    public function payToAppointment(Appointment $appointment, array $cardData);
}
