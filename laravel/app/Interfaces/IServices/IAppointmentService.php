<?php


namespace App\Interfaces\IServices;


interface IAppointmentService
{
    public function pendingApproveAppointments($babySitterId);
    public function pendingPayment(int $babySitter);
    public function paidAppointments(int $babySitter);
    public function notApprovedAppointments(int $babySitter);
    public function approveAppointment(int $appointmentId);
    public function disapproveAppointment(int $appointmentId);
}
