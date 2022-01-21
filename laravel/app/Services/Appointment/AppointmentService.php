<?php


namespace App\Services\Appointment;


use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IServices\IAppointmentService;

class AppointmentService implements IAppointmentService
{
    private IAppointmentRepository $appointmentRepository;

    public function __construct(IAppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
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

    public function approveAppointment(int $appointmentId){
        return $this->appointmentRepository->approveAppointment($appointmentId);
    }

    public function disapproveAppointment(int $appointmentId)
    {
        return $this->appointmentRepository->disapproveAppointment($appointmentId);

    }
}
