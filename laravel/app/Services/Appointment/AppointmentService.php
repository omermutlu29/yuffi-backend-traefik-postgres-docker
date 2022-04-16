<?php


namespace App\Services\Appointment;


use App\Http\Resources\CalendarGetResource;
use App\Interfaces\IRepositories\IAppointmentRepository;
use App\Interfaces\IRepositories\IBabySitterRepository;
use App\Interfaces\IServices\IAppointmentService;
use App\Models\BabySitter;
use App\Models\Parents;
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
        return $this->appointmentRepository->disapprove($appointmentId);
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
            //TODO Bakılacak
            $calculatedTimes = CalendarGetResource::generateTimesForSearching($data['time'], $data['hour']);
            return DB::transaction(function () use ($appointmentData, $data) {
                $appointment = $this->appointmentRepository->store($appointmentData);
                if (!$appointment)
                    throw new \Exception('Appointment could not created', 401);
                foreach ($data['children'] as $child) {
                    $appointment->registered_children()->create($child);
                }
                return $appointment;
            });
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function cancelAppointment(int $appointmentId, $user)
    {
        try {
            $appointment = $this->appointmentRepository->getAppointmentById($appointmentId);
            if (!$appointment) {
                throw new \Exception('Appointment could not find', 400);
            }

            if ($user instanceof BabySitter) {
                if ($appointment->baby_sitter_id != $user->id) {
                    throw new \Exception('Kendinize ait olmayan randevuyu iptal edemezsiniz!', 400);
                }
                return $this->appointmentRepository->updateAppointment(
                    $appointmentId,
                    [
                        'appointment_status_id' => 2,
                        'is_rejected_by_baby_sitter' => true,
                        'rejected_time_range' => now()->diffInHours($appointment->created_at)
                    ]);
            }
            if ($user instanceof Parents) {
                if ($appointment->parent_id != $user->id) {
                    throw new \Exception('Kendinize ait olmayan randevuyu iptal edemezsiniz!', 400);
                }
                return $this->appointmentRepository->updateAppointment($appointmentId, [
                    'appointment_status_id' => 3,
                    'is_rejected_by_baby_sitter' => false,
                    'rejected_time_range' => now()->diffInHours($appointment->created_at)
                ]);
            }
        } catch (\Exception $exception) {
            throw new \Exception('Randevu iptal edilemedi', 400);
        }

    }
}
