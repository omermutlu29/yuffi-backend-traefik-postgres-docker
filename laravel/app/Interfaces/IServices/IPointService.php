<?php


namespace App\Interfaces\IServices;


use App\Models\Appointment;
use App\Models\Parents;
use App\Models\PointType;

interface IPointService
{
    public function getUnratedAppointments(Parents $parent);
    public function rateAppointment($appointmentId,  $pointTypeId, int $point, string $additionalText);
}
