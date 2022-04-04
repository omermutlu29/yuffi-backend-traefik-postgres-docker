<?php


namespace App\Services\PointService;


use App\Interfaces\IServices\IPointService;
use App\Models\Appointment;
use App\Models\BabySitterPoint;
use App\Models\Parents;
use App\Models\PointType;

class PointService implements IPointService
{
    public function getUnratedAppointments(Parents $parent)
    {
        return $parent->appointments()->whereDoesntHave('points')->get();
    }

    /**
     * @param Appointment $appointment
     * @param PointType $pointType
     * @param int $point
     * @param string $additionalText
     * @return bool
     * @throws \Exception
     */
    public function rateAppointment($appointmentId, PointType $pointType, int $point, string $additionalText): bool
    {
        $appointment=Appointment::find($appointmentId);
        if (BabySitterPoint::where([
                'appointment_id' => $appointment->id,
                'point_type_id' => $pointType->id,
                'baby_sitter_id' => $appointment->baby_sitter->id
            ])->count() > 0) {
            throw new \Exception('Randevuya zaten puan verildi', 400);
        }
        $result = $appointment->baby_sitter->points()->create([
            'point_type_id' => $pointType->id,
            'point' => $point,
            'appointment_id' => $appointment->id,
            'additional_text' => $additionalText
        ]);
        if (!$result) {
            throw new \Exception('Bir sorun oluştu!', 400);
        }
        return true;
    }
}
