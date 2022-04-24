<?php


namespace App\Interfaces\IRepositories;


interface IAppointmentRepository
{
    public function getAppointmentById(int $id);

    public function getAll();

    public function getAppointmentStatuses();

    public function getAppointmentsByBabySitterId(int $babySitterId);

    public function getAppointmentsByBabySitterIdAndStatusId(int $babySitter, int $statusId);

    public function updateAppointment(int $appointmentId, array $data);

    public function getBabySitterPendingApproveAppointments(int $babySitter);

    public function getBabySitterPendingPayment(int $babySitter);

    public function getPaidAppointments(int $babySitter);

    public function getNotApprovedAppointments(int $babySitter);

    public function approveAppointment(int $appointmentId);

    public function store(array $data);

    public function getPastAppointmentsByParentId(int $parentId);

    public function getCanceledAppointmentsByParentId(int $parentId);

    public function getFutureAppointmentsByParentId(int $parentId);

    public function getPastAppointmentsByBabySitterId(int $babySitterId);

    public function getFutureAppointmentsByBabySitterId(int $babySitterId);

    public function getCanceledAppointmentsByBabySitterId(int $babySitterId);

    public function getUpcomingAppointments(int $babySitterId);
}
