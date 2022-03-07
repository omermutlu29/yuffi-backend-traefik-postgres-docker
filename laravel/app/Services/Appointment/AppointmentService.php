<?php


namespace App\Services\Appointment;


use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IServices\IAppointmentService;
use Illuminate\Support\Facades\DB;

class AppointmentService implements IAppointmentService
{
    private IAppointmentRepository $appointmentRepository;
    private IBabySitterRepository $babySitterRepository;

    public function __construct(
        IAppointmentRepository $appointmentRepository,
        IBabySitterRepository $babySitterRepository
    )
    {
        $this->babySitterRepository = $babySitterRepository;
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

    public function approveAppointment(int $appointmentId)
    {
        return $this->appointmentRepository->approveAppointment($appointmentId);
    }

    public function disapproveAppointment(int $appointmentId)
    {
        return $this->appointmentRepository->disapproveAppointment($appointmentId);
    }


    public function create(int $babySitterId, int $parentId, array $data)
    {
        try {
            $babySitter = $this->babySitterRepository->getUserById($babySitterId);
            if (!$babySitter) {
                throw new \Exception('Babysitter could not found', 401);
            }
            $appointmentData = [
                'baby_sitter_id' => $babySitterId,
                'parent_id' => $parentId,
                'town_id' => $data['town_id'],
                'appointment_status_id' => 1,
                'date' => $data['date'],
                'hour' => $data['hour'],
                'start' => $data['time'],
                'finish' => (date('H:i', strtotime("+" . $data['hour'] . " Hour " . $data['time']))),
                'price' => $data['hour'] * $babySitter->price_per_hour,
                'appointment_location_id' => $data['location_id'],
                'location' => $data['location'] ?? null
            ];
            DB::transaction(function () use ($appointmentData, $data) {
                $appointment = $this->appointmentRepository->store($appointmentData);
                if (!$appointment) {
                    throw new \Exception('Appointment could not created', 401);
                }
                foreach ($data['children'] as $child) {
                    $appointment->registered_children()->create($child);
                }
            });
            return true;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
