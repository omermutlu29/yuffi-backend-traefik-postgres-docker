<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Models\Appointment;
use App\Models\AppointmentStatus;

class AppointmentRepository implements IAppointmentRepository
{
    public function getAppointmentById(int $id)
    {
        return Appointment::find($id);
    }

    public function getAppointmentStatuses()
    {
        return AppointmentStatus::all();
    }

    public function getAppointmentsByBabySitterId(int $babySitterId)
    {
        return Appointment::where('baby_sitter_id', $babySitterId)->get();
    }

    public function getAppointmentsByBabySitterIdAndStatusId(int $babySitter, int $statusId)
    {
        return Appointment::where(['baby_sitter_id' => $babySitter, 'appointment_status_id' => $statusId])->get();
    }

    public function updateAppointment(int $appointmentId, array $data)
    {
        return Appointment::where('id', $appointmentId)->update($data);
    }

    public function getBabySitterPendingApproveAppointments(int $babySitter)
    {
        return Appointment::babySitter($babySitter)->pendingApprove()->get();
    }

    public function getBabySitterPendingPayment(int $babySitter)
    {
        return Appointment::babySitter($babySitter)->pendingPayment()->get();

    }

    public function getPaidAppointments(int $babySitter)
    {
        return Appointment::babySitter($babySitter)->paid()->get();
    }

    public function getNotApprovedAppointments(int $babySitter)
    {
        return Appointment::babySitter($babySitter)->notApproved()->get();

    }

    public function getAll()
    {
        return Appointment::all();
    }

    public function approveAppointment(int $appointmentId)
    {
        return Appointment::where('id', $appointmentId)->update(['baby_sitter_approved' => true, 'appointment_status_id' => 3]);
    }

    public function disapprove(int $appointmentId)
    {
        return Appointment::where('id', $appointmentId)->update(['baby_sitter_approved' => false, 'appointment_status_id' => 5]);
    }

    public function disapproveAppointment(int $appointmentId)
    {
        // TODO: Implement disapproveAppointment() method.
    }

    public function store(array $data)
    {
        return Appointment::create($data);
    }
}
