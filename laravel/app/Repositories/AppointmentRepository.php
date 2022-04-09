<?php


namespace App\Repositories;


use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Models\Appointment;
use App\Models\AppointmentStatus;

class AppointmentRepository implements IAppointmentRepository
{
    public function getAppointmentById(int $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            throw new \Exception('Appointment could not find', 400);
        }
        return $appointment;
    }

    public function getAppointmentStatuses()
    {
        return AppointmentStatus::all();
    }

    public function getAppointmentsByBabySitterId(int $babySitterId)
    {
        return Appointment::where('baby_sitter_id', $babySitterId)->get();
    }

    public function getPastAppointmentsByParentId(int $parentId)
    {
        return Appointment::where('parent_id', $parentId)->past()->orderBy('created_at', 'DESC')->get();
    }

    public function getFutureAppointmentsByParentId(int $parentId)
    {
        return Appointment::where('parent_id', $parentId)->future()->orderBy('created_at', 'DESC')->get();
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
        $appointment = $this->getAppointmentById($appointmentId);
        return $appointment->update(
            [
                'appointment_status_id' => 5,
                'is_rejected_by_baby_sitter' => true,
                'rejected_time_range' => now()->diffInHours($appointment->created_at)
            ]
        );
    }

    public function disapproveAppointment(int $appointmentId)
    {
        // TODO: Implement disapproveAppointment() method.
    }

    public function store(array $data)
    {
        return Appointment::create($data);
    }

    public function getPastAppointmentsByBabySitterId(int $babySitterId)
    {
        return Appointment::where('baby_sitter_id', $babySitterId)->past()->orderBy('created_at', 'DESC')->get();
    }

    public function getFutureAppointmentsByBabySitterId(int $babySitterId)
    {
        return Appointment::where('baby_sitter_id', $babySitterId)->future()->orderBy('created_at', 'DESC')->get();
    }

    public function getUpcomingAppointments(int $babySitterId)
    {
        return Appointment::where('baby_sitter_id', $babySitterId)->future(3)->orderBy('created_at', 'DESC')->get();

    }
}
