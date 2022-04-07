<?php


namespace App\Interfaces\IServices;


use App\Interfaces\IRepositories\ICardRepository;
use App\Models\Appointment;

interface IAppointmentPayment
{
    public function payToAppointment(Appointment $appointment, array $cardData);
}
