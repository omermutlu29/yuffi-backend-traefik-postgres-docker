<?php


namespace App\Services\Appointment;


use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IRepositories\IChildrenRepository;
use App\Interfaces\IServices\IAppointmentService;

class AppointmentService implements IAppointmentService
{
    private IAppointmentRepository $appointmentRepository;
    private IBabySitterRepository $babySitterRepository;
    private IChildrenRepository $childrenRepository;

    public function __construct(
        IAppointmentRepository $appointmentRepository,
        IBabySitterRepository $babySitterRepository,
        IChildrenRepository $childrenRepository
    )
    {
        $this->babySitterRepository = $babySitterRepository;
        $this->appointmentRepository = $appointmentRepository;
        $this->childrenRepository = $childrenRepository;
    }

    public function pendingApproveAppointments($babySitterId)
    {
        return $this->appointmentRepository->getBabySitterPendingApproveAppointments($babySitterId);
    }

    public function pendingPayment(int $babySitter)
    {
        return $this->appointmentRepository->getBabySitterPendingPayment($babySitter);
    }

    public function paidAppointments(int $babySitter)
    {
        return $this->appointmentRepository->getPaidAppointments($babySitter);
    }

    public function notApprovedAppointments(int $babySitter)
    {
        return $this->appointmentRepository->getNotApprovedAppointments($babySitter);
    }

    public function approveAppointment(int $appointmentId)
    {
        return $this->appointmentRepository->approveAppointment($appointmentId);
    }

    public function disapproveAppointment(int $appointmentId)
    {
        return $this->appointmentRepository->disapproveAppointment($appointmentId);
    }


    public function create(int $babySitterId, int $parentId, array $appointmentData)
    {
        $babySitter = $this->babySitterRepository->getUserById($babySitterId);
        if (!$babySitter) {
            throw new \Exception('Babysitter could not found', 401);
        }
        $appointmentData = [
            'baby_sitter_id' => $babySitterId,
            'parent_id' => $parentId,
            'town_id' => $appointmentData['town_id'],
            'appointment_status_id' => 1,
            'hour' => $appointmentData['hour'],
            'start' => $appointmentData['start'],
            'finish' => (date('H:i', strtotime("+" . $appointmentData['hour'] . " Hour " . $appointmentData['time']))),
            'price' => $appointmentData['hour'] * $babySitter->price_per_hour,
            'appointment_location_id' => $appointmentData['appointment_location_id'],
            'location' => $appointmentData['location']
        ];
        $appointment = $this->appointmentRepository->store($appointmentData);
        if (!$appointment) {
            throw new \Exception('Appointment could not created', 401);
        }
        $children = $this->childrenRepository->getParentChildren($parentId);
        foreach ($children as $child) {
            $appointment->registered_children()->attach($child->id);
        }
        return $appointment;

    }
}
